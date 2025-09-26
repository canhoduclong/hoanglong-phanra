<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\PostCategory;
use App\Models\Tag;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function index()
    {
        $posts = Post::where('is_published', true)->latest()->paginate(10);
        $categories = PostCategory::all();
        $tags = Tag::all();

        return view('posts.index', compact('posts', 'categories', 'tags'));
    }

    public function show(Post $post)
    {
        $otherPosts = Post::where('is_published', true)->where('id', '!=', $post->id)->latest()->take(5)->get();
        $categories = PostCategory::all();
        $tags = Tag::all();

        return view('posts.show', compact('post', 'otherPosts', 'categories', 'tags'));
    }

    public function category(PostCategory $category)
    {
        $posts = $category->posts()->where('is_published', true)->latest()->paginate(10);
        $categories = PostCategory::all();
        $tags = Tag::all();

        return view('posts.category', compact('posts', 'category', 'categories', 'tags'));
    }
}