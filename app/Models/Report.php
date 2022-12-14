<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'user_id', 'date', 'in_time', 'out_time', 'total_hours', 'seconds'];
}
