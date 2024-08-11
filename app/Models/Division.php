<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Division extends Model
{
    use HasFactory;
    
    protected $fillable = ['name'];
    protected $hidden = ['created_at', 'updated_at'];
    protected $keyType = 'uuid'; 
    public $incrementing = false; 

    public function employees()
    {
        return $this->hasMany(Employee::class, 'division_id');
    }
}
