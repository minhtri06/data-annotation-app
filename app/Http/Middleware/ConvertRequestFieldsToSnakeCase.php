<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

class ConvertRequestFieldsToSnakeCase
{
    protected function convertArrayKeysToSnakeCase(array $array)
    {
        $replace = [];
        foreach ($array as $key => $value) {
            $replace[Str::snake($key)] = is_array($value) ?
                $this->convertArrayKeysToSnakeCase($value) : $value;
        }
        return $replace;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $request->replace($this->convertArrayKeysToSnakeCase($request->all()));
        return $next($request);
    }
}
