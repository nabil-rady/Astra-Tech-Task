<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\ResponseFactory;
use Symfony\Component\HttpFoundation\Response;

class JsonResponseMiddleware
{
    /**
     * @var ResponseFactory
     */
    protected $responseFactory;

    /**
     * JsonResponseMiddleware constructor.
     */
    public function __construct(ResponseFactory $responseFactory)
    {
        $this->responseFactory = $responseFactory;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $request->headers->set('Accept', 'application/json');

        $response = $next($request);

        if (!$response instanceof JsonResponse) {
            $response = $this->responseFactory->json(
                $response->getContent(),
                $response->getStatusCode(),
                $response->headers->all()
            );
        }

        return $response;
    }
}
