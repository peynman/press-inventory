<?php

namespace Larapress\Inventory\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Larapress\Profiles\IProfileUser;
use Larapress\Profiles\Models\Group;

/**
 * @property int                  $id
 * @property string               $name
 * @property int                  $flags
 * @property int                  $author_id
 * @property int                  $group_id
 * @property IProfileUser         $author
 * @property Group                $group
 * @property array                $data
 * @property \Carbon\Carbon       $shelved_at
 * @property \Carbon\Carbon       $updated_at
 * @property \Carbon\Carbon       $deleted_at
 */
class InventoryBranch extends Model
{
    use SoftDeletes;

    protected $table = 'inventory_branch';

    protected $fillable = [
        'author_id',
        'group_id',
        'data',
        'name',
        'flags',
    ];

    public $casts = [
        'data' => 'array',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function author()
    {
        return $this->belongsTo(config('larapress.crud.user.model'), 'author_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function group()
    {
        return $this->belongsTo(config('larapress.profiles.group.model'), 'group_id');
    }
}
