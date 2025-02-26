<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
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
      $image = $request->file('photo_driver');
      $imageName = $image->getClientOriginalExtension();
      $name_File = str_replace(" ", "_", $imageName);
      $imageUrl = date('His') . '.' . $name_File;
      // $conductor->photo_driver = $imageUrl;
      $image->move(public_path('conductores/'), $imageUrl);
      $data['photo_driver'] = $imageUrl;
    }

    if ($request->hasFile('photo_vehicle')) {
      /*
      $image = $request->file('photo_vehicle');
      $imageName = time() . '.' . $image->getClientOriginalName();
      $path = Storage::putFileAs('public/vehiculos', $image, $imageName);
      $imageUrl = asset(Storage::url($path));
      */
      $image = $request->file('photo_vehicle');
      $imageName = $image->getClientOriginalExtension();
      $name_File = str_replace(" ", "_", $imageName);
      $imageUrl = date('His') . '.' . $name_File;
      // $conductor->photo_vehicle = $imageUrl;
      $image->move(public_path('vehiculos/'), $imageUrl);
      $data['photo_vehicle'] = $imageUrl;
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
  /*
  public function update(Request $request, $id)
  {
    $validator = Validator::make($request->all(), [
      'name' => 'required|string|max:255',
      'email' => 'required|string|email',
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

      $image1 = $request->file('photo_driver');
      $imageName = $image1->getClientOriginalExtension();
      $name_File = str_replace(" ", "_", $imageName);
      $imageUrl = date('His') . '.' . $name_File;
      $imagePath = public_path('conductores/'. $imageUrl);
      $image1->move(public_path('conductores/'), $imageUrl);
      $conductor->photo_driver = $imageUrl;
      if ($conductor->photo_driver && file_exists(public_path('conductores/' . $conductor->photo_driver))) {
        Log::info($conductor->photo_driver);
        unlink(public_path('conductores/' . $conductor->photo_driver));
      }
      
      $conductor->photo_driver = $imageUrl;
    }

    if ($request->hasFile('photo_vehicle')) {
      $image2 = $request->file('photo_vehicle');
      $imageName = $image2->getClientOriginalExtension();
      $name_File = str_replace(" ", "_", $imageName);
      $imageUrl = date('His') . '.' . $name_File;
      $conductor->photo_vehicle = $imageUrl;
      $image2->move(public_path('vehiculos/'), $imageUrl);

      if ($conductor->photo_vehicle && file_exists(public_path('vehiculos/' . $conductor->photo_vehicle))) {
        unlink(public_path('vehiculos/' . $conductor->photo_vehicle));
      }
      $conductor->photo_vehicle = $imageUrl;
    }
    $conductor->save();
    // $conductor->update();

    return response()->json([
      'driver' => $conductor
    ], 200);
  }
  */
  public function update(Request $request, $id)
  {
    $validator = Validator::make($request->all(), [
      'name' => 'required|string|max:255',
      'email' => 'required|string|email',
      'password' => 'nullable|string|min:8',
      'photo_driver' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5048',
      'photo_vehicle' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5048',
      'ruc' => 'nullable|string|unique:users,ruc,' . $id,
      'phone' => 'nullable|string',
      'address' => 'nullable|string',
      'dni' => 'nullable|string',
      'date_afiliate' => 'nullable|date',
      'account_bank' => 'nullable|string',
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
      $image1 = $request->file('photo_driver');
      $imageName = $image1->getClientOriginalExtension();
      $name_File = str_replace(" ", "_", $imageName);
      $imageUrl = date('His') . '.' . $name_File;
      $imagePath = public_path('conductores/' . $imageUrl);
      $image1->move(public_path('conductores/'), $imageUrl);

      // Eliminar la imagen anterior si existe
      if ($conductor->photo_driver && file_exists(public_path('conductores/' . $conductor->photo_driver))) {
        Log::info('Eliminando imagen anterior: ' . $conductor->photo_driver);
        unlink(public_path('conductores/' . $conductor->photo_driver));
      }

      $data['photo_driver'] = $imageUrl;
    }

    if ($request->hasFile('photo_vehicle')) {
      $image2 = $request->file('photo_vehicle');
      $imageName = $image2->getClientOriginalExtension();
      $name_File = str_replace(" ", "_", $imageName);
      $imageUrl = date('His') . '.' . $name_File;
      $imagePath = public_path('vehiculos/' . $imageUrl);
      $image2->move(public_path('vehiculos/'), $imageUrl);

      // Eliminar la imagen anterior si existe
      if ($conductor->photo_vehicle && file_exists(public_path('vehiculos/' . $conductor->photo_vehicle))) {
        Log::info('Eliminando imagen anterior: ' . $conductor->photo_vehicle);
        unlink(public_path('vehiculos/' . $conductor->photo_vehicle));
      }

      $data['photo_vehicle'] = $imageUrl;
    }

    $conductor->fill($data); // Usar fill() para asignar los datos al modelo
    $conductor->save();

    return response()->json([
      'driver' => $conductor,
    ], 200);
  }
  public function destroy($id)
  {
    $conductor = User::where('role', 'conductor')->find($id);
    if (!$conductor) {
      return response()->json(['message' => 'Conductor no encontrado'], 404);
    }

    if ($conductor->photo_driver && file_exists(public_path('conductores/' . $conductor->photo_driver))) {
      unlink(public_path('conductores/' . $conductor->photo_driver));
    }
    if ($conductor->photo_vehicle && file_exists(public_path('vehiculos/' . $conductor->photo_vehicle))) {
      unlink(public_path('vehiculos/' . $conductor->photo_vehicle));
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
