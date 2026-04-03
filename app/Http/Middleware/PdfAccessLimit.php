<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PdfAccessLimit
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();
        if (!$user) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $key = 'pdf_access_' . $user->id;
        $accessCount = \Cache::get($key, 0);

        // Limit to 10 accesses per hour
        if ($accessCount >= 10) {
            return response()->json(['error' => 'Too many PDF accesses. Please try again later.'], 429);
        }

        \Cache::put($key, $accessCount + 1, now()->addHour());

        return $next($request);
    }
}
