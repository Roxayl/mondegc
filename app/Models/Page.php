<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

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

    public function getPageOrFail() {

        // Si la page n'est pas publiée.
        if(strtotime($this->published_at) > time() || is_null($this->published_at)) {
            throw new NotFoundHttpException();
        }

        return $this;

    }

}
