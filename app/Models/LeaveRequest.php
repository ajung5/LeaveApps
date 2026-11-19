<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class LeaveRequest extends Model {
    protected $fillable = [
        'request_number',
        'employee_id',
        'leave_type_id',
        'start_date',
        'end_date',
        'duration_days',
        'reason',
        'leave_address',
        'contact_phone',
        'status',
        'revision_notes',
        'submitted_at',
        'letter_number',
        'issued_at',
        'employee_snapshot',
        'official_snapshot',
        'signed_document_path'
    ];
    protected function casts(): array {
        return [
            'start_date' => 'date',
            'end_date' => 'date',
            'submitted_at' => 'datetime',
            'issued_at' => 'datetime',
            'employee_snapshot' => 'array',
            'official_snapshot' => 'array'
        ];
    }
    public function employee(): BelongsTo {
        return $this->belongsTo(Employee::class);
    }
    public function type(): BelongsTo {
        return $this->belongsTo(LeaveType::class, 'leave_type_id');
    }
    public function decisions(): HasMany {
        return $this->hasMany(LeaveDecision::class);
    }
    public function attachments(): HasMany {
        return $this->hasMany(LeaveAttachment::class);
    }
}
