<?php


namespace MH\Http\Controllers\Pub;

use MH\Http\Controllers\Controller;
use MH\Repositories\PostRepositoryInterface;
use Meta;
use Breadcrumbs;

/**
 * Class BlogController
 * @package MH\Http\Controllers\Pub
 */
class BlogController extends Controller
{

    /**
     * @var PostRepositoryInterface
     */
    private $post;

    /**
     * BlogController constructor.
     * @param PostRepositoryInterface $post
     */
    public function __construct(PostRepositoryInterface $post)
    {
        $this->post = $post;
    }

    /**
     * @return $this
     */
    public function index()
    {
        $posts = $this->post->paginatePosts(10);
        $meta = ['title' => 'Blog'];
        $breadcrumbs = Breadcrumbs::render('blog');

        return view('pub.blog')->with(compact('posts', 'breadcrumbs', 'meta'));
    }

    /**
     * @param $slug
     * @return $this
     */
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
