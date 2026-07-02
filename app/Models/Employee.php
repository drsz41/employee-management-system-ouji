<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    protected $fillable = [
        'employee_id',
        'name',
        'email',
        'position',
        'department',
        'status',
        'join_date',
    ];
}
