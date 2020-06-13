<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
    protected $fillable = [
        'title',
        'url',
        'content',
        'seo_description',
        'seo_keywords',
        'published_at',
        'cover_image',
    
    ];
    
    
    protected $dates = [
        'created_at',
        'updated_at',
        'published_at',
    
    ];
    
    protected $appends = ['resource_url'];

    /* ************************ ACCESSOR ************************* */

    public function getResourceUrlAttribute()
    {
        return url('/admin/pages/'.$this->getKey());
    }
}
