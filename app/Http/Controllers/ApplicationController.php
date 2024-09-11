<?php

namespace App\Http\Controllers;

use App\Models\Application;
use App\Models\Job;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ApplicationController extends Controller
{
    public function index(Request $request)
    {
        $job_id = $request->input('job_id');

        $query = Application::query()
            ->select('applications.*', 'users.name as applicant_name', 'users.email as applicant_email', 
                     'jobs.title as job_title', 'jobs.location as job_location', 'jobs.salary as job_salary')
            ->join('users', 'applications.user_id', '=', 'users.id')
            ->join('jobs', 'applications.job_id', '=', 'jobs.id');

        if ($job_id) {
            $query->where('applications.job_id', 'like', '%' . $job_id . '%');
        }

        $applications = $query->get();

        return response()->json($applications);
    }

    public function store(Request $request, Job $job)
    {
        $request->validate([
            'resume' => 'required|file|mimes:pdf,doc,docx|max:2048', // Validate file types and size
        ]);

        // Handle file upload
        $resumePath = $request->file('resume')->store('resumes', 'public');
        $resumeUrl = Storage::url($resumePath); // Generate the URL with domain name

        // Convert the path to a full URL
        $resumeFullUrl = env('APP_URL') . $resumeUrl;

        $application = $job->applications()->create([
            'user_id' => $request->user()->id,
            'resume' => $resumeFullUrl,
        ]);

        return response()->json([
            'message' => 'Application submitted successfully',
            'application' => $application,
        ]);
    }


    public function accept($id)
{
    $application = Application::findOrFail($id);
    $application->status = 'accepted';
    $application->save();

    return response()->json(['message' => 'Application accepted'], 200);
}

public function reject($id)
{
    $application = Application::findOrFail($id);
    $application->status = 'rejected';
    $application->save();

    return response()->json(['message' => 'Application rejected'], 200);
}

    /**
     * Cancel an application for a job.
     */
    public function destroy(Request $request, Job $job)
    {
        $application = Application::where('job_id', $job->id)
            ->where('user_id', $request->user()->id)
            ->first();
    
        if ($application) {
            // Get the relative path of the resume by removing the storage URL part
            $resumePath = str_replace('/storage', '', parse_url($application->resume, PHP_URL_PATH));
    
            // Check if the resume file exists and delete it
            if (Storage::disk('public')->exists($resumePath)) {
                Storage::disk('public')->delete($resumePath);
            }
    
            // Delete the application record
            $application->delete();
    
            return response()->json([
                'message' => 'Application cancelled successfully'
            ], 200);
        }
    
        return response()->json([
            'message' => 'Application not found'
        ], 404);
    }
}
