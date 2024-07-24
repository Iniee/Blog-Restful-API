<?php

namespace App\Services;

use App\DTO\BlogDTO;
use App\Models\Blog;
use App\Models\User;


class BlogService
{
    public function createBlog(BlogDTO $data, User $user):Blog
    {
       $blog = Blog::query()
        ->create([
            'name' => $data->name,
            'description' => $data->description,
            'user_id' => $user->id,
        ]);

        return $blog;
    }

    public function updateBlog(int $id, array $data, int $user): ?Blog
    {
        $blog = Blog::find($id);

        if (!$blog) {
            return null;
        }

        if ($blog->user_id != $user) {
            return false; 
        }

        $updateData = array_filter([
            'name' => $data['name'] ?? null,
            'description' => $data['description'] ?? null,
        ]);

        if ($blog->update($updateData)) {
            return $blog; 
        }

        return false;
    }

    public function destroyBlog(int $id, int $user): bool
    {
        $blog = Blog::find($id);

        if (!$blog) {
            return false; 
        }

        if ($blog->user_id != $user) {
            return null; //
        }

        return $blog->delete();
    }
}