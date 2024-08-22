<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    // Define the table associated with the model
    protected $table = 'categories';

    // Specify which fields can be mass-assigned
    protected $fillable = ['name', 'description'];

    // Specify casts for specific fields
    protected $casts = [
        'is_active' => 'boolean',
        'created_at' => 'datetime'
    ];

    // Define a relationship to the user model
    public function tasks()
    {
        return $this->hasMany(Task::class);
    }
}
