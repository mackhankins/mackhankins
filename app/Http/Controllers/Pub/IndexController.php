<?php


namespace MH\Http\Controllers\Pub;

use MH\Http\Controllers\Controller;
use MH\Repositories\PostRepositoryInterface;

/**
 * Class IndexController
 * @package MH\Http\Controllers\Pub
 */
class IndexController extends Controller
{

    protected $redirectTo = '/dashboard';

    /**
     * IndexController constructor.
     * @param PostRepositoryInterface $post
     */
    public function __construct(PostRepositoryInterface $post)
    {
        $this->post = $post;
    }

    /**
     * Show the application welcome screen to the user.
     *
     * @return Response
     */
    public function index()
    {
        $posts = $this->post->limit(9)->where('status', '=', 'published')->get();

        return view('pub.index')->with(compact('posts'));
    }
}
