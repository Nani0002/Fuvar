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
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        if (!Auth::user()->admin) {
            return redirect()->route('index');
        }
        return view()->make('jobs.job_form', ["admin" => true, "users" => User::all()->where("admin", false)]);
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
        return view()->make('jobs.job_form', ["job" => $job, "admin" => true, "users" => User::all()->where("admin", false)]);
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
            'message' => 'string|nullable'
        ]);

        $job->status = $request["status"];
        if ($request["status"] != 4) {
            $job->message = null;
        } else {
            $job->message = $request["message"];
        }
        $job->update();
        return redirect()->route('index');
    }
}
