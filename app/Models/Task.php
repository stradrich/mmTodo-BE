<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    // Define the table associated with the model
    protected $table = 'tasks';

    // Specify which fields can be mass-assigned
    protected $fillable = ['title', 'description', 'status', 'due_date', 'priority'];

    // Specify casts for specific fields
    protected $casts = [
        'status' => 'string',
        'priority' => 'string',
        'due_date' => 'datetime',
    ];

    // Define a relationship to the user model
    public function user()
    {
        return $this->belongsTo(user::class);
    }
}
