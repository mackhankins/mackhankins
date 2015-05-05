<?php

namespace MH\Repositories\Eloquent;

use MH\Post;
use MH\Repositories\FileRepositoryInterface;
use MH\Repositories\PostRepositoryInterface;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\App;

class PostRepository extends AbstractRepository implements PostRepositoryInterface {

    public function __construct(Post $post, FileRepositoryInterface $file)
    {
        $this->model = $post;
        $this->file = $file;
    }

    public function getAll()
    {
        return $this->model->all();
    }

    public function limit($number)
    {
        return $this->model->orderBy('created_at', 'desc')->take($number);
    }

    public function paginate($number)
    {
        return $this->model->where('type', '=', 'post')->orderBy('created_at', 'desc')->paginate(intval($number));
    }

    public function paginatePosts($number)
    {
        return $this->model->where('type', '=', 'post')->where('status', '=', 'published')->orderBy('created_at', 'desc')->paginate(intval($number));
    }

    public function paginateLinks($number)
    {
        return $this->model->where('type', '=', 'link')->where('status', '=', 'published')->orderBy('created_at', 'desc')->paginate(intval($number));
    }

    public function findById($id)
    {
        return $this->model->find($id);
    }

    public function findBySlug($slug)
    {
        try
        {
            return $this->model->where('slug', '=', $slug)->firstOrFail();
        } catch (ModelNotFoundException $e)
        {
            return App::abort(404);
        }
    }

    public function searchPosts($query)
    {
        return $this->model->where('type', '=', 'post')->where('status', '=', 'published')->search($query);
    }

    public function store(array $data)
    {
        $post = $this->getNew();
        $post->title = $data['title'];
        $post->excerpt = $data['excerpt'];
        $post->pcontent = $data['content'];
        $post->type = 'post';
        $post->user_id = $data['user_id'];
        $post->status = $data['status'];
        if($data['featured']->isValid())
        {
            $filename = $this->file->uploadImage($data['featured']);
            $post->featuredimage = $filename;
        }
        $post->save();
    }

    public function update($id, array $data)
    {
        $post = $this->findById($id);
        $post->title = $data['title'];
        $post->slug = $data['slug'];
        $post->excerpt = $data['excerpt'];
        $post->pcontent = $data['content'];
        $post->type = 'post';
        $post->status = $data['status'];
        if(!empty($data['featured']) AND $data['featured']->isValid())
        {
            $filename = $this->file->uploadImage($data['featured']);
            $post->featuredimage = $filename;
        }
        $post->save();
    }

}
