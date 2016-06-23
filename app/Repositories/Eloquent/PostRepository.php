<?php

namespace MH\Repositories\Eloquent;

use MH\Post;
use MH\Repositories\FileRepositoryInterface;
use MH\Repositories\PostRepositoryInterface;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\App;

/**
 * Class PostRepository
 * @package MH\Repositories\Eloquent
 */
class PostRepository extends AbstractRepository implements PostRepositoryInterface
{

    /**
     * PostRepository constructor.
     * @param Post $post
     * @param FileRepositoryInterface $file
     */
    public function __construct(Post $post, FileRepositoryInterface $file)
    {
        $this->model = $post;
        $this->file = $file;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function getAll()
    {
        return $this->model->all();
    }

    /**
     * @param $number
     * @return mixed
     */
    public function limit($number)
    {
        return $this->model->orderBy('created_at', 'desc')->take($number);
    }

    /**
     * @param $number
     * @return mixed
     */
    public function paginate($number)
    {
        return $this->model->where('type', '=', 'post')->orderBy('created_at', 'desc')->paginate(intval($number));
    }

    /**
     * @param $number
     * @return mixed
     */
    public function paginatePosts($number)
    {
        return $this->model->where('type', '=', 'post')->where('status', '=', 'published')->orderBy('created_at', 'desc')->paginate(intval($number));
    }

    /**
     * @param $number
     * @return mixed
     */
    public function paginateLinks($number)
    {
        return $this->model->where('type', '=', 'link')->where('status', '=', 'published')->orderBy('created_at', 'desc')->paginate(intval($number));
    }

    /**
     * @param $id
     * @return mixed
     */
    public function findById($id)
    {
        return $this->model->find($id);
    }

    /**
     * @param $slug
     * @return mixed
     */
    public function findBySlug($slug)
    {
        try {
            return $this->model->where('slug', '=', $slug)->firstOrFail();
        } catch (ModelNotFoundException $e) {
            return App::abort(404);
        }
    }

    /**
     * @param $query
     * @return mixed
     */
    public function searchPosts($query)
    {
        return $this->model->where('type', '=', 'post')->where('status', '=', 'published')->search($query);
    }

    /**
     * @param array $data
     * @return mixed|void
     */
    public function store(array $data)
    {
        $post = $this->getNew();
        $post->title = $data['title'];
        $post->excerpt = strip_tags($data['excerpt']);
        $post->pcontent = $data['content'];
        $post->type = 'post';
        $post->user_id = $data['user_id'];
        $post->status = $data['status'];
        if ($data['featured']->isValid()) {
            $filename = $this->file->uploadImage($data['featured']);
            $post->featuredimage = $filename;
        }
        $post->save();
    }

    /**
     * @param $id
     * @param array $data
     * @return mixed|void
     */
    public function update($id, array $data)
    {
        $post = $this->findById($id);
        $post->title = $data['title'];
        $post->slug = $data['slug'];
        $post->excerpt = strip_tags($data['excerpt']);
        $post->pcontent = $data['content'];
        $post->type = 'post';
        $post->status = $data['status'];
        if (!empty($data['featured']) and $data['featured']->isValid()) {
            $filename = $this->file->uploadImage($data['featured']);
            $post->featuredimage = $filename;
        }
        $post->save();
    }

    /**
     * @param $id
     * @return mixed|void
     */
    public function delete($id)
    {
        $post = $this->findById($id);
        $post->delete();
    }
}
