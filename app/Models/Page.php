<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Class Page
 *
 * @property int $id
 * @property string $title
 * @property string $url
 * @property string|null $content
 * @property string|null $seo_description
 * @property string|null $seo_keywords
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $published_at
 * @property string|null $cover_image
 * @method static \Illuminate\Database\Eloquent\Builder|Page newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Page newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Page query()
 * @method static \Illuminate\Database\Eloquent\Builder|Page whereContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Page whereCoverImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Page whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Page whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Page wherePublishedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Page whereSeoDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Page whereSeoKeywords($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Page whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Page whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Page whereUrl($value)
 * @mixin Model
 */
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

    public function getPageOrFail(): self
    {
        // Si la page n'est pas publiée.
        if(strtotime($this->published_at) > time() || is_null($this->published_at)) {
            throw new NotFoundHttpException();
        }

        return $this;
    }
}
