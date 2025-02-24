<?php

namespace App\Http\Controllers;

use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ServiceController extends Controller
{
  public function index()
  {
    $service = Service::all();
    return response()->json($service);
  }

  public function store(Request $request)
  {
    $validator = Validator::make($request->all(), [
      'name' => 'required|string|max:255',
      'name_en' => 'required|string|max:255',
      'description' => 'required|string|max:255',
      'description_en' => 'required|string|max:255',
      'image' =>  'required|image|mimes:jpeg,png,jpg,avif,webp|max:5048'
    ]);

    if ($validator->fails()) {
      return response()->json($validator->errors());
    }
    $image = $request->file('image');
    $imageName = time() . '.' . $image->getClientOriginalName();
    $path = Storage::putFileAs('public/services', $image, $imageName);
    $imageUrl = Storage::url($path);

    $service = Service::create([
      'name' => $request->name,
      'description' => $request->description,
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
      /*'name_en' => 'required|string|max:255',*/
      'description' => 'required|string|max:255',
      /*'description_en' => 'required|string|max:255',*/
      'image' => 'nullable|image|mimes:jpeg,png,jpg,avif,webp|max:5048', // 'nullable' para permitir la edición sin cambiar la imagen
    ]);

    if ($validator->fails()) {
      return response()->json($validator->errors(), 422);
    }

    $service->name = $request->name;
    $service->description = $request->description;

    if ($request->hasFile('image')) {
      // Elimina la imagen anterior si existe
      if ($service->image) {
        Storage::delete(str_replace(Storage::url(''), '', $service->image));
      }

      $image = $request->file('image');
      $imageName = time() . '.' . $image->getClientOriginalExtension();
      $path = Storage::putFileAs('public/services', $image, $imageName);
      $imageUrl = Storage::url($path);
      $service->image = $imageUrl;
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
    if ($service->image) {
      Storage::delete(str_replace(Storage::url(''), '', $service->image));
    }

    $service->delete();

    return response()->json(['message' => 'Servicio eliminado con éxito', 'service' => $service], 200);
  }
  public function show ($id, $lang) {
    if ($lang == 'en') {
      $colummns = [
        'name_en',
        'description_en',
        'url_image'
      ];
    }
    else {
      $colummns = [
        'name',
        'description',
        'url_image'
      ];
    }
    $service = Service::find($id)->get($colummns);
    if (!$service) {
      return response()->json([
        'message' => 'El servicio no existe'
      ]);
    }
    return response()->json([
      'service' => $service
    ]);
  }

  public function paginateServices ($limit, $page) {
    $services = Service::paginate($limit, ['*'],'page',$page);
    $response = [
      'services' => $services->items(),
      'currentPage' => $services->currentPage(),
      'totalPages' => $services->lastPage()
    ];
    return response()->json($response, 200);
  }
}
