<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    const STATUS_PRESENT     = 'present';
    const STATUS_ALPA        = 'alpa';

    protected $table = 'attendances';
    
    protected $fillable = ['schedule_id', 'person_id', 'user_id', 'status', 'description', 'is_validated', 'admin_id'];

    public function schedule()
    {
        return $this->belongsTo(Schedule::class);
    }

    public function person()
    {
        return $this->belongsTo(Person::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
