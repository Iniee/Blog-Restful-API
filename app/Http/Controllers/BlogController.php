<?php

namespace App\Http\Controllers;

use App\DTO\BlogDTO;
use App\Models\Blog;
use App\Models\User;
use App\Traits\ApiResponses;
use Illuminate\Http\Request;
use App\Services\BlogService;
use App\Http\Resources\BlogResource;
use App\Http\Requests\Blog\StoreRequest;
use App\Http\Requests\Blog\UpdateRequest;
use Symfony\Component\HttpFoundation\Response;

class BlogController extends Controller
{
    use ApiResponses;

    public function __construct(
        public readonly BlogService $blogService,
    ) {
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $blog = Blog::all();
        if ($blog) {
            return $this->okayApiResponse(BlogResource::collection($blog));
        }
        return $this->errorApiResponse(
            'We are currently unable to handle your request, please try again',
            Response::HTTP_SERVICE_UNAVAILABLE
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRequest $request, int $user): Response
    {
        $user = User::find($user);
        if(!$user) {
            return $this->notFoundApiResponse(
                'Invalid User'
            );
        }
        $validatedData = $request->validated();

        $data = BlogDTO::fromArray($validatedData);

        $blogCreated = $this->blogService->createBlog($data, $user);

        if ($blogCreated) {
            return $this->createdApiResponse(new BlogResource($blogCreated));
        }
        return $this->errorApiResponse(
            'We are currently unable to handle your request, please try again',
            Response::HTTP_SERVICE_UNAVAILABLE
        );
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $blog = Blog::find($id);

        if (!$blog) {
            return $this->notFoundApiResponse(
                'Blog not found'
            );
        }
        if ($blog) {
            return $this->okayApiResponse(new BlogResource($blog));
        }
        return $this->errorApiResponse(
            'We are currently unable to handle your request, please try again',
            Response::HTTP_SERVICE_UNAVAILABLE
        );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRequest $request, int $id, int $user): Response
    {
        $data = $request->validated();
        
        $user = User::find($user);
        if(!$user) {
            return $this->notFoundApiResponse(
                'Invalid User'
            );
        }
        
        $result = $this->blogService->updateBlog($id, $data, $user->id);

        if ($result === null) {
            return $this->notFoundApiResponse('Blog not found');
        }

        if ($result === false) {
            return $this->unauthorizedApiResponse();
        }

        return $this->okayApiResponse(new BlogResource($result));
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id, int $user): Response
    {
        $user = User::find($user);
        if(!$user) {
            return $this->notFoundApiResponse(
                'Invalid User'
            );
        }
        $result = $this->blogService->destroyBlog($id, $user->id);

        if ($result === false) {
            return $this->notFoundApiResponse('Blog not found');
        }

        if ($result === null) {
            return $this->unauthorizedApiResponse();
        }

        return $this->successNoDataApiResponse("Blog was deleted successfully");
    }
}