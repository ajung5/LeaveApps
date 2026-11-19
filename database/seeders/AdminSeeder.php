<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use RuntimeException;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        $email = env('ADMIN_EMAIL');
        $password = env('ADMIN_PASSWORD');
        if (! $email || ! filter_var($email, FILTER_VALIDATE_EMAIL) || ! $password || strlen($password)<12) {
            throw new RuntimeException('Isi ADMIN_EMAIL valid dan ADMIN_PASSWORD minimal 12 karakter pada .env dahulu.');
        }
        if (User::where('email', $email)->exists()) {
            $this->command->info('Email admin sudah ada; kata sandi tidak direset.');
            return;
        }
        User::create(['name'=>env('ADMIN_NAME','Administrator'),'email'=>$email,'password'=>$password,'role'=>'admin']);
        $this->command->info('Akun admin berhasil dibuat. Hapus ADMIN_PASSWORD dari .env setelah bootstrap.');
    }
}
