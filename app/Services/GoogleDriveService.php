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
        if (!class_exists(\Google\Client::class)) {
            return false;
        }

        $clientId = env('GOOGLE_DRIVE_CLIENT_ID');
        $clientSecret = env('GOOGLE_DRIVE_CLIENT_SECRET');
        $refreshToken = env('GOOGLE_DRIVE_REFRESH_TOKEN');
        $accessToken = env('GOOGLE_DRIVE_ACCESS_TOKEN');

        $hasToken = (!empty($refreshToken) && $refreshToken !== 'xxx') || (!empty($accessToken) && $accessToken !== 'xxx');

        return !empty($clientId) && !empty($clientSecret) && $hasToken;
    }

    /**
     * Resolve a relative or absolute local file path across known Laravel disks.
     */
    public function resolveLocalFilePath(string $path): ?string
    {
        if (file_exists($path) && is_file($path)) {
            return $path;
        }

        $clean = ltrim($path, '/\\');

        $candidates = [
            Storage::disk('public')->path($path),
            Storage::disk('public')->path($clean),
            storage_path('app/public/' . $clean),
            storage_path('app/' . $clean),
            public_path('storage/' . $clean),
            public_path($clean),
            base_path($clean),
        ];

        foreach ($candidates as $cand) {
            if (file_exists($cand) && is_file($cand)) {
                return $cand;
            }
        }

        return null;
    }

    /**
     * Get CA certificate bundle path for SSL verification on Windows.
     */
    public static function getCaBundlePath(): string|bool
    {
        $candidates = [
            'C:/laragon/etc/ssl/cacert.pem',
            ini_get('curl.cainfo'),
            ini_get('openssl.cafile'),
            'C:/laragon/bin/python/python-3.10/Lib/site-packages/certifi/cacert.pem',
        ];

        foreach ($candidates as $path) {
            if (!empty($path) && file_exists($path)) {
                return $path;
            }
        }

        return true;
    }

    /**
     * Initialize Google Client.
     */
    public function getClient(): GoogleClient
    {
        if ($this->client !== null) {
            return $this->client;
        }

        $caPath = self::getCaBundlePath();
        $guzzle = new \GuzzleHttp\Client([
            'verify' => $caPath,
        ]);

        $client = new GoogleClient();
        $client->setHttpClient($guzzle);
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
     * Extract File ID from Google Drive URL or raw file ID.
     */
    public function extractFileId(?string $url): ?string
    {
        if (!$url) return null;

        $url = trim($url);

        if (preg_match('/\/d\/([a-zA-Z0-9_-]+)/', $url, $matches)) {
            return $matches[1];
        }

        if (preg_match('/[?&]id=([a-zA-Z0-9_-]+)/', $url, $matches)) {
            return $matches[1];
        }

        // If raw alphanumeric ID (at least 20 chars without slashes)
        if (preg_match('/^[a-zA-Z0-9_-]{20,}$/', $url)) {
            return $url;
        }

        return null;
    }

    /**
     * Format file name following the convention: {tanggal}_{nama_login}_{nama_file}
     * Contoh: 2026_11_06_admin_nama_file
     */
    public function formatFileName(string $originalName, ?string $userName = null, ?string $date = null): string
    {
        // 1. Tanggal (YYYY_MM_DD)
        $rawDate = $date ?: date('Y_m_d');
        $dateStr = str_replace('-', '_', $rawDate);

        // 2. Nama yang login submit
        $rawUser = $userName ?: (auth()->check() ? auth()->user()->name : 'user');
        $cleanUser = trim(preg_replace('/[^A-Za-z0-9]/', '_', $rawUser), '_');
        $cleanUser = strtolower(preg_replace('/_+/', '_', $cleanUser));
        if (empty($cleanUser)) {
            $cleanUser = 'user';
        }

        // 3. Nama file asli
        $cleanOriginal = preg_replace('/[^A-Za-z0-9\.\-_]/', '_', $originalName);
        $cleanOriginal = trim(preg_replace('/_+/', '_', $cleanOriginal), '_');

        // Bersihkan prefix tanggal atau user terdahulu jika ada agar tidak menumpuk
        $cleanOriginal = preg_replace('/^(user|[a-zA-Z0-9_-]+?)_\d{4}[_-]\d{2}[_-]\d{2}_/i', '', $cleanOriginal);
        $cleanOriginal = preg_replace('/^\d{4}[_-]\d{2}[_-]\d{2}_[a-zA-Z0-9_-]+?_/i', '', $cleanOriginal);
        $cleanOriginal = trim($cleanOriginal, '_');

        return "{$dateStr}_{$cleanUser}_{$cleanOriginal}";
    }

    /**
     * Upload an uploaded file or file path into Google Drive folder.
     * Naming format on Google Drive: {nama_user}_{date}_{nama_file}
     * Falls back to local public disk if Google Drive is not configured or fails.
     *
     * @param UploadedFile|string $file
     * @param string $folderConfigKeyOrId .env key (e.g. 'GOOGLE_DRIVE_UPLOAD_FILE_SERTIFIKAT') or folder URL / ID
     * @param string $localFallbackSubdir directory name for fallback in storage/app/public
     * @param string|null $userName Owner name to prefix the file name
     * @return array{url: string, drive: bool, file_id: ?string, name: string}
     */
    public function upload($file, string $folderConfigKeyOrId, string $localFallbackSubdir = 'uploads', ?string $userName = null): array
    {
        // 1. Resolve folder ID from .env or param
        $rawFolder = env($folderConfigKeyOrId, $folderConfigKeyOrId);
        $folderId = $this->extractFolderId($rawFolder);

        $originalName = ($file instanceof UploadedFile)
            ? $file->getClientOriginalName()
            : basename($file);

        // Target naming format: {nama_user}_{date}_{nama_file}
        $safeFilename = $this->formatFileName($originalName, $userName);

        // 2. Try Google Drive upload if configured
        if ($this->isConfigured() && $folderId) {
            try {
                $service = $this->getDrive();

                $fileMetadata = new DriveFile([
                    'name'    => $safeFilename,
                    'parents' => [$folderId],
                ]);

                if ($file instanceof UploadedFile) {
                    $filePath = $file->getRealPath();
                } else {
                    $filePath = $this->resolveLocalFilePath($file);
                }

                if (!$filePath || !file_exists($filePath)) {
                    Log::warning("Google Drive: Berkas lokal tidak ditemukan: {$file}");
                    return [
                        'url'     => is_string($file) ? $file : '',
                        'drive'   => false,
                        'file_id' => null,
                        'name'    => $safeFilename,
                    ];
                }

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
            $resolved = $this->resolveLocalFilePath($file);
            if ($resolved && file_exists($resolved)) {
                Storage::disk('public')->put($localPath, File::get($resolved));
            } else {
                $localPath = $file;
            }
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
    public function deleteFile(?string $urlOrPath): bool
    {
        if (!$urlOrPath) return false;

        // If Google Drive URL or ID
        if (str_contains($urlOrPath, 'drive.google.com') || preg_match('/^[a-zA-Z0-9_-]{20,}$/', $urlOrPath)) {
            $fileId = $this->extractFileId($urlOrPath);
            if ($fileId && $this->isConfigured()) {
                try {
                    $service = $this->getDrive();
                    $service->files->delete($fileId, ['supportsAllDrives' => true]);
                    Log::info("Google Drive: Berhasil menghapus file ID {$fileId}");
                    return true;
                } catch (Throwable $e) {
                    Log::error("Google Drive delete file error: " . $e->getMessage());
                    return false;
                }
            }
        } else {
            // Local file
            if (Storage::disk('public')->exists($urlOrPath)) {
                return Storage::disk('public')->delete($urlOrPath);
            }
        }

        return false;
    }

    /**
     * Rename an existing file on Google Drive to match new owner name.
     */
    public function renameExistingFile(string $urlOrPath, string $newOwnerName, string $fallbackLabel = 'dokumen'): bool
    {
        $fileId = $this->extractFileId($urlOrPath);
        if (!$fileId || !$this->isConfigured()) {
            return false;
        }

        try {
            $service = $this->getDrive();
            $file = $service->files->get($fileId, ['fields' => 'name', 'supportsAllDrives' => true]);
            $currentName = $file->getName() ?: "{$fallbackLabel}.pdf";

            $ext = pathinfo($currentName, PATHINFO_EXTENSION);
            $ext = $ext ? ".{$ext}" : '';

            $cleanUser = trim(preg_replace('/[^A-Za-z0-9]/', '_', $newOwnerName), '_');
            $cleanUser = strtolower(preg_replace('/_+/', '_', $cleanUser));
            if (empty($cleanUser)) {
                $cleanUser = 'user';
            }

            $dateStr = date('Y_m_d');
            $cleanBase = pathinfo($currentName, PATHINFO_FILENAME);
            $cleanBase = preg_replace('/^(user|[a-zA-Z0-9_-]+?)_\d{4}[_-]\d{2}[_-]\d{2}_/i', '', $cleanBase);
            $cleanBase = preg_replace('/^\d{4}[_-]\d{2}[_-]\d{2}_[a-zA-Z0-9_-]+?_/i', '', $cleanBase);
            $cleanBase = trim($cleanBase, '_');

            $newName = "{$dateStr}_{$cleanUser}_{$cleanBase}{$ext}";

            $driveFile = new DriveFile(['name' => $newName]);
            $service->files->update($fileId, $driveFile, ['supportsAllDrives' => true]);
            Log::info("Google Drive: Berhasil mengubah nama file {$fileId} menjadi {$newName}");
            return true;
        } catch (Throwable $e) {
            Log::error("Google Drive rename file error: " . $e->getMessage());
            return false;
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
        $pattern = "/^{$key}=.*/m";
        $replacement = "{$key}={$value}";

        if (preg_match($pattern, $content)) {
            $content = preg_replace_callback($pattern, fn() => $replacement, $content);
        } else {
            $content .= "\n{$replacement}";
        }

        file_put_contents($envPath, $content);
    }
}

