<?php

namespace App\Services;

use Google\Client as GoogleClient;
use Google\Service\Drive as GoogleDrive;
use Google\Service\Drive\DriveFile;
use Google\Service\Drive\Permission;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Throwable;

class GoogleDriveService
{
    protected ?GoogleClient $client = null;
    protected ?GoogleDrive $service = null;

    /**
     * Check if Google Drive is properly configured with credentials.
     */
    public function isConfigured(): bool
    {
        $clientId = env('GOOGLE_DRIVE_CLIENT_ID');
        $clientSecret = env('GOOGLE_DRIVE_CLIENT_SECRET');
        $refreshToken = env('GOOGLE_DRIVE_REFRESH_TOKEN');
        $accessToken = env('GOOGLE_DRIVE_ACCESS_TOKEN');

        $hasToken = (!empty($refreshToken) && $refreshToken !== 'xxx') || (!empty($accessToken) && $accessToken !== 'xxx');

        return !empty($clientId) && !empty($clientSecret) && $hasToken;
    }

    /**
     * Initialize Google Client.
     */
    public function getClient(): GoogleClient
    {
        if ($this->client !== null) {
            return $this->client;
        }

        $client = new GoogleClient();
        $client->setClientId(env('GOOGLE_DRIVE_CLIENT_ID'));
        $client->setClientSecret(env('GOOGLE_DRIVE_CLIENT_SECRET'));
        $client->setRedirectUri(env('GOOGLE_REDIRECT_URI', url('/google-drive/callback')));
        $client->addScope(GoogleDrive::DRIVE);
        $client->setAccessType('offline');
        $client->setPrompt('consent');

        $accessToken = env('GOOGLE_DRIVE_ACCESS_TOKEN');
        $refreshToken = env('GOOGLE_DRIVE_REFRESH_TOKEN');

        if (!empty($accessToken) && $accessToken !== 'xxx') {
            $client->setAccessToken($accessToken);
        }

        if ($client->isAccessTokenExpired() && !empty($refreshToken) && $refreshToken !== 'xxx') {
            try {
                $newToken = $client->fetchAccessTokenWithRefreshToken($refreshToken);
                if (!empty($newToken['access_token'])) {
                    static::updateEnvKey('GOOGLE_DRIVE_ACCESS_TOKEN', $newToken['access_token']);
                }
            } catch (Throwable $e) {
                Log::error('Google Drive fetchAccessToken error: ' . $e->getMessage());
            }
        }

        $this->client = $client;
        return $this->client;
    }

    /**
     * Get Google Drive Service instance.
     */
    public function getDrive(): GoogleDrive
    {
        if ($this->service !== null) {
            return $this->service;
        }

        $this->service = new GoogleDrive($this->getClient());
        return $this->service;
    }

    /**
     * Extract Folder ID from full Google Drive URL or raw folder ID.
     */
    public function extractFolderId(?string $folderUrlOrId): ?string
    {
        if (empty($folderUrlOrId)) {
            return null;
        }

        $folderUrlOrId = trim($folderUrlOrId);

        // Check if URL: https://drive.google.com/drive/folders/FOLDER_ID?...
        if (preg_match('/\/folders\/([a-zA-Z0-9_-]+)/', $folderUrlOrId, $matches)) {
            return $matches[1];
        }

        // Check if query param: id=FOLDER_ID
        if (preg_match('/[?&]id=([a-zA-Z0-9_-]+)/', $folderUrlOrId, $matches)) {
            return $matches[1];
        }

        // Otherwise assume raw ID if alphanumeric
        if (preg_match('/^[a-zA-Z0-9_-]+$/', $folderUrlOrId)) {
            return $folderUrlOrId;
        }

        return null;
    }

    /**
     * Extract File ID from Google Drive URL.
     */
    public function extractFileId(?string $url): ?string
    {
        if (!$url) return null;

        if (preg_match('/\/d\/([a-zA-Z0-9_-]+)/', $url, $matches)) {
            return $matches[1];
        }

        if (preg_match('/[?&]id=([a-zA-Z0-9_-]+)/', $url, $matches)) {
            return $matches[1];
        }

        return null;
    }

    /**
     * Upload an uploaded file or file path into Google Drive folder.
     * Falls back to local public disk if Google Drive is not configured or fails.
     *
     * @param UploadedFile|string $file
     * @param string $folderConfigKeyOrId .env key (e.g. 'GOOGLE_DRIVE_UPLOAD_FILE_SERTIFIKAT') or folder URL / ID
     * @param string $localFallbackSubdir directory name for fallback in storage/app/public
     * @return array{url: string, drive: bool, file_id: ?string, name: string}
     */
    public function upload($file, string $folderConfigKeyOrId, string $localFallbackSubdir = 'uploads'): array
    {
        // 1. Resolve folder ID from .env or param
        $rawFolder = env($folderConfigKeyOrId, $folderConfigKeyOrId);
        $folderId = $this->extractFolderId($rawFolder);

        $originalName = ($file instanceof UploadedFile)
            ? $file->getClientOriginalName()
            : basename($file);
        $safeOriginal = preg_replace('/[^A-Za-z0-9\.\-_ ]/', '', $originalName);
        $safeFilename = time() . '_' . $safeOriginal;

        // 2. Try Google Drive upload if configured
        if ($this->isConfigured() && $folderId) {
            try {
                $service = $this->getDrive();

                $fileMetadata = new DriveFile([
                    'name'    => $safeFilename,
                    'parents' => [$folderId],
                ]);

                $filePath = ($file instanceof UploadedFile) ? $file->getRealPath() : $file;
                $content = File::get($filePath);
                $mimeType = ($file instanceof UploadedFile)
                    ? ($file->getClientMimeType() ?: 'application/octet-stream')
                    : (File::mimeType($filePath) ?: 'application/octet-stream');

                $driveFile = $service->files->create($fileMetadata, [
                    'data'       => $content,
                    'mimeType'   => $mimeType,
                    'uploadType' => 'multipart',
                    'fields'     => 'id, webViewLink, webContentLink',
                    'supportsAllDrives' => true,
                ]);

                $fileId = $driveFile->getId();

                // Make file accessible by link
                $this->makePublic($fileId);

                $viewUrl = $driveFile->getWebViewLink() ?: "https://drive.google.com/file/d/{$fileId}/view";

                return [
                    'url'     => $viewUrl,
                    'drive'   => true,
                    'file_id' => $fileId,
                    'name'    => $safeFilename,
                ];
            } catch (Throwable $e) {
                Log::error("Google Drive Upload Error: " . $e->getMessage());
                // Fall through to local fallback
            }
        }

        // 3. Fallback to local storage/app/public
        if ($file instanceof UploadedFile) {
            $localPath = $file->storeAs($localFallbackSubdir, $safeFilename, 'public');
        } else {
            $localPath = $localFallbackSubdir . '/' . $safeFilename;
            Storage::disk('public')->put($localPath, File::get($file));
        }

        return [
            'url'     => $localPath,
            'drive'   => false,
            'file_id' => null,
            'name'    => $safeFilename,
        ];
    }

    /**
     * Make Drive file public reader.
     */
    public function makePublic(string $fileId): void
    {
        try {
            $service = $this->getDrive();
            $permission = new Permission([
                'type' => 'anyone',
                'role' => 'reader',
            ]);

            $service->permissions->create($fileId, $permission, [
                'fields' => 'id',
                'supportsAllDrives' => true,
            ]);
        } catch (Throwable $e) {
            Log::warning("Could not set permission for Drive file {$fileId}: " . $e->getMessage());
        }
    }

    /**
     * Delete file from Google Drive (or local storage).
     */
    public function deleteFile(?string $urlOrPath): void
    {
        if (!$urlOrPath) return;

        // If Google Drive URL
        if (str_contains($urlOrPath, 'drive.google.com')) {
            $fileId = $this->extractFileId($urlOrPath);
            if ($fileId && $this->isConfigured()) {
                try {
                    $service = $this->getDrive();
                    $service->files->delete($fileId, ['supportsAllDrives' => true]);
                } catch (Throwable $e) {
                    Log::error("Google Drive delete file error: " . $e->getMessage());
                }
            }
        } else {
            // Local file
            if (Storage::disk('public')->exists($urlOrPath)) {
                Storage::disk('public')->delete($urlOrPath);
            }
        }
    }

    /**
     * Save key-value to .env file.
     */
    public static function updateEnvKey(string $key, string $value): void
    {
        $envPath = base_path('.env');
        if (!file_exists($envPath)) {
            return;
        }

        $content = file_get_contents($envPath);
        if (preg_match("/^{$key}=.*/m", $content)) {
            $content = preg_replace("/^{$key}=.*/m", "{$key}={$value}", $content);
        } else {
            $content .= "\n{$key}={$value}";
        }

        file_put_contents($envPath, $content);
    }
}

