<?php
// app/Http/Middleware/LoggerMiddleware.php
// app/Http/Middleware/LoggerMiddleware.php

namespace App\Http\Middleware;

use Closure;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\Logger;

class LoggerMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     *
     * @return mixed
     */
    public function handle(Request $request, Closure $next) // Correct type-hinting
    {
        $response = $next($request);

        $contents = json_decode($response->getContent(), true, 512);

        $headers = $request->header();

        $dt = Carbon::now();
        $data = [
            'path'         => $request->getPathInfo(),
            'method'       => $request->getMethod(),
            'ip'           => $request->ip(),
            'http_version' => $_SERVER['SERVER_PROTOCOL'],
            'timestamp'    => $dt->toDateTimeString(),
            'headers'      => json_encode([
                'user-agent' => $headers['user-agent'][0] ?? null, // Access first element of the header
                'referer'    => $headers['referer'][0] ?? null,
                'origin'     => $headers['origin'][0] ?? null,
            ]),
        ];

        // Extract the model name from the request path or any other logic
        $data['model'] = $this->getModelFromPath($request->getPathInfo());

        if ($request->user()) {
            $data['user_id'] = $request->user()->id;
        }

        if (count($request->all()) > 0) {
            $hiddenKeys = ['password'];
            $data['request'] = json_encode($request->except($hiddenKeys));
        }

        if (!empty($contents['message'])) {
            $data['response']['message'] = $contents['message'];
        }

        if (!empty($contents['errors'])) {
            $data['response']['errors'] = json_encode($contents['errors']);
        }

        if (!empty($contents['result'])) {
            $data['response']['result'] = json_encode($contents['result']);
        }

        Logger::create($data);

        $message = str_replace('/', '_', trim($request->getPathInfo(), '/'));
        Log::info($message, $data);

        return $response;
    }

    /**
     * Extract the model name from the request path.
     *
     * @param string $path
     * @return string|null
     */
    private function getModelFromPath($path)
    {
//        // General logic to determine model name from the path
//        if (preg_match('/\/api\/(\w+)/', $path, $matches)) {
//            return ucfirst($matches[1]);
//        }

        return null;
    }
}
