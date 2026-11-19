<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Employee extends Model {
    protected $fillable = [
        'user_id',
        'organizational_unit_id',
        'nip',
        'full_name',
        'employment_type',
        'rank',
        'grade',
        'position',
        'service_start_date',
        'phone',
        'leave_address',
        'supervisor_name',
        'supervisor_nip',
        'supervisor_position'
    ];
    protected function casts(): array {
        return ['service_start_date' => 'date'];
    }
    public function user(): BelongsTo {
        return $this->belongsTo(User::class);
    }
    public function unit(): BelongsTo {
        return $this->belongsTo(OrganizationalUnit::class, 'organizational_unit_id');
    }
    public function requests(): HasMany {
        return $this->hasMany(LeaveRequest::class);
    }
    public function balances(): HasMany {
        return $this->hasMany(LeaveBalance::class);
    }
}
