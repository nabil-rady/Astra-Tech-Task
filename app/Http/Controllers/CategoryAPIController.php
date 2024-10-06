<?php

namespace App\Http\Controllers;

use App\Http\Middleware\AdminMiddleware;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use OpenApi\Attributes as OA;

class CategoryAPIController extends Controller implements HasMiddleware
{
    public static function middleware()
    {
        return [new Middleware('auth:sanctum', only: ['store', 'update', 'destroy']), new Middleware(AdminMiddleware::class, only: ['store', 'update', 'destroy'])];
    }


    #[OA\Get(
        path: "/api/category",
        operationId: "getCategories",
        tags: ['Categories'],
        description: "Returns all available categories"
    )]
    #[OA\Response(
        response: 200,
        description: "Successful"
    )]
    public function index()
    {
        return Category::all();
    }


    #[OA\Post(
        path: "/api/category",
        operationId: "createCategory",
        description: "Creates a new category and returns it, needs admin authorization",
        tags: ['Categories'],
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
                            example: "Tech"
                        ),
                        new OA\Property(
                            property: "image",
                            type: "string",
                            example: "/404.png"
                        )
                    ],
                    required: ["title", "image"]
                )
            )
        ]
    )]
    #[OA\Response(response: 201, description: "Successfully created category")]
    #[OA\Response(response: 422, description: "Bad request")]
    #[OA\Response(response: 401, description: "Unauthorized")]
    #[OA\Response(
        response: 403,
        description: "Unauthorized for non admins"
    )]
    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|min:3|max:255|unique:categories,title',
            'image' => 'required|url',
        ]);

        $category = Category::create($data);

        return response()->json($category, 201);
    }



    #[OA\Get(
        path: "/api/category/{id}",
        operationId: "getCategory",
        tags: ['Categories'],
        description: "Returns category with given id"
    )]
    #[OA\Parameter(
        name: "id",
        in: "path",
        required: true,
        description: "ID of the category that needs to be fetched",
        schema: new OA\Schema(
            type: "integer"
        ),
    )]
    #[OA\Response(response: 200, description: "Successful")]
    #[OA\Response(response: 404, description: "Category not found")]
    public function show(Category $category)
    {
        return $category;
    }


    #[OA\Put(
        path: "/api/category/{id}",
        operationId: "updateCategory",
        tags: ['Categories'],
        description: "Updates a category and returns it, needs admin authorization",
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
                            example: "Tech"
                        ),
                        new OA\Property(
                            property: "image",
                            type: "string",
                            example: "http://localhost:8000/404.png"
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
        description: "ID of the category that needs to be updated",
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
        description: "Category not found"
    )]
    #[OA\Response(
        response: 422,
        description: "Bad request"
    )]
    #[OA\Response(
        response: 401,
        description: "Unauthenticated"
    )]
    #[OA\Response(
        response: 403,
        description: "Unauthorized for non admins"
    )]
    public function update(Request $request, Category $category)
    {
        $data = $request->validate([
            'title' => 'string|min:3|max:255|unique:categories,title',
            'image' => 'url',
        ]);

        $category->update($data);

        return $category;
    }

    #[OA\Delete(
        path: "/api/category/{id}",
        operationId: "deleteCategory",
        tags: ['Categories'],
        description: "Deletes a category, needs admin authorization",
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
        description: "ID of the category that needs to be deleted",
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
        description: "Category not found"
    )]
    #[OA\Response(
        response: 401,
        description: "Unauthenticated"
    )]
    #[OA\Response(
        response: 403,
        description: "Unauthorized for non admins"
    )]
    public function destroy(Category $category)
    {
        $category->delete();

        return response()->json([], 204);
    }
}
