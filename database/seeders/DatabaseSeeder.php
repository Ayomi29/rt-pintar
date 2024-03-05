<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\FamilyCard;
use App\Models\FamilyMember;
use App\Models\House;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::create([
            'email' => 'admnrt15balikpapan@gmail.com',
            'phone_number' => '081257418596',
            'password' => bcrypt('admin123')
        ]);
        Role::create([
            'user_id' => 1,
            'role_name' => 'admin'
        ]);
        House::create([
            'house_number' => '01'
        ]);
        House::create([
            'house_number' => '02'
        ]);
        FamilyCard::create([
            'house_id' => '1',
            'family_card_number' => '1111111111111111',
            'status' => 'aktif'
        ]);
        
        FamilyMember::create([
            'user_id' => '1',
            'family_card_id' => '1',
            'family_member_name' => 'admin',
            'nik' => '1111111111111100',
            'gender' => 'laki-laki',
            'birth_place' => 'bali',
            'birth_date' => '27-12-1978',
            'job' => 'ketua rt',
            'religious' => 'islam',
            'education' => 'S1',
            'citizenship' => 'Indonesia',
            'family_status' => 'kepala keluarga',
            'marital_status' => 'Menikah',
            'address' => 'test',
            'verified' => '1',
            'status' => 'aktif'
        ]);
        FamilyMember::create([
            'family_card_id' => '1',
            'family_member_name' => 'warga',
            'nik' => '1111111111111100',
            'gender' => 'laki-laki',
            'birth_place' => 'bali',
            'birth_date' => '27-12-2000',
            'job' => 'pelajar',
            'religious' => 'islam',
            'education' => 'SMA',
            'citizenship' => 'Indonesia',
            'family_status' => 'anak',
            'marital_status' => 'Belum Menikah',
            'address' => 'test',
            'verified' => '0',
            'status' => 'aktif'
        ]);
    }
}
