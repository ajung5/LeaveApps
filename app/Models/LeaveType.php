<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LeaveType extends Model
{
    protected $fillable=['code','name','count_mode','uses_balance','requires_attachment','is_active'];
    protected function casts(): array { return ['uses_balance'=>'boolean','requires_attachment'=>'boolean','is_active'=>'boolean']; }
}
