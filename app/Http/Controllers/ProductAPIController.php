<?php

namespace App\Http\Controllers;

use App\Http\Middleware\AdminMiddleware;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use OpenApi\Attributes as OA;

class ProductAPIController extends Controller implements HasMiddleware
{
    public static function middleware()
    {
        return [new Middleware('auth:sanctum', only: ['store', 'update', 'destroy']), new Middleware(AdminMiddleware::class, only: ['store', 'update', 'destroy'])];
    }


    #[OA\Get(
        path: "/api/product",
        operationId: "getProducts",
        tags: ['Products'],
        description: "Returns all available products"
    )]
    #[OA\Response(
        response: 200,
        description: "Successful"
    )]
    public function index()
    {
        return Product::all();
    }

    #[OA\Post(
        path: "/api/product",
        operationId: "createProduct",
        tags: ['Products'],
        description: "Creates a new product and returns it, needs admin authorization",
        security: [
            [
                'sanctum' => [],
            ]
        ]
    )]
    #[OA\RequestBody(
        required: true,
        content: [
            'application/json' => new OA\MediaType(
                mediaType: "application/json",
                schema: new OA\Schema(
                    properties: [
                        new OA\Property(
                            property: "title",
                            type: "string",
                            example: "iPhone"
                        ),
                        new OA\Property(
                            property: "image",
                            type: "string",
                            example: "http://localhost:8000/404.png"
                        ),
                        new OA\Property(
                            property: "description",
                            type: "string",
                            example: "This is a nice product"
                        ),
                        new OA\Property(
                            property: "price",
                            type: "float",
                            example: 15.7
                        ),
                        new OA\Property(
                            property: "quantity",
                            type: "integer",
                            example: 2
                        ),
                        new OA\Property(
                            property: "category_id",
                            type: "integer",
                            description: "Id of category that the product belongs to",
                            example: 1
                        )
                    ],
                    required: ["title", "image", "description", "price", "quantity", "category_id"]
                )
            )
        ]
    )]
    #[OA\Response(response: 201, description: "Successfully created category")]
    #[OA\Response(response: 422, description: "Bad request")]
    #[OA\Response(response: 401, description: "Unauthenticated")]
    #[OA\Response(
        response: 403,
        description: "Unauthorized for non admins"
    )]
    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|min:3|max:255',
            'description' => 'required|string|min:10|max:255',
            'image' => 'required|url',
            'price' => 'required|numeric|gt:0',
            'quantity' => 'required|integer|gte:0',
            'category_id' => 'required|integer|exists:categories,id'
        ]);

        $product = Product::create($data);

        return $product;
    }

    #[OA\Get(
        path: "/api/product/{id}",
        operationId: "getProduct",
        tags: ['Products'],
        description: "Returns product with given id"
    )]
    #[OA\Parameter(
        name: "id",
        in: "path",
        required: true,
        description: "ID of the product that needs to be fetched",
        schema: new OA\Schema(
            type: "integer"
        ),
    )]
    #[OA\Response(response: 200, description: "Successful")]
    #[OA\Response(response: 404, description: "Product not found")]
    public function show(Product $product)
    {
        return $product;
    }


    #[OA\Put(
        path: "/api/product/{id}",
        operationId: "updateProduct",
        tags: ['Products'],
        description: "Updates a product and returns it, needs admin authorization",
        security: [
            [
                'sanctum' => [],
            ]
        ]
    )]
    #[OA\RequestBody(
        required: true,
        content: [
            'application/json' => new OA\MediaType(
                mediaType: "application/json",
                schema: new OA\Schema(
                    properties: [
                        new OA\Property(
                            property: "title",
                            type: "string",
                            example: "iPhone"
                        ),
                        new OA\Property(
                            property: "image",
                            type: "string",
                            example: "http://localhost:8000/404.png"
                        ),
                        new OA\Property(
                            property: "description",
                            type: "string",
                            example: "This is a nice product"
                        ),
                        new OA\Property(
                            property: "price",
                            type: "float",
                            example: 15.7
                        ),
                        new OA\Property(
                            property: "quantity",
                            type: "integer",
                            example: 2
                        ),
                        new OA\Property(
                            property: "category_id",
                            type: "integer",
                            description: "Id of category that the product belongs to",
                            example: 1
                        )
                    ],
                )
            )
        ]
    )]
    #[OA\Parameter(
        name: "id",
        in: "path",
        required: true,
        description: "ID of the product that needs to be updated",
        schema: new OA\Schema(
            type: "integer"
        ),
    )]
    #[OA\Response(
        response: 200,
        description: "Successful"
    )]
    #[OA\Response(
        response: 404,
        description: "Product not found"
    )]
    #[OA\Response(
        response: 401,
        description: "Unauthenticated"
    )]
    #[OA\Response(
        response: 422,
        description: "Bad request"
    )]
    #[OA\Response(
        response: 403,
        description: "Unauthorized for non admins"
    )]
    public function update(Request $request, Product $product)
    {
        $data = $request->validate([
            'title' => 'string|min:3|max:255',
            'description' => 'string|min:10|max:255',
            'image' => 'url',
            'price' => 'numeric|gt:0',
            'quantity' => 'integer|gte:0',
            'category_id' => 'integer|exists:categories,id'
        ]);

        $product->update($data);

        return $product;
    }


    #[OA\Delete(
        path: "/api/product/{id}",
        operationId: "deleteProduct",
        tags: ['Products'],
        description: "Deletes a product, needs admin authorization",
        security: [
            [
                'sanctum' => [],
            ]
        ]
    )]
    #[OA\Parameter(
        name: "id",
        in: "path",
        required: true,
        description: "ID of the product that needs to be deleted",
        schema: new OA\Schema(
            type: "integer"
        ),
    )]
    #[OA\Response(
        response: 204,
        description: "Successful"
    )]
    #[OA\Response(
        response: 404,
        description: "Product not found"
    )]
    #[OA\Response(
        response: 401,
        description: "Unauthenticated"
    )]
    #[OA\Response(
        response: 403,
        description: "Unauthorized for non admins"
    )]
    public function destroy(Product $product)
    {
        $product->delete();

        return response()->json([], 204);
    }
}
