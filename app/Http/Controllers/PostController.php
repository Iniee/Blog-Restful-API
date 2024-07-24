<?php

namespace App\Http\Controllers;

use App\DTO\PostDTO;
use App\Models\Blog;
use App\Models\Post;
use App\Models\User;
use App\Traits\ApiResponses;
use App\Services\PostService;
use App\Http\Resources\PostResource;
use App\Http\Requests\Post\StoreRequest;
use App\Http\Resources\ShowPostResource;
use App\Http\Requests\Post\UpdateRequest;
use Symfony\Component\HttpFoundation\Response;

class PostController extends Controller
{
    use ApiResponses;

    public function __construct(
        public readonly PostService $postService
    ) {
    }
    /**
     * Display a listing of the resource.
     */
    public function index($blog)
    {
        $blog = Blog::find($blog);
        if (!$blog) {
            return $this->notFoundApiResponse(
                'Blog not found'
            );
        }
        $post = $blog->posts;
        
        if ($post) {
            return $this->okayApiResponse(PostResource::collection($post));
        }
        return $this->errorApiResponse(
            'We are currently unable to handle your request, please try again',
            Response::HTTP_SERVICE_UNAVAILABLE
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRequest $request, int $blog, int $user): Response
    {
        $validatedData = $request->validated();
        
        $blog = Blog::find($blog);
        $user = User::find($user);

        if (!$blog) {
            return $this->notFoundApiResponse(
                'Blog not found'
            );
        }

        if(!$user) {
            return $this->notFoundApiResponse(
                'Invalid User'
            );
        }
        $data = PostDTO::fromArray($validatedData);

        $postCreated = $this->postService->createPost($data, $blog, $user);

        if ($postCreated) {
            return $this->createdApiResponse(new PostResource($postCreated));
        }
        return $this->errorApiResponse(
            'We are currently unable to handle your request, please try again',
            Response::HTTP_SERVICE_UNAVAILABLE
        );
    }

    /**
     * Display the specified resource.
     */
    public function show(int $post)
    {
        $post = Post::find($post);

        if (!$post) {
            return $this->notFoundApiResponse(
                'Post not found'
            );
        }
        if ($post) {
            return $this->okayApiResponse(new ShowPostResource($post));
        }
        return $this->errorApiResponse(
            'We are currently unable to handle your request, please try again',
            Response::HTTP_SERVICE_UNAVAILABLE
        );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRequest $request, int $post, int $user)
    {
        $data = $request->validated();

        $post = Post::find($post);

        if (!$post) {
            return $this->notFoundApiResponse(
                'Post not found'
            );
        }
        
        $user = User::find($user);

        if (!$user) {
            return $this->notFoundApiResponse(
                'Post not found'
            );
        }

        if ($post->user_id != $user->id) {
            return $this->unauthorizedApiResponse();
        }

        $updateData = [];
        if (isset($data['title'])) {
            $updateData['title'] = $data['title'];
        }
        if (isset($data['content'])) {
            $updateData['content'] = $data['content'];
        }

        // Update the Post
        if ($post->update($updateData)) {
            return $this->okayApiResponse(new PostResource($post));
        }

        return $this->errorApiResponse(
            'We are currently unable to handle your request, please try again',
            Response::HTTP_SERVICE_UNAVAILABLE
        );
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $post, int $user)
    {
        $post = Post::find($post);

        if (!$post) {
            return $this->notFoundApiResponse(
                'Post not found'
            );
        }

        $user = User::find($user);

        if (!$user) {
            return $this->notFoundApiResponse(
                'Post not found'
            );
        }

        if ($post->user_id != $user->id) {
            return $this->unauthorizedApiResponse();
        }

        if ($post->delete()) {
            return $this->successNoDataApiResponse("Post was Deleted Successfully");
        }

        return $this->errorApiResponse(
            'We are currently unable to handle your request, please try again',
            Response::HTTP_SERVICE_UNAVAILABLE
        );
    }
}