<?php

namespace App\Http\Controllers;

use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ServiceController extends Controller
{
  public function index()
  {
    $service = Service::all();
    return response()->json(['services' => $service]);
  }

  public function store(Request $request)
  {
    $validator = Validator::make($request->all(), [
      'name' => 'required|string|',
      'name_en' => 'required|string|',
      'description' => 'required|string|',
      'description_en' => 'required|string|',
      'image' =>  'required|image|mimes:jpeg,png,jpg,avif,webp|max:5048'
    ]);

    if ($validator->fails()) {
      return response()->json($validator->errors(), 422);
    }
    $image = $request->file('image');
    $imageName = $image->getClientOriginalName();
    $name_file = str_replace(" ", "_", $imageName);
    $imageUrl = date('His') . '-' . $name_file;
    $image->move(public_path('servicios/'), $imageUrl);

    $service = Service::create([
      'name' => $request->name,
      'name_en' => $request->name_en,
      'description' => $request->description,
      'description_en' => $request->description_en,
      'url_image' => $imageUrl
    ]);

    return response()->json([
      'message' => 'Servicio creado con éxito',
      'service' => $service,
    ], 200);
  }

  public function update(Request $request, $id)
  {
    $service = Service::find($id);

    if (!$service) {
      return response()->json(['message' => 'Servicio no encontrado'], 404);
    }

    $validator = Validator::make($request->all(), [
      'name' => 'required|string|max:255',
      'name_en' => 'required|string|max:255',
      'description' => 'required|string',
      'description_en' => 'required|string',
      'image' => 'nullable|image|mimes:jpeg,png,jpg,avif,webp|max:5048',
    ]);

    if ($validator->fails()) {
      return response()->json($validator->errors(), 422);
    }

    $service->name = $request->input('name');
    $service->description = $request->input('description');

    if ($request->hasFile('image')) {
      $file = $request->file('image');
      $filename = $file->getClientOriginalName();
      $name_File = str_replace(" ", "_", $filename);

      $imageUrl = date('His') . '-' . $name_File;
      $file->move(public_path('servicios/'), $imageUrl);

      if ($service->url_image && file_exists(public_path('servicios/' . $service->url_image))) {
        unlink(public_path('servicios/' . $service->url_image));
      }
      /*
      if ($service->url_image) {
        Storage::delete(str_replace(Storage::url(''), '', $service->url_image));
      }

      $image = $request->file('image');
      $imageName = time() . '.' . $image->getClientOriginalExtension();
      $path = Storage::putFileAs('public/services', $image, $imageName);
      $imageUrl = asset(Storage::url($path));
      */
      $service->url_image = $imageUrl;
    }

    $service->save();

    return response()->json([
      'message' => 'Servicio actualizado con éxito',
      'service' => $service,
    ], 200);
  }

  public function destroy($id)
  {
    $service = Service::find($id);

    if (!$service) {
      return response()->json(['message' => 'Servicio no encontrado'], 404);
    }

    // Elimina la imagen asociada si existe
    if ($service->url_image && file_exists(public_path('servicios/' . $service->url_image))) {
      unlink(public_path('servicios/' . $service->url_image));
    }

    $service->delete();

    return response()->json(['message' => 'Servicio eliminado con éxito', 'service' => $service], 200);
  }
  public function show($id)
  {
    $service = Service::find($id);
    if (!$service) {
      return response()->json([
        'message' => 'El servicio no existe'
      ]);
    }
    return response()->json([
      'service' => $service
    ]);
  }

  public function paginateServices($limit, $page)
  {
    $services = Service::paginate($limit, ['*'], 'page', $page);
    $response = [
      'services' => $services->items(),
      'currentPage' => $services->currentPage(),
      'totalPages' => $services->lastPage()
    ];
    return response()->json($response, 200);
  }
}
