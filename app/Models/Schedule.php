<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    protected $table = 'schedules';

    protected $fillable = ['date', 'day_name', 'is_validated'];

    public function persons()
    {
        return $this->belongsToMany(Person::class, 'schedule_person');
    }


    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }
}
