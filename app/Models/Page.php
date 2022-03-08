<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
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
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $published_at
 * @property string|null $cover_image
 * @method static Builder|Page newModelQuery()
 * @method static Builder|Page newQuery()
 * @method static Builder|Page query()
 * @method static Builder|Page whereContent($value)
 * @method static Builder|Page whereCoverImage($value)
 * @method static Builder|Page whereCreatedAt($value)
 * @method static Builder|Page whereId($value)
 * @method static Builder|Page wherePublishedAt($value)
 * @method static Builder|Page whereSeoDescription($value)
 * @method static Builder|Page whereSeoKeywords($value)
 * @method static Builder|Page whereTitle($value)
 * @method static Builder|Page whereUpdatedAt($value)
 * @method static Builder|Page whereUrl($value)
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

    /**
     * @return $this
     */
    public function getPageOrFail(): self
    {
        // Si la page n'est pas publiée.
        if(strtotime($this->published_at) > time() || is_null($this->published_at)) {
            throw new NotFoundHttpException();
        }

        return $this;
    }
}
