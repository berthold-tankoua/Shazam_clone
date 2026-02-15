<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;

class ShazamController extends Controller
{
    public function handleWebhook(Request $request)
    {

        if (!$request->hasFile('audio')) {
            return response()->json(['error' => 'Pas de fichier reçu'], 400);
        }

        $file = $request->file('audio');

        $response = Http::attach(
            'audio',
            file_get_contents($file->getRealPath()),
            $file->getClientOriginalName()
        )->post('https://n8n.srv1043341.hstgr.cloud/webhook-test/1b3602f7-37ee-4ff4-815e-cf0a055b2ce1');

        $json = $response->json();

        // Ensuite, extraire les champs spécifiques
        $data = [];
        $data['status'] = $json['status'] ?? null;           // exemple: 'success' ou autre
        $data['artist'] = $json['artist'] ?? null;
        $data['title'] = $json['title'] ?? null;
        $data['album'] = $json['album'] ?? null;
        $data['release_date'] = $json['release_date'] ?? null;
        $data['song_link'] = $json['song_link'] ?? null;
        $data['img'] = $json['img'] ?? null;
        $data['spotify_link'] = $json['spotify_link'] ?? null;
        $data['apple_link'] = $json['apple_link'] ?? null;
        $data['preview'] = $json['preview'] ?? null;

        Session::put('result', $data);
        return response()->json([
            'status' => $json['status'] ?? null,
        ]);
    }
}
