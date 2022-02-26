<?php

namespace Larapress\Inventory\CRUD;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Larapress\CRUD\Services\CRUD\Traits\CRUDProviderTrait;
use Larapress\CRUD\Services\CRUD\ICRUDProvider;
use Larapress\CRUD\Services\CRUD\ICRUDVerb;
use Larapress\CRUD\Services\RBAC\IPermissionsMetadata;
use Larapress\ECommerce\Models\Product;
use Larapress\ECommerce\IECommerceUser;

class InventoryBranchCRUDProvider implements
    ICRUDProvider,
    IPermissionsMetadata
{
    use CRUDProviderTrait;

    public $name_in_config = 'larapress.inventory.routes.inventory_branches.name';
    public $model_in_config = 'larapress.inventory.routes.inventory_branches.model';
    public $compositions_in_config = 'larapress.inventory.routes.inventory_branches.compositions';

    public $validSortColumns = [
        'id' => 'id',
        'author_id' => 'author_id',
        'group_id' => 'group_id',
        'name' => 'name',
        'created_at' => 'created_at',
        'updated_at' => 'updated_at',
        'deleted_at' => 'deleted_at',
    ];
    public $searchColumns = [
        'id',
        'name',
        'data',
    ];

    /**
     * Undocumented function
     *
     * @return array
     */
    public function getPermissionVerbs(): array
    {
        return [
            ICRUDVerb::VIEW,
            ICRUDVerb::SHOW,
            ICRUDVerb::CREATE,
            ICRUDVerb::EDIT,
            ICRUDVerb::DELETE,
        ];
    }

    /**
     * Undocumented function
     *
     * @return array
     */
    public function getFilterFields(): array
    {
        return [
            'group_id' => 'equals:group_id',
            'name' => 'equals:name',
        ];
    }

    /**
     * Undocumented function
     *
     * @return array
     */
    public function getValidRelations(): array
    {
        return [
            'author' => config('larapress.crud.user.provider'),
            'group' => config('larapress.profiles.routes.groups.provider'),
        ];
    }

    /**
     * @param Builder $query
     *
     * @return Builder
     */
    public function onBeforeQuery(Builder $query): Builder
    {
        /** @var IECommerceUser $user */
        $user = Auth::user();
        if (!$user->hasRole(config('larapress.profiles.security.roles.super_role'))) {
            if ($user->hasRole(config('larapress.ecommerce.products.product_owner_role_ids'))) {
                $query->orWhereIn('product_id', $user->getOwenedProductsIds());
            } else {
                $query->orWhere('author_id', $user->id);
            }

        }

        return $query;
    }

    /**
     * @param Product $object
     *
     * @return bool
     */
    public function onBeforeAccess($object): bool
    {
        /** @var IECommerceUser $user */
        $user = Auth::user();
        if (!$user->hasRole(config('larapress.profiles.security.roles.super_role'))) {
            if ($user->hasRole(config('larapress.ecommerce.products.product_owner_role_ids'))) {
                return in_array($object->product_id, $user->getOwenedProductsIds());
            }
            return $user->id === $object->author_id;
        }

        return true;
    }
}
