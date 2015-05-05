<?php


namespace MH\Http\Controllers\Pub;


use MH\Http\Controllers\Controller;
use MH\Repositories\PostRepositoryInterface;

use Meta;
use Breadcrumbs;

class BlogController extends Controller {

    public function __construct(PostRepositoryInterface $post)
    {
        $this->post = $post;
    }

    public function index()
    {
        $posts = $this->post->paginatePosts(5);
        Meta::meta('title', 'Blog');
        Meta::meta('description', 'a simple blog about my interest');
        Meta::meta('image', asset('images/home-logo.png'));
        $breadcrumbs = Breadcrumbs::render('blog');

        return view('pub.blog')->with(compact('posts', 'breadcrumbs'));
    }

    public function single($slug)
    {
        $post = $this->post->findBySlug($slug);
        Meta::meta('title', $post->title);
        Meta::meta('description', $post->excerpt);
        Meta::meta('image', asset('images/home-logo.png'));
        $breadcrumbs = Breadcrumbs::render('post', $post);

        return view('pub.post')->with(compact('post', 'breadcrumbs'));
    }
}