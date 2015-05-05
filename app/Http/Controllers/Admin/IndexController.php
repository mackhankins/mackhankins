<?php


namespace MH\Http\Controllers\Admin;

use Entrust;
use Illuminate\Support\Facades\Request;
use MH\Http\Controllers\Controller;
use MH\Http\Requests\StoreBlogPostRequest;
use MH\Http\Requests\UpdateBlogPostRequest;
use MH\Repositories\FileRepositoryInterface;
use MH\Repositories\PostRepositoryInterface;
use Auth;

class IndexController extends Controller{

    public function __construct(PostRepositoryInterface $posts, FileRepositoryInterface $files)
    {
        $this->post = $posts;
        $this->file = $files;
    }

    public function index()
    {
        $posts = $this->post->paginate('10');
        return view('admin.index')->with(compact('posts'));
    }

    public function create()
    {
        $user = Auth::user();
        $this->file->buildImageJson(storage_path(env('UPLOAD_PATH')),'uploads.json');
        $filesjson = url('app/uploads.json');
        return view('admin.new')->with(compact('user', 'filesjson'));
    }

    public function edit($id)
    {
        $post = $this->post->findById($id);
        $this->file->buildImageJson(storage_path(env('UPLOAD_PATH')),'uploads.json');
        $filesjson = url('app/uploads.json');
        return view('admin.edit')->with(compact('post', 'filesjson'));
    }

    public function store(StoreBlogPostRequest $request)
    {
        $this->post->store(Request::all());
        return redirect()->route('admin.dashboard');
    }

    public function update(UpdateBlogPostRequest $request, $id)
    {
        $this->post->update($id, Request::all());
        return redirect()->route('admin.dashboard');
    }

    public function upload()
    {
        dd(Request::all());
        //$this->file->uploadImage();
    }

}