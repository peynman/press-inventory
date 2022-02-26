<?php

namespace Larapress\Inventory\Services\CartInventory;

use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Larapress\CRUD\Events\CRUDUpdated;
use Larapress\CRUD\Exceptions\AppException;
use Larapress\ECommerce\IECommerceUser;
use Larapress\ECommerce\Services\Cart\CartEvent;
use Larapress\ECommerce\Services\Cart\Requests\CartUpdateRequest;

class CartInventoryService
{

    /**
     * Undocumented function
     *
     * @param CartUpdateRequest $request
     * @param IECommerceUser $user
     * @param integer $currency
     *
     * @return mixed
     */
    public function updateCartDeliveryData($cart, CartUpdateRequest $request, IECommerceUser $user, int $currency)
    {
        $cart->setDeliveryAddress($request->getDeliveryAddressId());
        $cart->setDeliveryPreferredTimestamp($request->getDeliveryTimestamp());

        if (!is_null($request->getDeliveryAgentName()) && !is_null($request->getDeliveryAddressId())) {
            $agentClass = config('larapress.inventory.delivery_agents.' . $request->getDeliveryAgentName());
            if (!class_exists($agentClass)) {
                throw new AppException(AppException::ERR_OBJ_NOT_READY);
            }

            $address = $cart->getDeliveryAddress();
            /** @var IDeliveryAgentClient */
            $agent = new $agentClass();
            if ($agent->canDeliveryForAddress($address)) {
                $price = $agent->getEstimatedPrice($address, $currency);
                $cart->setDeliveryPrice($price);
                $cart->setDeliveryAgentName($request->getDeliveryAgentName());
            } else {
                throw new AppException(AppException::ERR_INVALID_PARAMS);
            }
        } else {
            $cart->setDeliveryPrice(0);
            $cart->setDeliveryAgentName(null);
        }

        /** @var IDeliveryAgent */
        $agent = app(IDeliveryAgent::class);
        $avAgents = $agent->getAvailableAgentsForCart($cart);
        $cart->setAvailableDeliveryAgents($avAgents);
        $cart->removeGiftCodeUsage();

        // update amount based on products and gift code
        $cart->amount = $this->cartService->calculateCartAmountFromDataAndProducts($cart);
        // save cart updates
        $cart->update();

        $this->resetPurchasingCache($user->id);
        $cart = $this->getPurchasingCart($user, $currency);
        CRUDUpdated::dispatch(
            Auth::user(),
            $cart,
            CartCRUDProvider::class,
            Carbon::now()
        );
        CartEvent::dispatch(
            $cart->id,
            Carbon::now()
        );

        return $cart;
    }
}
