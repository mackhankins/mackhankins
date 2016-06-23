<?php


namespace MH\Http\Controllers\Admin;

use MH\Http\Controllers\Controller;
use MH\Repositories\PostRepositoryInterface;

/**
 * Class PostController
 * @package MH\Http\Controllers\Admin
 */
class PostController extends Controller
{

    protected $posts;

    /**
     * PostController constructor.
     * @param PostRepositoryInterface $posts
     */
    public function __construct(PostRepositoryInterface $posts)
    {
        $this->post = $posts;
    }

    public function index()
    {

    }
}
