<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\User;
use App\DTO\CommentDTO;
use App\Traits\ApiResponses;
use Illuminate\Http\Request;
use App\Services\PostService;
use App\Http\Resources\CommentResource;
use App\Http\Requests\Post\CommentRequest;
use Symfony\Component\HttpFoundation\Response;

class CommentController extends Controller
{
    use ApiResponses;
    public function __construct(
        public readonly PostService $postService
    ) {
    }
    public function store(CommentRequest $request, int $post, int $user): Response
    {
        $validatedData = $request->validated();

        $user = User::find($user);
        
        if (!$user) {
            return $this->notFoundApiResponse(
                'Invalid User'
            );
        }
        
        $post = Post::find($post);

        if (!$post) {
            return $this->notFoundApiResponse(
                'Post not found'
            );
        }
        
        if ($post->commentByUser($user->id)) {
            return $this->errorApiResponse('You have already commented on this post.', Response::HTTP_CONFLICT);
        }
        
        $data = CommentDTO::fromArray($validatedData);

        $commentCreated = $this->postService->storeComment($data, $post, $user);

        if ($commentCreated) {
            return $this->createdApiResponse(new CommentResource($commentCreated));
        }
        return $this->errorApiResponse(
            'We are currently unable to handle your request, please try again',
            Response::HTTP_SERVICE_UNAVAILABLE
        );
    }
}