<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LeaveBalanceTransaction extends Model {
    protected $fillable = ['leave_balance_id', 'leave_request_id', 'actor_id', 'delta', 'type', 'note'];
}
