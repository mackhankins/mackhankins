<?php


namespace MH\Http\Controllers\Pub;

use MH\Http\Controllers\Controller;
use MH\Repositories\PostRepositoryInterface;

use Meta;
use Breadcrumbs;

class BlogController extends Controller
{

    public function __construct(PostRepositoryInterface $post)
    {
        $this->post = $post;
    }

    public function index()
    {
        $posts = $this->post->paginatePosts(5);
        $meta = ['title' => 'Blog'];
        $breadcrumbs = Breadcrumbs::render('blog');

        return view('pub.blog')->with(compact('posts', 'breadcrumbs', 'meta'));
    }

    public function single($slug)
    {
        $post = $this->post->findBySlug($slug);
        $meta = [
            'title' => $post->title,
            'description' => $post->excerpt,
            'image' => '/images/small/'.$post->featuredimage,
        ];
        $breadcrumbs = Breadcrumbs::render('post', $post);

        return view('pub.post')->with(compact('post', 'breadcrumbs', 'meta'));
    }
}
