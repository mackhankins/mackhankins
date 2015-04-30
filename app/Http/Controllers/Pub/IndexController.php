<?php


namespace MH\Http\Controllers\Pub;


use MH\Http\Controllers\Controller;
use MH\Repositories\PostRepositoryInterface;
use Meta;

class IndexController extends Controller {

    protected $redirectTo = '/dashboard';

    public function __construct(PostRepositoryInterface $post)
    {
        $this->middleware('guest');
        $this->post = $post;
    }

    /**
     * Show the application welcome screen to the user.
     *
     * @return Response
     */
    public function index()
    {
        Meta::meta('title', 'Mack Hankins');
        Meta::meta('description', 'Php Developer');
        Meta::meta('image', asset('images/home-logo.png'));
        $posts = $this->post->limit(10)->where('type', '=', 'post')->where('status', '=', 'published')->get();
        $links = $this->post->limit(6)->where('type', '=', 'link')->where('status', '=', 'published')->get();

        return view('pub.index')->with(compact('posts', 'links'));
    }

}