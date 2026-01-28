<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ViesController extends Controller
{
    public function check(Request $request)
    {
        $request->validate([
            'country' => 'required|string|size:2',
            'vat' => 'required|string',
        ]);

        try {
            $response = Http::timeout(10)->get(
                "https://ec.europa.eu/taxation_customs/vies/rest-api/ms/{$request->country}/vat/{$request->vat}"
            );

            return response()->json($response->json());
        } catch (\Throwable $e) {
            return response()->json([
                'valid' => false,
                'error' => 'VIES indisponível',
            ], 503);
        }
    }
}
