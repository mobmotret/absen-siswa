<?php

namespace Database\Seeders;

use App\Models\Siswa;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SiswaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Siswa::create([
            'nis' => '10203040',
            'nama_lengkap' => 'Budi Setiawan',
            'kelas' => 'XII RPL',
            'no_hp' => '08123456789',
            'password' => bcrypt('123456'),
        ]);
    }
}
