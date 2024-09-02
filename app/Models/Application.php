<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Application extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'job_id', 'resume', 'status'];

    /**
     * Get the job that owns the application.
     */
    public function job()
    {
        return $this->belongsTo(Job::class);
    }

    /**
     * Get the user who applied for the job.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
