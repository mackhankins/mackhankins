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

/**
 * Class IndexController
 * @package MH\Http\Controllers\Admin
 */
class IndexController extends Controller
{

    /**
     * IndexController constructor.
     * @param PostRepositoryInterface $posts
     * @param FileRepositoryInterface $files
     */
    public function __construct(PostRepositoryInterface $posts, FileRepositoryInterface $files)
    {
        $this->post = $posts;
        $this->file = $files;
    }

    /**
     * @return $this
     */
    public function index()
    {
        $posts = $this->post->paginate('10');
        return view('admin.index')->with(compact('posts'));
    }

    /**
     * @return $this
     */
    public function create()
    {
        $user = Auth::user();
        $this->file->buildImageJson(storage_path(env('UPLOAD_PATH')), 'uploads.json');
        $filesjson = url('app/uploads.json');
        return view('admin.new')->with(compact('user', 'filesjson'));
    }

    /**
     * @param $id
     * @return $this
     */
    public function edit($id)
    {
        $post = $this->post->findById($id);
        $this->file->buildImageJson(storage_path(env('UPLOAD_PATH')), 'uploads.json');
        $filesjson = url('app/uploads.json');
        return view('admin.edit')->with(compact('post', 'filesjson'));
    }

    /**
     * @param StoreBlogPostRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(StoreBlogPostRequest $request)
    {
        $this->post->store(Request::all());
        return redirect()->route('admin.dashboard');
    }

    /**
     * @param UpdateBlogPostRequest $request
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(UpdateBlogPostRequest $request, $id)
    {
        $this->post->update($id, Request::all());
        return redirect()->route('admin.dashboard');
    }

    /**
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function delete($id)
    {
        $this->post->delete($id);
        return redirect()->route('admin.dashboard');
    }

    /**
     * @return string
     */
    public function upload()
    {
        if (Request::file('file')->isValid()) {
            $filename = $this->file->uploadImage(Request::file('file'));

            return stripslashes(json_encode(['filelink' => '/images/large/'.$filename]));
        }
    }
}
