<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\User;
use App\Traits\ApiResponses;
use Illuminate\Http\Request;
use App\Services\PostService;
use Symfony\Component\HttpFoundation\Response;

class LikeController extends Controller
{
    use ApiResponses;

    public function __construct(
        public readonly PostService $postService
    ) {
    }

    public function store(int $post, int $user): Response
    {
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


        if ($post->likedByUser($user->id)) {
            return $this->errorApiResponse('You have already liked this post.', Response::HTTP_CONFLICT);
        }

        $likeStored = $this->postService->storeLike($post, $user);

        if ($likeStored) {
            return $this->okayApiResponse(message: "Post Liked Succesfully");
        }
        return $this->errorApiResponse(
            'We are currently unable to handle your request, please try again',
            Response::HTTP_SERVICE_UNAVAILABLE
        );
    }
}