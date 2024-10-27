<?php

namespace App\Http\Controllers;

use App\Models\alumno;
use App\Models\alumno_lote;
use App\Models\configuracion;
use App\Models\lote;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class mensajeria_controller extends Controller
{
    public function send_mensaje(Request $request)
    {
        try {

            // crear lote
            $lote_defecto = configuracion::where('id', 1)->first();

            $lote = lote::where("lote_id", $lote_defecto->lote)->first();

            if (!$lote) {
                $lote = new lote();
                $lote->numero = $lote_defecto->lote;
                $lote->save();
            }

            // buscar alumno si exite
            $alumno = alumno::where('nombres',  $request->input('alumno')[1])->where("contacto", $request->input('alumno')[3])->first();

            if (!$alumno) {
                $alumno = new alumno();
                $alumno->nombres = $request->input('alumno')[1];
                $alumno->dni = $request->input('alumno')[0];
                $alumno->contacto = $request->input('alumno')[3];
                $alumno->carrera = $request->input('alumno')[2];
                $alumno->save();
            }

            // buscar si tiene en el lote
            $alumno_lote = alumno_lote::where('alumno_id', $alumno->alumno_id)->where("lote_id", $lote->lote_id)->first();

            if ($alumno_lote) {
                return response()->json([
                    'message' => 'este alumno ya se encuentra en este lote',
                    'error' => '',
                    'success' => false,
                    'data' => $alumno_lote,
                ]);
            } else {
                // enviar mensaje
                $alumno_lote = new alumno_lote();
                $alumno_lote->alumno_id = $alumno->alumno_id;
                $alumno_lote->lote_id = $lote->lote_id;


                if ($alumno_lote->save()) {
                    $response = Http::withHeaders([
                        'Accept' => 'application/json',
                        'Content-Type' => 'application/json'
                    ])->post('http://localhost:3000/send_mensaje', [
                        'phone' => $request->input('alumno')[3]
                    ]);

                    return $response->json();
                }

                return response()->json([
                    'message' => 'este alumno no se encontro el lote',
                    'error' => '',
                    'success' => true,
                    'data' => $alumno_lote
                ]);
            }
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            return response()->json([
                'message' => 'error del servidor',
                'error' => $th->getMessage(),
                'success' => false,
                'data' => '',
            ]);
        }
    }
}
