<?php

namespace MH\Repositories\Eloquent;

use MH\Post;
use MH\Repositories\PostRepositoryInterface;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\App;

class PostRepository extends AbstractRepository implements PostRepositoryInterface {

    public function __construct(Post $post)
    {
        $this->model = $post;
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
        return $this->model->where('type', '=', 'post')->where('status', '=', 'published')->orderBy('created_at', 'desc')->paginate(intval($number));
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

    public function datatables()
    {
        $posts = $this->model->leftJoin(
            'users',
            'blog_posts.user_id', '=', 'users.id'
        )
            ->select(
                array('blog_posts.id',
                    'blog_posts.title',
                    'users.username as author',
                    'blog_posts.status',
                    'blog_posts.created_at',
                    'blog_posts.updated_at',
                    'blog_posts.slug'
                )
            );

        return Datatables::of($posts)
            ->edit_column('title', '<a href="{{ action(\'App\Modules\Blog\Controllers\Pub\BlogPostController@post\', [ \'slug\' => $slug] ) }}" target="_blank">{{$title}}</a>')
            ->remove_column('slug')
            ->add_column('Action(s)', '<ul class="list-inline" role="menu">
			<li>
			<a href="{{ action(\'App\Modules\Blog\Controllers\Admin\BlogPostController@edit\', array(\'id\' => $id)) }}"><i class="fa fa-edit" title="Edit"></i></a>
			</li>
			<li>
			<a href="#" class="delete_toggle" rel="{{$id}}" title="Delete"><i class="fa fa-trash-o"></i></a>
			</li>
			</ul>')
            ->make();
    }

    public function store(array $data)
    {
        $post = $this->getNew();
        $post->title = $data['title'];
        $post->body = $data['body'];
        $post->user_id = $data['user_id'];
        $post->status = (!empty($data['published']) ? 'published' : 'draft');
        $post->save();
        if (!empty($data['categories']))
        {
            $post->categories()->sync($data['categories']);
        }
        $post->saveTags($data['tags']);
        $post->saveSeo($data['seo_title'], $data['seo_description'], $data['seo_keywords']);
    }

    public function update($id, array $data)
    {
        $post = $this->findById($id);
        $post->title = $data['title'];
        $post->slug = $data['slug'];
        $post->body = $data['body'];
        $post->status = (!empty($data['published']) ? 'published' : 'draft');
        $post->save();
        if (!empty($data['categories']))
        {
            $post->categories()->sync($data['categories']);
        }
        $post->saveTags($data['tags']);
        $post->saveSeo($data['seo_title'], $data['seo_description'], $data['seo_keywords']);
    }

}
