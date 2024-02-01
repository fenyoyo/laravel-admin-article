<?php

namespace Intop\Article\Models;

use Dcat\Admin\Models\MenuCache;
use Dcat\Admin\Traits\HasDateTimeFormatter;
use Dcat\Admin\Traits\ModelTree;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Spatie\EloquentSortable\Sortable;

/**
 * @property int $id
 */
class Category extends Model implements Sortable
{
    use HasDateTimeFormatter,
        MenuCache,
        ModelTree {
        allNodes as treeAllNodes;
        ModelTree::boot as treeBoot;
    }

    use HasFactory;


    public function getHasParentIdAttribute(): bool
    {
        return $this->attributes['parent_id'] > 0;
    }

    public static function getCategoryByParentId($id)
    {
        return self::query()->where('parent_id', $id)->get();
    }

}
