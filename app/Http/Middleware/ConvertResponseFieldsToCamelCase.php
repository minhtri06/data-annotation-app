<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use PhpParser\Node\Stmt\TryCatch;
use Symfony\Component\HttpFoundation\Response;

class ConvertResponseFieldsToCamelCase
{
    protected function convertArrayKeysToCamelCase(array $array)
    {
        $replace = [];
        foreach ($array as $key => $value) {
            $replace[Str::camel($key)] = is_array($value) ?
                $this->convertArrayKeysToCamelCase($value) : $value;
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
        $response = $next($request);
        $content = $response->getContent();

        try {
            $json = json_decode($content, true);
            if ($json) {
                $response->setContent(
                    json_encode($this->convertArrayKeysToCamelCase($json))
                );
            }
        } catch (\Throwable $th) {
            return 'Error in ConvertResponseFieldsToCamelCase';
        }

        return $response;
    }
}
