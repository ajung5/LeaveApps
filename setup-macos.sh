#!/usr/bin/env bash
set -euo pipefail
cd "$(dirname "$0")"
command -v php >/dev/null 2>&1 || { echo 'PHP tidak ditemukan. Install PHP 8.3+ terlebih dulu.'; exit 1; }
command -v composer >/dev/null 2>&1 || { echo 'Composer tidak ditemukan. Install composer terlebih dulu.'; exit 1; }
php -r 'if (PHP_VERSION_ID < 80300) {fwrite(STDERR,"Memerlukan PHP 8.3+.\n"); exit(1);} foreach (["mbstring","dom","fileinfo","pdo_sqlite"] as $e) {if (!extension_loaded($e)) {fwrite(STDERR,"Ekstensi PHP belum aktif: $e\n"); exit(1);}}'
composer install
if [[ ! -f .env ]]; then cp .env.example .env; fi
mkdir -p database storage/app/private storage/framework/cache/data storage/framework/sessions storage/framework/views storage/logs
if [[ ! -f database/database.sqlite ]]; then touch database/database.sqlite; fi
php artisan key:generate
php artisan migrate --seed
echo
printf 'Setup aplikasi selesai. Sekarang isi ADMIN_EMAIL dan ADMIN_PASSWORD pada .env, lalu:\n  php artisan db:seed --class=AdminSeeder\n  php artisan test\n  php artisan serve\n'
