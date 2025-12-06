<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class PreventBackHistory
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Jika response adalah download file (Excel, PDF, dll), jangan tambahkan header cache pencegah back button
        // karena bisa mengganggu proses download dan menyebabkan error 'Call to undefined method'.
        if ($response instanceof BinaryFileResponse) {
            return $response;
        }

        return $response->withHeaders([
            'Cache-Control' => 'no-cache, no-store, must-revalidate, max-age=0',
            'Pragma' => 'no-cache',
            'Expires' => 'Sat, 01 Jan 2000 00:00:00 GMT',
        ]);
    }
}
