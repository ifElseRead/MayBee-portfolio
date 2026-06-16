<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\PageView;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TrackPageViews
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        return $next($request);
    }

    /**
     * Handle tasks after the response has been sent to the browser.
     */
    public function terminate(Request $request, Response $response): void
    {
        // Only log successful GET requests (HTTP 200 OK)
        if (! $request->isMethod('GET') || $response->getStatusCode() !== 200) {
            return;
        }

        // Filter out common bots and crawlers
        $userAgent = strtolower($request->userAgent() ?? '');
        if (preg_match('/bot|crawl|slurp|spider|mediapartners|lighthouse|headless/i', $userAgent)) {
            return;
        }

        $targetRoutes = ['home', 'contact', 'posts.show'];
        $routeName = $request->route()?->getName();

        if (in_array($routeName, $targetRoutes, true)) {
            PageView::create([
                'user_id' => $request->user()?->id,
                'ip_address' => $request->ip(),
                'url' => $request->fullUrl(),
                'route_name' => $routeName,
            ]);
        }
    }
}
