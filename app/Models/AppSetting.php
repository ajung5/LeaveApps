<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AppSetting extends Model
{
    public $timestamps=false;
    protected $primaryKey='key'; public $incrementing=false; protected $keyType='string';
    protected $fillable=['key','value'];
    public static function read(string $key, string $default=''): string
    {
        return (string) (static::query()->whereKey($key)->value('value') ?? $default);
    }
}
