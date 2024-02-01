<?php

namespace Intop\Article\Models;

use Dcat\Admin\Traits\HasDateTimeFormatter;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property string|array $value
 */
class Article extends Model
{

    use HasDateTimeFormatter;

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }
}
