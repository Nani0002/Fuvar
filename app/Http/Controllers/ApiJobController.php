<?php

namespace App\Http\Controllers;

use App\Models\Job;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ApiJobController extends Controller
{
    /**
     * Get all jobs.
     */
    public function index()
    {
        return response()->json(Job::all());
    }

    /**
     * Get all jobs of a user.
     */
    public function show(string $id)
    {
        return response()->json(Job::all()->where("user_id", $id));
    }

    /**
     * Create a job.
     */
    public function store(Request $request)
    {
        if(!Auth::user()->admin){
            return response()->json(["error" => "Only administrators can post jobs."]);
        }

        $errors = [];

        if (!isset($request["addressee_name"]) || $request["addressee_name"] == "") {
            $errors[] = "Név megadása kötelező.";
        }
        if (!isset($request["addressee_phone"]) || $request["addressee_phone"] == "") {
            $errors[] = "Telefonszám megadása kötelező.";
        }
        if (!isset($request["start_address"]) || $request["start_address"] == "") {
            $errors[] = "Kiindulási cím megadása kötelező.";
        }
        if (!isset($request["end_address"]) || $request["end_address"] == "") {
            $errors[] = "Érkezési cím megadása kötelező.";
        }

        if (count($errors) > 0) {
            return response()->json(["errors" => $errors]);
        }

        $job = new Job;
        $job->addressee_name = $request["addressee_name"];
        $job->addressee_phone = $request["addressee_phone"];
        $job->start_address = $request["start_address"];
        $job->end_address = $request["end_address"];
        $job->status = 1;

        $job->save();

        return response()->json(["message" => "Munka sikeresen hozzáadva."]);
    }

    /**
     * Update a job.
     */
    public function update(Request $request, string $id)
    {
        if(!Auth::user()->admin){
            return response()->json(["error" => "Only administrators can modify jobs."]);
        }

        $job = Job::all()->where("id", $id)->first();
        if (isset($request["addressee_name"]) && $request["addressee_name"] != "") {
            $job->addressee_name = $request["addressee_name"];
        }
        if (isset($request["addressee_phone"]) && $request["addressee_phone"] != "") {
            $job->addressee_phone = $request["addressee_phone"];
        }
        if (isset($request["start_address"]) && $request["start_address"] != "") {
            $job->start_address = $request["addressee_name"];
        }
        if (isset($request["end_address"]) && $request["end_address"] != "") {
            $job->end_address = $request["end_address"];
        }

        if(isset($request["user_id"]) && !is_null($job->user_id)){
            return response()->json(["errors" => "Már kiosztott munkának a futárját nem lehet módosítani."]);
        }
        if(isset($request["user_id"]) && is_null($job->user_id) && count(User::all()->where("admin", false)->where("id", $request["user_id"])) == 0){
            return response()->json(["errors" => "Nem futár munkatárs."]);
        }
        if (isset($request["user_id"]) && is_null($job->user_id) && count(User::all()->where("admin", false)->where("id", $request["user_id"])) != 0) {
            $job->user_id = $request["user_id"];
            $job->status = 1;
        }



        $job->update();

        return response()->json(["message" => "Munka sikeresen módosítva."]);
    }
}
