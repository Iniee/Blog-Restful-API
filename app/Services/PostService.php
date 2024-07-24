<?php

namespace App\Services;

use App\DTO\PostDTO;
use App\Models\Blog;
use App\Models\Like;
use App\Models\Post;
use App\Models\User;
use App\DTO\CommentDTO;
use App\Models\Comment;

class PostService
{
    public function createPost(PostDTO $data, Blog $blog, User $user): Post
    {
        return Post::query()->create([
            'title' => $data->title,
            'content' => $data->content,
            'blog_id' => $blog->id,
            'user_id' => $user->id,
        ]);
    }

    public function updatePost(int $postId, array $data, int $userId): ?Post
    {
        $post = Post::find($postId);

        if (!$post) {
            return null;
        }

        $user = User::find($userId);
        if (!$user) {
            return null;
        }

        if ($post->user_id != $user->id) {
            return null;
        }

        $updateData = array_filter([
            'title' => $data['title'] ?? null,
            'content' => $data['content'] ?? null,
        ]);

        if ($post->update($updateData)) {
            return $post;
        }

        return null;
    }

    public function destroyPost(int $postId, int $userId): bool
    {
        $post = Post::find($postId);

        if (!$post) {
            return false;
        }

        $user = User::find($userId);
        if (!$user) {
            return false;
        }

        if ($post->user_id != $user->id) {
            return false;
        }

        return $post->delete();
    }

    public function storeLike(Post $post, User $user): ?Like
    {
        $existingLike = Like::where('post_id', $post->id)
                            ->where('liked_by', $user->id)
                            ->first();

        if ($existingLike) {
            return null;
        }

        return Like::query()->create([
            'liked_by' => $user->id,
            'post_id' => $post->id,
        ]);
    }

    public function storeComment(CommentDTO $data, Post $post, User $user): Comment
    {
        return Comment::query()->create([
            'comment_by' => $user->id,
            'post_id' => $post->id,
            'comment' => $data->comment
        ]);
    }
}