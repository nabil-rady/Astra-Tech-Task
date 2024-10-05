<?php

namespace App\Http\Controllers;

use OpenApi\Attributes as OA;

#[OA\Info(
    version: "1.0.0",
    title: "Astra Tech API Store",
    description: "Swagger OpenAPI description",
    contact: new OA\Contact(
        email: "nabil-rady@outlook.com"
    ),
    license: new OA\License(
        name: "Apache 2.0",
        url: "https://www.apache.org/licenses/LICENSE-2.0.html"
    )
)]
#[OA\Tag(
    name: 'Auth',
    description: 'Operations related to authorization'
)]
#[OA\Tag(
    name: 'Categories',
    description: 'Operations related to categories'
)]
#[OA\Tag(
    name: 'Products',
    description: 'Operations related to products'
)]
#[OA\Tag(
    name: 'Orders',
    description: 'Operations related to orders'
)]
#[OA\SecurityScheme(
    securityScheme: "sanctum",
    type: "http",
    in: "header",
    name: "Authorization",
    scheme: "bearer",
    bearerFormat: "JWT",
    description: "Bearer token for Laravel Sanctum authentication"
)]
abstract class Controller
{
    //
}
