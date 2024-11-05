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
        $admin = Auth::user() ? Auth::user()->admin : false;
        $jobs = Auth::user() ? ($admin ? Job::with("user.vehicle")->get() : Auth::user()->jobs()->get()) : [];
        $users = $admin ? User::has("vehicle")->where("admin", false)->get() : [];
        $undiscardeds = $admin ? Job::with('user')->where("status", 4)->where(function ($query) {
            $query->where('discarded', '!=', true)
                ->orWhereNull('discarded');
        })->get() : [];

        $counter = $admin ? count(Job::all()->where("status", 4)->whereNull('discarded')) : 0;

        return view('index', ["jobs" => $jobs, "users" => $users, "admin" => $admin, "undiscardeds" => $undiscardeds, "counter" => $counter]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        if (!Auth::user()->admin) {
            return redirect()->route('index');
        }
        return view()->make('jobs.job_form', ["admin" => true, "users" => User::has('vehicle')->where("admin", false)->get()]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if (!Auth::user()->admin) {
            return redirect()->route('index');
        }

        $request->validate([
            'addressee_name' => 'string|required',
            'addressee_phone' => 'string|required',
            'start_address' => 'string|required',
            'end_address' => 'string|required',
            'user_id' => 'integer|required'
        ]);

        $job = new Job;
        $job->addressee_name = $request->addressee_name;
        $job->addressee_phone = $request->addressee_phone;
        $job->start_address = $request->start_address;
        $job->end_address = $request->end_address;
        $job->status = 0;

        if ($request->user_id != -1) {
            $job->user_id = $request->user_id;
            $job->status = 1;
        }

        $job->save();
        return redirect()->route('index');
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
        $job = Job::with('user')->where("id", $id)->first();
        if (!$job) {
            abort(404);
        }
        return view()->make('jobs.job_form', ["job" => $job, "admin" => true, "users" => User::has('vehicle')->where("admin", false)]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $job = Job::all()->where("id", $id)->first();
        if (!$job) {
            abort(404);
        }
        if (!Auth::user()->admin) {
            return redirect()->route('index');
        }

        $request->validate([
            'addressee_name' => 'string|required',
            'addressee_phone' => 'string|required',
            'start_address' => 'string|required',
            'end_address' => 'string|required',
            'user_id' => 'integer'
        ]);

        $job->addressee_name = $request->addressee_name;
        $job->addressee_phone = $request->addressee_phone;
        $job->start_address = $request->start_address;
        $job->end_address = $request->end_address;

        if (isset($request->user_id) && $request->user_id != -1) {
            $job->user_id = $request->user_id;
            $job->status = 1;
        }


        $job->update();
        return redirect()->route('index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $job = Job::all()->where("id", $id)->first();
        if (!$job) {
            abort(404);
        }
        if (!Auth::user()->admin) {
            return redirect()->route('index');
        }

        $job->delete();

        return redirect()->route('index');
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
     * Update the status of the specified resource in storage.
     */
    public function progress(Request $request, string $id)
    {
        $job = Job::all()->where("id", $id)->first();
        if (!$job) {
            abort(404);
        }

        $request->validate([
            'status' => 'integer|required|min:2|max:4',
            'message' => 'string|nullable'
        ]);

        $job->status = $request["status"];
        if ($request["status"] != 4) {
            $job->message = null;
        } else {
            $job->message = $request["message"];
            $job->discarded = false;
        }
        $job->update();
        return redirect()->route('index');
    }

    /**
     * Get all jobs that match a status.
     */
    public function status(Request $request)
    {
        $request->validate([
            'status' => 'integer|required|min:0|max:4',
        ]);

        $status = $request["status"];

        $admin = Auth::user() ? Auth::user()->admin : false;
        $jobs = Auth::user() ? ($admin ? Job::with("user.vehicle")->where("status", $status)->get() : Auth::user()->jobs()->where("status", $status)->get()) : [];
        $users = $admin ? User::has("vehicle")->where("admin", false)->get() : [];
        $undiscardeds = $admin ? Job::with('user')->where("status", 4)->where(function ($query) {
            $query->where('discarded', '!=', true)
                ->orWhereNull('discarded');
        })->get() : [];

        $counter = $admin ? count(Job::all()->where("status", 4)->whereNull('discarded')) : 0;

        return view('index', ["jobs" => $jobs, "users" => $users, "admin" => $admin, "undiscardeds" => $undiscardeds, "counter" => $counter]);
    }

    /**
     * Dismisses the job's message.
     */
    public function dismiss(string $id)
    {
        $job = Job::all()->where("id", $id)->first();
        if (!$job) {
            abort(404);
        }

        $job->discarded = true;

        $job->update();

        return count(Job::all()->where("status", 4)->where("discarded", false));
    }

    /**
     * Reads all jobs' message.
     */
    public function read()
    {
        foreach (Job::all()->where("status", 4)->where("discarded", null) as $job) {
            $job->discarded = false;
            $job->update();
        }
    }
}
