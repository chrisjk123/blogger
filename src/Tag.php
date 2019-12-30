<?php

namespace Chriscreate\Blog;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    protected $table = 'tags';

    protected $primaryKey = 'id';

    public $guarded = [];

    public $timestamps = true;

    public function posts()
    {
        return $this->morphedByMany(Post::class, 'taggable');
    }
}
