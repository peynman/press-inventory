<?php

return [
    // delivery agents
    'delivery_agents' => [
        'alopeyk' => \Larapress\Inventory\Services\DeliveryAgent\AloPeyk\AloPeykDeliveryAgent::class,
        'alopeyk_user' => \Larapress\Inventory\Services\DeliveryAgent\AloPeyk\AloPeykUserDeliveryAgent::class,
        'poste_pishtaz' => \Larapress\Inventory\Services\DeliveryAgent\PostePishtaz\PostePishtazDeliveryAgent::class,
        'poste_pishtaz_user' => \Larapress\Inventory\Services\DeliveryAgent\PostePishtaz\PostePishtazUserDeliveryAgent::class,
        'snap_user' => \Larapress\Inventory\Services\DeliveryAgent\Snap\SnapUserDeliveryAgent::class,
    ],

    // crud resources of the package
    'permissions' => [
        \Larapress\Inventory\CRUD\InventoryBranchCRUDProvider::class,
        \Larapress\Inventory\CRUD\ProductStockCRUDProvider::class,
    ],

    //
    'routes' => [
        'inventory_branches' => [
            'name' => 'branches',
            'model' => \Larapress\Inventory\Models\InventoryBranch::class,
            'provider' => \Larapress\Inventory\CRUD\InventoryBranchCRUDProvider::class,
        ],
        'product_stocks' => [
            'name' => 'product-stocks',
            'model' => \Larapress\Inventory\Models\ProductStock::class,
            'provider' => \Larapress\Inventory\CRUD\ProductStockCRUDProvider::class,
        ],
    ],
];
