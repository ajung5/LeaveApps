<?php

use Illuminate\Support\Facades\Artisan;

Artisan::command('cuti:info', function () {
    $this->info('Sistem Cuti Pegawai - Laravel 13');
});
