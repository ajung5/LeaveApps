<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LeaveAttachment extends Model
{
    protected $fillable=['leave_request_id','path','original_name'];
}
