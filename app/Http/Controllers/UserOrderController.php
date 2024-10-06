<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use OpenApi\Attributes as OA;

class UserOrderController extends Controller implements HasMiddleware
{
    public static function middleware()
    {
        return [new Middleware('auth:sanctum')];
    }


    #[OA\Get(
        path: "/api/my-orders",
        operationId: "myOrders",
        tags: ['Orders'],
        security: [
            [
                'sanctum' => [],
            ]
        ],
        description: "Returns orders made by the authenticated user"
    )]
    #[OA\Response(
        response: 200,
        description: "Successful"
    )]
    #[OA\Response(
        response: 401,
        description: "Unauthenticated"
    )]
    public function __invoke(Request $request)
    {
        return $request->user()->load('orders.products');
    }
}
