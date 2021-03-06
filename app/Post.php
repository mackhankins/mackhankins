<?php namespace MH;

use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;

/**
 * Class Post
 * @package MH
 */
class Post extends Model
{

    use Sluggable;

    /**
     * Sluggable configuration.
     *
     * @var array
     * @return array
     */
    public function sluggable()
    {
        return [
            'slug' => [
                'source'         => 'title',
                'separator'      => '-',
                'includeTrashed' => true,
            ]
        ];
    }

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
    protected $fillable = ['title', 'slug', 'pcontent', 'user_id', 'featuredimage', 'status', 'imgsrc', 'excerpt'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(\MH\User::class, 'user_id');
    }
}
