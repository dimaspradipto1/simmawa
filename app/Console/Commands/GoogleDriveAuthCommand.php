<?php

namespace App\Console\Commands;

use App\Services\GoogleDriveService;
use Illuminate\Console\Command;
use Throwable;

class GoogleDriveAuthCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'drive:auth {--code= : Google OAuth authorization code}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Authorize SIMAWA with Google Drive OAuth and save refresh token to .env';

    /**
     * Execute the console command.
     */
    public function handle(GoogleDriveService $driveService): int
    {
        $clientId = env('GOOGLE_DRIVE_CLIENT_ID');
        $clientSecret = env('GOOGLE_DRIVE_CLIENT_SECRET');

        if (empty($clientId) || empty($clientSecret)) {
            $this->error('GOOGLE_DRIVE_CLIENT_ID dan GOOGLE_DRIVE_CLIENT_SECRET belum diatur di .env');
            return Command::FAILURE;
        }

        $client = $driveService->getClient();

        $code = $this->option('code');
        if (empty($code)) {
            $authUrl = $client->createAuthUrl();

            $this->info('===============================================================');
            $this->info('               GOOGLE DRIVE OAUTH AUTHORIZATION                ');
            $this->info('===============================================================');
            $this->line('');
            $this->line('1. Buka tautan berikut di browser Anda:');
            $this->line($authUrl);
            $this->line('');
            $this->line('2. Masuk dengan Akun Google Drive dan setujui izin akses.');
            $this->line('3. Setelah disetujui, salin parameter `code` dari URL callback atau halaman Google.');
            $this->line('');

            $code = $this->ask('Masukkan Authorization Code yang Anda peroleh:');
        }

        if (empty($code)) {
            $this->error('Authorization code tidak boleh kosong.');
            return Command::FAILURE;
        }

        try {
            $token = $client->fetchAccessTokenWithAuthCode(trim($code));

            if (isset($token['error'])) {
                $errorMsg = $token['error_description'] ?? $token['error'];
                $this->error("Gagal memperoleh token: {$errorMsg}");
                return Command::FAILURE;
            }

            $refreshToken = $token['refresh_token'] ?? null;
            $accessToken = $token['access_token'] ?? null;

            if ($refreshToken) {
                GoogleDriveService::updateEnvKey('GOOGLE_DRIVE_REFRESH_TOKEN', $refreshToken);
                $this->info("GOOGLE_DRIVE_REFRESH_TOKEN berhasil disimpan ke .env!");
            } else {
                $this->warn('Peringatan: Google tidak mengembalikan refresh_token baru. Jika sebelumnya sudah pernah diotorisasi, gunakan refresh token yang sudah ada.');
            }

            if ($accessToken) {
                GoogleDriveService::updateEnvKey('GOOGLE_DRIVE_ACCESS_TOKEN', $accessToken);
                $this->info("GOOGLE_DRIVE_ACCESS_TOKEN berhasil disimpan ke .env!");
            }

            $this->info('');
            $this->info('SELAMAT! Google Drive telah aktif dan siap digunakan untuk upload berkas SIMAWA.');
            return Command::SUCCESS;
        } catch (Throwable $e) {
            $this->error('Terjadi kesalahan: ' . $e->getMessage());
            return Command::FAILURE;
        }
    }
}

