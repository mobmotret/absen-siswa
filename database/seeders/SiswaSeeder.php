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
            'nis' => '12345',
            'nama_lengkap' => 'Ircham Ali',
            'kelas' => 'XII RPL',
            'no_hp' => '0812345559',
            'password' => bcrypt('12345'),
        ]);
    }
}
