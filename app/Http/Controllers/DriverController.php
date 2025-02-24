<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class DriverController extends Controller
{
    public function index()
    {
        $conductores = User::where('role', 'conductor')->get();
        return response()->json($conductores);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|unique:users',
            'password' => 'required|string|min:8',
            'photo_driver' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5048',
            'photo_vehicle' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5048',
            'ruc' => 'required|string|unique:users',
            'phone' => 'required|string',
            'address' => 'required|string',
            'dni' => 'required|string',
            'date_afiliate' => 'required|date',
            'account_bank' => 'required|string'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $data = $request->except('password', 'photo_driver', 'photo_vehicle');
        $data['password'] = Hash::make($request->password);
        $data['role'] = 'conductor'; // Asegura que el rol sea conductor

        if ($request->hasFile('photo_driver')) {
            $data['photo_driver'] = $request->file('photo_driver')->store('conductores/photos', 'public');
        }

        if ($request->hasFile('photo_vehicle')) {
            $data['photo_vehicle'] = $request->file('photo_vehicle')->store('conductores/vehicles', 'public');
        }

        $conductor = User::create($data);

        return response()->json([
            'driver' => $conductor
        ], 200);
    }

    public function show($id)
    {
        $conductor = User::where('role', 'conductor')->find($id);
        if (!$conductor) {
            return response()->json(['message' => 'Conductor no encontrado'], 404);
        }
        return response()->json([
            'driver' => $conductor
        ], 200);
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|unique:users,email,' . $id,
            'password' => 'nullable|string|min:8',
            'photo_driver' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5048',
            'photo_vehicle' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5048',
            'ruc' => 'nullable|string|unique:users,ruc,' . $id,
            'phone' => 'nullable|string',
            'address' => 'nullable|string',
            'dni' => 'nullable|string',
            'date_afiliate' => 'nullable|date',
            'account_bank' => 'nullable|string'
            
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $conductor = User::where('role', 'conductor')->find($id);
        if (!$conductor) {
            return response()->json(['message' => 'Conductor no encontrado'], 404);
        }

        $data = $request->except('password', 'photo_driver', 'photo_vehicle');
        if ($request->password) {
            $data['password'] = Hash::make($request->password);
        }

        if ($request->hasFile('photo_driver')) {
            if ($conductor->photo_driver) {
                Storage::delete($conductor->photo_driver);
            }
            $data['photo_driver'] = $request->file('photo_driver')->store('conductores/photos', 'public');
        }

        if ($request->hasFile('photo_vehicle')) {
            if ($conductor->photo_vehicle) {
                Storage::delete($conductor->photo_vehicle);
            }
            $data['photo_vehicle'] = $request->file('photo_vehicle')->store('conductores/vehicles', 'public');
        }

        $conductor->update($data);

        return response()->json([
            'driver' => $conductor
        ], 200);
    }

    public function destroy($id)
    {
        $conductor = User::where('role', 'conductor')->find($id);
        if (!$conductor) {
            return response()->json(['message' => 'Conductor no encontrado'], 404);
        }
        $conductor->delete();
        return response()->json(['message' => 'Conductor eliminado', 'driver' => $conductor], 200);
    }
    public function paginateConductores($limit = 10, $page = 1)
    {
        $conductores = User::where('role', 'conductor')
            ->paginate($limit, ['*'], 'page', $page);

        $response = [
            'drivers' => $conductores->items(),
            'currentPage' => $conductores->currentPage(),
            'totalPages' => $conductores->lastPage()
        ];

        return response()->json($response, 200);
    }
}
