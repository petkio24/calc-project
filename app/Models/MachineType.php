<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MachineType extends Model
{
    use HasFactory;

    protected $table = 'machine_types';

    protected $fillable = [
        'name',
        'power_range',
        'max_rpm',
        'rigidity_factor'
    ];
}
