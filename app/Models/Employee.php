<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'image',
        'name',
        'phone',
        'division_id',
        'position',
    ];

    protected $hidden = ['created_at', 'updated_at'];

    protected $keyType = 'uuid'; 
    public $incrementing = false; 

    public function division()
    {
        return $this->belongsTo(Division::class, 'division_id');
    }
}
