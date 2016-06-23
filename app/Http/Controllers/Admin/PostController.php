<?php


namespace MH\Http\Controllers\Admin;

use MH\Http\Controllers\Controller;
use MH\Repositories\PostRepositoryInterface;

class PostController extends Controller
{

    protected $posts;

    public function __construct(PostRepositoryInterface $posts)
    {
        $this->post = $posts;
    }

    public function index()
    {

    }
}
