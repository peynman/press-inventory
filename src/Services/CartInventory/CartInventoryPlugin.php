<?php

namespace Larapress\Inventory\Services\CartInventory;

use Closure;
use Larapress\CRUD\Exceptions\AppException;
use Larapress\ECommerce\Models\Product;
use Larapress\ECommerce\Services\Banking\BankRedirectRequest;
use Larapress\ECommerce\Services\Cart\ICart;
use Larapress\ECommerce\Services\Cart\ICartServicePlugin;
use Larapress\ECommerce\Services\Cart\Requests\CartContentModifyRequest;
use Larapress\ECommerce\Services\Cart\Requests\CartValidateRequest;

class CartInventoryPlugin implements ICartServicePlugin {
    /**
     * Undocumented function
     *
     * @param ICart $cart
     * @param CartContentModifyRequest $request
     * @param Closure $next
     *
     * @return mixed
     */
    public function beforeContentModify(ICart $cart, CartContentModifyRequest $request, Closure $next) {
        return $next($cart, $request);
    }

    /**
     * Undocumented function
     *
     * @param ICart $cart
     * @param CartContentModifyRequest $request
     * @param Closure $next
     *
     * @return mixed
     */
    public function afterContentModify(ICart $cart, CartContentModifyRequest $request, Closure $next) {
        return $next($cart, $request);
    }

    /**
     * Undocumented function
     *
     * @param ICart $cart
     * @param Closure $next
     *
     * @return mixed
     */
    public function beforePurchase(ICart $cart, Closure $next) {
        return $next($cart);
    }

    /**
     * Undocumented function
     *
     * @param ICart $cart
     * @param Closure $next
     *
     * @return mixed
     */
    public function afterPurchase(ICart $cart, Closure $next) {
        return $next($cart);
    }

    /**
     * Undocumented function
     *
     * @param ICart $cart
     * @param CartValidateRequest $request
     * @param Closure $next
     *
     * @return mixed
     */
    public function validateBeforeBankForwarding(ICart $cart, CartValidateRequest $request, Closure $next) {

        $addressId = $request->getDeliveryAddressId();
        if (!is_null($addressId)) {
            if ($cart->getDeliveryAddressId() !== $addressId) {
                throw new AppException(AppException::ERR_INVALID_QUERY);
            }
        }

        $agent = $request->getDeliveryAgentName();
        if (!is_null($agent)) {
            if ($cart->getDeliveryAgentName() !== $agent) {
                throw new AppException(AppException::ERR_INVALID_QUERY);
            }
        }

        return $next($cart, $request);
    }

    /**
     * Undocumented function
     *
     * @param ICart                 $cart
     * @param array                 $requestProdPivot
     * @param Product               $product
     * @param CartValidateRequest   $request
     * @param Closure               $next
     *
     * @return mixed
     */
    public function validateProductDataInCart(
        ICart $cart,
        array $requestProdPivot,
        Product $product,
        CartValidateRequest $request,
        Closure $next
    ) {
        if ($requestProdPivot['quantity'] !== $product->pivot?->data['quantity']) {
            throw new AppException(AppException::ERR_INVALID_QUERY);
        }
        return $next($cart, $requestProdPivot, $product, $request);
    }

    /**
     * Undocumented function
     *
     * @param ICart $cart
     * @param Closure $next
     * @return mixed
     */
    public function afterBankResolved(ICart $cart, Closure $next) {
        return $next($cart);
    }

}
