<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Services\SlugService;
use Cviebrock\EloquentSluggable\Sluggable;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;

class Blog extends Model implements TranslatableContract
{
    use Translatable;
    use Sluggable;

    protected $table = 'blog';
    public $timestamps = false;
    protected $dateFormat = 'U';
    protected $guarded = ['id'];

    public $translatedAttributes = ['title', 'description', 'meta_description', 'content'];

    /**
     * Return the sluggable configuration array for this model.
     *
     * @return array
     */
    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'title'
            ]
        ];
    }

    public static function makeSlug($title)
    {
        return SlugService::createSlug(self::class, 'slug', $title);
    }

    public function category()
    {
        return $this->belongsTo('App\Models\BlogCategory', 'category_id', 'id');
    }

    public function author()
    {
        return $this->belongsTo('App\User', 'author_id', 'id');
    }

    public function comments()
    {
        return $this->hasMany('App\Models\Comment', 'blog_id', 'id');
    }

    public function getUrl()
    {
        return '/blog/' . $this->slug;
    }

    public function getTitleAttribute()
    {
        return getTranslateAttributeValue($this, 'title');
    }

    public function getDescriptionAttribute()
    {
        return getTranslateAttributeValue($this, 'description');
    }

    public function getMetaDescriptionAttribute()
    {
        return getTranslateAttributeValue($this, 'meta_description');
    }

    public function getContentAttribute()
    {
        return getTranslateAttributeValue($this, 'content');
    }
}
