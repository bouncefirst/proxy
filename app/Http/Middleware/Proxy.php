<?php

namespace App\Http\Middleware;

use Closure;
use GuzzleHttp\Client;

class Proxy
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
        $domain = env('APP_DOMAIN');
        $path = $request->getPathInfo();
        $method = $request->getMethod();
        $content = $request->getContent();
        $contentType = $request->headers->get('Content-Type');

        $client = new Client([
            'base_uri' => $domain,
        ]);

        $response = $client->request($method, $domain . $path, [
            'body' => $content,
            'headers' => [
                'Content-Type' => $contentType,
            ],
        ]);

        return response()->json($response->getBody()->getContents());
    }
}
