<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Job extends Model
{
    use HasFactory;

    protected $fillable = ['user_id','title', 'description', 'location', 'work_type','category','salary','deadline'];


    /**
     * Get the applications for the job.
     */
    public function applications()
    {
        return $this->hasMany(Application::class);
    }
}
