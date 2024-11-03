<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Vehicle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VehicleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (!Auth::user()->admin) {
            return redirect()->route('index');
        }
        return view()->make("vehicles.vehicles", ["vehicles" => Vehicle::all(), "admin" => true, "free_users" => User::doesntHave('vehicle')->where('admin', false)->get()]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        if (!Auth::user()->admin) {
            return redirect()->route('index');
        }
        return view()->make('vehicles.vehicle_form', ["admin" => true, "free_users" => User::doesntHave('vehicle')->where('admin', false)->get()]);
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
            "brand" => "string|required",
            "type" => "string|required",
            "registration" => [
                'required',
                'string',
                function ($attribute, $value, $fail) {
                    if (!preg_match('/^(?:[A-Z]{3}-\d{3}|[A-Z]{2}-[A-Z]{2}-\d{3})$/', $value)) {
                        $fail("The $attribute format is invalid.");
                    }
                },
            ],
            "user_id" => "int|nullable",
        ]);

        $vehicle = new Vehicle;
        $vehicle->brand = $request["brand"];
        $vehicle->type = $request["type"];
        $vehicle->registration = $request["registration"];

        if ($request->user_id != -1) {
            $vehicle->user_id = $request->user_id;
        }

        $vehicle->save();

        return redirect()->route('vehicles.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        abort(404);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        if (!Auth::user()->admin) {
            return redirect()->route('index');
        }
        $vehicle = Vehicle::all()->where("id", $id)->first();
        if (!$vehicle) {
            abort(404);
        }
        return view()->make('vehicles.vehicle_form', ["vehicle" => $vehicle, "admin" => true, "free_users" => User::doesntHave('vehicle')->where('admin', false)->get()]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        if (!Auth::user()->admin) {
            return redirect()->route('index');
        }
        $vehicle = Vehicle::all()->where("id", $id)->first();
        if (!$vehicle) {
            abort(404);
        }

        $request->validate([
            "brand" => "string|required",
            "type" => "string|required",
            "registration" => [
                'required',
                'string',
                function ($attribute, $value, $fail) {
                    if (!preg_match('/^(?:[A-Z]{3}-\d{3}|[A-Z]{2}-[A-Z]{2}-\d{3})$/', $value)) {
                        $fail("The $attribute format is invalid.");
                    }
                },
            ],
            "user_id" => "int",
        ]);

        $vehicle->brand = $request["brand"];
        $vehicle->type = $request["type"];
        $vehicle->registration = $request["registration"];

        if (isset($request["user_id"]) && $request->user_id != -1) {
            $vehicle->user_id = $request->user_id;
        }

        $vehicle->update();
        return redirect()->route('vehicles.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        if (!Auth::user()->admin) {
            return redirect()->route('index');
        }
        $vehicle = Vehicle::all()->where("id", $id)->first();
        if (!$vehicle) {
            abort(404);
        }
        if (!is_null($vehicle->user)) {
            abort(403);
        }
        $vehicle->delete();
        return redirect()->route('vehicles.index');
    }

    /**
     * Allocate the vehicle to a user.
     */
    public function allocate(Request $request, string $id)
    {
        if (!Auth::user()->admin) {
            return redirect()->route('index');
        }
        $vehicle = Vehicle::all()->where("id", $id)->first();
        if (!$vehicle) {
            abort(404);
        }

        $request->validate([
            'user_id' => 'integer|required',
        ]);

        if (count(User::all()->where('id', $request["user_id"])->all()) == 0) {
            abort(404);
        }

        $vehicle->user_id = $request["user_id"];
        $vehicle->update();

        return redirect()->route('vehicles.index');
    }
}
