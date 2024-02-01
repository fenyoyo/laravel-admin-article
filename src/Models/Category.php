<?php

namespace Intop\Article\Models;

use App\Models\Traits\AssetTrait;
use BlueM\Tree;
use BlueM\Tree\Serializer\HierarchicalTreeJsonSerializer;
use Dcat\Admin\Models\MenuCache;
use Dcat\Admin\Traits\HasDateTimeFormatter;
use Dcat\Admin\Traits\ModelTree;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
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

    use HasFactory, AssetTrait;

    protected $casts = ['fields' => 'array', 'is_menu' => 'boolean'];

    protected $attributes = [
        'id' => 0,
        'url' => '',
    ];

    public static function arrayToArrayTree($data)
    {

        $tree = new Tree(
            $data,
            ['rootId' => 0, 'id' => 'id', 'parent' => 'parent_id']
        );
        $tree->setJsonSerializer(new HierarchicalTreeJsonSerializer);
        return $tree->jsonSerialize();
    }

    public function getHasParentIdAttribute(): bool
    {
        return $this->attributes['parent_id'] > 0;
    }

    public function getUrlAttribute(): string
    {
        if ($this->attributes['url'] != "") {
            return $this->attributes['url'];
        }
        return route('news.show', ['id' => $this->attributes['id']]);
    }

    public function getMobileUrlAttribute(): string
    {
        if ($this->attributes['url'] != "") {
            return '/mobile' . $this->attributes['url'];
        }
        return $this->attributes['url'];
    }

    public static function getCategoryByParentId($id)
    {
        return self::query()->where('parent_id', $id)->get();
    }
}
