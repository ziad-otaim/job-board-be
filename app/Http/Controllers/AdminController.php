<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Job;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    // Method to view dashboard (admin statistics)
    public function dashboard()
    {
        $totalUsers = User::count();
        $totalJobs = Job::count();
        $pendingJobs = Job::where('status', 'pending')->count();

        return response()->json([
            'totalUsers' => $totalUsers,
            'totalJobs' => $totalJobs,
            'pendingJobs' => $pendingJobs,
        ]);
    }

    // Method to get all users
    public function getUsers()
    {
        $users = User::all();
        return response()->json($users);
    }

    // Method to update a user's information
    public function updateUser(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $user->update($request->all());
        return response()->json(['message' => 'User updated successfully']);
    }

    // Method to delete a user
    public function deleteUser($id)
    {
        User::destroy($id);
        return response()->json(['message' => 'User deleted successfully']);
    }

    // Method to get all jobs
    public function getJobs()
    {
        $jobs = Job::where('status', 'pending')->get();
        return response()->json($jobs);
    }

    // Method to approve a job
    public function approveJob($id)
    {
        $job = Job::findOrFail($id);
        $job->status = 'approved';
        $job->save();

        return response()->json(['message' => 'Job approved successfully']);
    }

    // Method to delete a job
    public function deleteJob($id)
    {
        Job::destroy($id);
        return response()->json(['message' => 'Job deleted successfully']);
    }
}
