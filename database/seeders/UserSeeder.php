<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [
            // Superadmin
            [
                'name'      => 'Super Admin SIMMAWA',
                'email'     => 'superadmin@uis.ac.id',
                'password'  => Hash::make('password'),
                'role'      => 'superadmin',
                'is_active' => true,
            ],
            [
                'name'      => 'System Administrator',
                'email'     => 'admin@uis.ac.id',
                'password'  => Hash::make('password'),
                'role'      => 'superadmin',
                'is_active' => true,
            ],

            // Admin BKAK
            [
                'name'      => 'Admin BKAK Utama',
                'email'     => 'adminbkak@uis.ac.id',
                'password'  => Hash::make('password'),
                'role'      => 'adminbkak',
                'is_active' => true,
            ],
            [
                'name'      => 'Siti Rahmawati, S.Kom',
                'email'     => 'siti.rahmawati@uis.ac.id',
                'password'  => Hash::make('password'),
                'role'      => 'adminbkak',
                'is_active' => true,
            ],

            // Kabid
            [
                'name'      => 'Dr. H. Ahmad Dahlan, M.Pd.',
                'email'     => 'kabid@uis.ac.id',
                'password'  => Hash::make('password'),
                'role'      => 'kabid',
                'is_active' => true,
            ],
            [
                'name'      => 'Dr. Ratna Sari, M.Si.',
                'email'     => 'ratna.kabid@uis.ac.id',
                'password'  => Hash::make('password'),
                'role'      => 'kabid',
                'is_active' => true,
            ],

            // Staff Kemahasiswaan
            [
                'name'      => 'Staff Kemahasiswaan',
                'email'     => 'staff@uis.ac.id',
                'password'  => Hash::make('password'),
                'role'      => 'staff',
                'is_active' => true,
            ],
            [
                'name'      => 'Budi Santoso, A.Md.',
                'email'     => 'budi.staff@uis.ac.id',
                'password'  => Hash::make('password'),
                'role'      => 'staff',
                'is_active' => true,
            ],
            [
                'name'      => 'Dewi Lestari, S.E.',
                'email'     => 'dewi.staff@uis.ac.id',
                'password'  => Hash::make('password'),
                'role'      => 'staff',
                'is_active' => false,
            ],

            // Pimpinan Universitas
            [
                'name'      => 'Prof. Dr. Ir. H. Muhammad Ridwan, M.T.',
                'email'     => 'pimpinan@uis.ac.id',
                'password'  => Hash::make('password'),
                'role'      => 'pimpinan',
                'is_active' => true,
            ],
            [
                'name'      => 'Dr. Hj. Nurhayati, M.M.',
                'email'     => 'warek3@uis.ac.id',
                'password'  => Hash::make('password'),
                'role'      => 'pimpinan',
                'is_active' => true,
            ],

            // Admin Program Studi
            [
                'name'      => 'Admin Prodi Teknik Informatika',
                'email'     => 'prodi@uis.ac.id',
                'password'  => Hash::make('password'),
                'role'      => 'prodi',
                'is_active' => true,
            ],
            [
                'name'      => 'Admin Prodi Sistem Informasi',
                'email'     => 'prodi.si@uis.ac.id',
                'password'  => Hash::make('password'),
                'role'      => 'prodi',
                'is_active' => true,
            ],
            [
                'name'      => 'Admin Prodi Manajemen',
                'email'     => 'prodi.manajemen@uis.ac.id',
                'password'  => Hash::make('password'),
                'role'      => 'prodi',
                'is_active' => true,
            ],

            // Dosen Pendamping
            [
                'name'      => 'Dosen Pendamping Utama',
                'email'     => 'dosenpendamping@uis.ac.id',
                'password'  => Hash::make('password'),
                'role'      => 'dosenpendamping',
                'is_active' => true,
            ],
            [
                'name'      => 'Hendra Wijaya, S.T., M.Eng.',
                'email'     => 'hendra.dosen@uis.ac.id',
                'password'  => Hash::make('password'),
                'role'      => 'dosenpendamping',
                'is_active' => true,
            ],
            [
                'name'      => 'Maya Indah, S.Kom., M.T.',
                'email'     => 'maya.dosen@uis.ac.id',
                'password'  => Hash::make('password'),
                'role'      => 'dosenpendamping',
                'is_active' => true,
            ],

            // Mahasiswa
            [
                'name'          => 'Mahasiswa Berprestasi',
                'email'         => 'mahasiswa@uis.ac.id',
                'password'      => Hash::make('password'),
                'role'          => 'mahasiswa',
                'nim'           => '201055201001',
                'fakultas'      => 'Fakultas Sains dan Teknologi',
                'program_studi' => 'S1 - Teknik Informatika',
                'organisasi'    => 'BEM Universitas Ibnu Sina, HMTI - Teknik Informatika',
                'jabatan'       => 'Presiden Mahasiswa (Presma) (BEM UIS), Anggota (HMTI)',
                'is_active'     => true,
            ],
            [
                'name'          => 'Frida Ayu Wulandari',
                'email'         => 'frida.wulandari@student.uis.ac.id',
                'password'      => Hash::make('password'),
                'role'          => 'mahasiswa',
                'nim'           => '201055201025',
                'fakultas'      => 'Fakultas Ekonomi dan Bisnis',
                'program_studi' => 'S1 - Manajemen',
                'organisasi'    => 'HMM - Manajemen',
                'jabatan'       => 'Ketua (HMM)',
                'is_active'     => true,
            ],
            [
                'name'      => 'Rizky Pratama Putra',
                'email'     => 'rizky.pratama@student.uis.ac.id',
                'password'  => Hash::make('password'),
                'role'      => 'mahasiswa',
                'is_active' => true,
            ],
            [
                'name'      => 'Anisa Nurul Hidayah',
                'email'     => 'anisa.hidayah@student.uis.ac.id',
                'password'  => Hash::make('password'),
                'role'      => 'mahasiswa',
                'is_active' => false,
            ],
        ];

        foreach ($users as $userData) {
            User::updateOrCreate(
                ['email' => $userData['email']],
                $userData
            );
        }
    }
}
