<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CsvData extends Model
{
    use HasFactory;
    protected $fillable = [
        'branch_id', 'first_name', 'last_name', 'email', 'phone', 'gender'
    ];
}
