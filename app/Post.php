<?php namespace MH;

use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\SluggableInterface;
use Cviebrock\EloquentSluggable\SluggableTrait;

class Post extends Model implements SluggableInterface
{

    use SluggableTrait;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'posts';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['title', 'slug', 'pcontent', 'type', 'user_id', 'featuredimage', 'status', 'extlink', 'commentcount', 'mimetype', 'excerpt'];

    public function user()
    {
        return $this->belongsTo(\MH\User::class, 'user_id');
    }

    protected $sluggable = array(
        'build_from' => 'title',
        'save_to'    => 'slug',
    );
}
