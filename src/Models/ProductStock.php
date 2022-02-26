<?php

namespace Larapress\Inventory\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Larapress\Profiles\IProfileUser;
use Larapress\Profiles\Models\Group;

/**
 * @property int                  $id
 * @property int                  $flags
 * @property int                  $priority
 * @property int                  $product_id
 * @property int                  $branch_id
 * @property int                  $author_id
 * @property int                  $group_id
 * @property IProfileUser         $author
 * @property InventoryBranch      $branch
 * @property Product              $product
 * @property Group                $group
 * @property array                $data
 * @property \Carbon\Carbon       $shelved_at
 * @property \Carbon\Carbon       $updated_at
 * @property \Carbon\Carbon       $deleted_at
 */
class ProductStock extends Model
{
    const STATUS_ACTIVE = 0;
    const STATUS_INACTIVE = 1;

    use SoftDeletes;

    protected $table = 'product_stocks';

    protected $fillable = [
        'author_id',
        'branch_id',
        'product_id',
        'group_id',
        'qoh',
        'status',
        'priority',
        'data',
        'flags',
    ];

    public $casts = [
        'data' => 'array',
    ];

    public $appends = [];

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
    public function branch()
    {
        return $this->belongsTo(config('larapress.inventory.inventory_branches.model'), 'branch_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function product()
    {
        return $this->belongsTo(config('larapress.ecommerce.products.model'), 'product_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function group()
    {
        return $this->belongsTo(config('larapress.profiles.group.model'), 'group_id');
    }
}
