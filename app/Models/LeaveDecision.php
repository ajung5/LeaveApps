<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LeaveDecision extends Model {
    protected $fillable = [
        'leave_request_id',
        'recorded_by',
        'stage',
        'decision',
        'official_name',
        'official_nip',
        'official_position',
        'reference',
        'notes',
        'decision_date'
    ];
    protected function casts(): array {
        return ['decision_date' => 'date'];
    }
}
