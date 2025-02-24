<?php

namespace App\Http\Controllers;

use App\Mail\MessageMailable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class MailController extends Controller
{
  public function sendReservation(Request $request) {
    $validator = Validator::make($request->all(), [
      'nombres' => 'required|string',
      'apellidos' => 'required|string',
      'email' => 'required|string|email',
      'celular' => 'required|string',
      'fecha' => 'required|date',
      'hora' => 'required|string',
      'distrito_origen' => 'required|string',
      'distrito_destino' => 'required|string',
    ]);
    if ($validator->fails()) {
      return response()->json($validator->errors(), 404);
    }
    $dataValidated = $validator->validated();
    Mail::to('juancajas1905@gmail.com')->send(new MessageMailable(
      $dataValidated['nombres'],
      $dataValidated['apellidos'],
      $dataValidated['email'],
      $dataValidated['celular'],
      $dataValidated['fecha'],
      $dataValidated['hora'],
      $dataValidated['distrito_origen'],
      $dataValidated['distrito_destino']
    ));
  }
}
