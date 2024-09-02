<?php

namespace App\Http\Controllers;

use App\Models\Job;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class JobController extends Controller
{
    public function index(Request $request)
    {
        // Fetch query parameters
        $employer_id = $request->input('employer_id');
        $location = $request->input('location');
        $category = $request->input('category');
        $search = $request->input('search');

        // Query the jobs with optional filters
        $query = Job::query();

        if ($employer_id) {
            $query->where('user_id', 'like', '%' . $employer_id . '%');
        }
        
        if ($location) {
            $query->where('location', 'like', '%' . $location . '%');
        }

        if ($category) {
            $query->where('category', 'like', '%' . $category . '%');
        }

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', '%' . $search . '%')
                  ->orWhere('description', 'like', '%' . $search . '%');
            });
        }

        $jobs = $query->get();

        return response()->json($jobs);
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'location' => 'required|string',
            'work_type' => 'required|string',
            'category' => 'required|string',
            'salary' => 'nullable|numeric',
            'deadline' => 'nullable|date',
        ]);

        $job = Job::create([
            'user_id' => Auth::id(),
            'title' => $request->title,
            'description' => $request->description,
            'location' => $request->location,
            'work_type' => $request->work_type,
            'category' => $request->category,
            'salary' => $request->salary,
            'deadline' => $request->deadline,
            'status' => 'open',
        ]);

        return response()->json([
            'message' => 'Job created successfully',
            'job' => $job,
        ]);
    }

    public function show(Request $request, Job $job)
    {
        $isApplied = false;

        if ($request->user()) {
            $isApplied = $job->applications()->where('user_id', $request->user()->id)->exists();
        }

        return response()->json([
            'job' => $job,
            'is_applied' => $isApplied
        ]);
    }

    public function update(Request $request, Job $job)
    {

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'location' => 'nullable|string|max:255',
            'work_type' => 'nullable|string|max:255',
            'salary' => 'nullable|numeric',
            'category' => 'required|string',
            'deadline' => 'nullable|date',
        ]);

        $job->update($request->only(['title', 'description', 'location', 'work_type', 'salary', 'category', 'deadline']));
        return response()->json([
            'message' => 'Job updated successfully',
            'job' => $job,
        ]);
    }

    public function destroy(Job $job)
    {
        $job->delete();

        return response()->json([
            'message' => 'Job deleted successfully',
        ]);
    }
}
