<?php

namespace App\Http\Middleware;

use Closure;

class SnakeCaseAttributes
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $request->replace($this->snake_keys($request->all()));

        return $next($request);
    }

    /**
     * Convert keys to snake case
     *
     * @param $array
     * @param string $delimiter
     * @return array
     */
    private function snake_keys($array, $delimiter = '_')
    {
        $result = [];
        foreach ($array as $key => $value) {
            if (is_array($value)) {
                $value = snake_keys($value, $delimiter);
            }
            $result[snake_case($key, $delimiter)] = $value;
        }
        return $result;
    }
}