<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AuditLog extends Model
{
    protected $fillable=['actor_id','action','subject_type','subject_id','details'];
    protected function casts(): array {return ['details'=>'array'];}
    public static function record(string $action, Model $subject, array $details=[]): void
    {
        self::create(['actor_id'=>auth()->id(),'action'=>$action,'subject_type'=>$subject->getMorphClass(),
            'subject_id'=>$subject->getKey(),'details'=>$details]);
    }
}
