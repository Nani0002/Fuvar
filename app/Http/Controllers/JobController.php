<?php

namespace App\Http\Controllers;

use App\Models\Job;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class JobController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view()->make('jobs.jobs', ["jobs" => Auth::user()->admin ? Job::all() : Auth::user()->jobs()->get(), "admin" => Auth::user()->admin]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        if (!Auth::user()->admin) {
            return redirect()->route('index');
        }
        return view()->make('jobs.job_form');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $job = Job::all()->where("id", $id)->first();
        if (!$job) {
            abort(404);
        }
        if (!Auth::user()->admin && $job->user_id != Auth::id()) {
            return redirect()->route('index');
        }
        return view()->make('jobs.job', ["job" => $job]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        if (!Auth::user()->admin) {
            return redirect()->route('index');
        }
        $job = Job::all()->where("id", $id)->first();
        if (!$job) {
            abort(404);
        }
        return view()->make('jobs.job_form', ["job" => $job]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    /**
     * Allocate a job to a driver
     */
    public function allocate(Request $request, string $id)
    {
        if (!Auth::user()->admin) {
            return redirect()->route('index');
        }

        $job = Job::all()->where("id", $id)->first();
        if (!$job) {
            abort(404);
        }

        $request->validate([
            'user_id' => 'integer|required',
        ]);

        $user = User::all()->where('id', $request["user_id"])->first();

        $job->user_id = $user->id;
        $job->status = 1;
        $job->update();
        return redirect()->route('index');
    }

    /**
     * Update the specified resource in storage.
     */
    public function progress(Request $request, string $id)
    {
        $job = Job::all()->where("id", $id)->first();
        if (!$job) {
            abort(404);
        }

        $request->validate([
            'status' => 'integer|required|min:2|max:4',
        ]);
        $job->status = $request["status"];
        $job->update();
        return redirect()->route('index');
    }
}
