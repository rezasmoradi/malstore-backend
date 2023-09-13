<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateOrderRequest;
use App\Http\Requests\DeleteOrderProductRequest;
use App\Http\Requests\RegisterOrderForShopperRequest;
use App\Http\Requests\RegisterOrderRequest;
use App\Http\Requests\UpdateOrderRequest;
use App\Http\Resources\OrderCollection;
use App\Http\Resources\OrderResource;
use App\Http\Resources\ProductCollection;
use App\Models\Address;
use App\Models\CustomerGroup;
use App\Models\Discount;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;

class OrderController extends Controller
{
    public function index()
    {
        if ($guestKey = cookie(env('GUEST_USER_KEY', '__guest_user_key'))) {
            return new OrderCollection(OrderDetail::query()->where(['guest_user_key' => $guestKey]));
        } else {
            return response(['message' => 'No order have been registered'], Response::HTTP_BAD_REQUEST);
        }
    }

    public function store(CreateOrderRequest $request)
    {
        $product = $request->product;
        $discountId = null;
        $unitPrice = $product->unit_price;

        if ($request->has('coupon_code')) {
            $discount = Discount::query()->where('coupon_code', $request->coupon_code)->first();
            $discountId = $discount->id;
            if ($discount->discount_unit === Discount::DISCOUNT_UNIT_PERCENT) {
                $unitPrice = ((100 - $discount->dicount_value) / 100) * $product->unit_price;
            } else {
                $unitPrice = $product->unit_price - $discount->discount_value;
            }
            if ($discount->max_discount_amount && $request->quantity > 1) {
                if (($unitPrice * $request->quantity) > $discount->max_discount_amount) {
                    $unitPrice = $product->unit_price;
                    $totalPrice = ($unitPrice * $request->quantity) - $discount->max_discount_amount;
                }
            }
        }
        $cookieName = env('GUEST_USER_KEY', '__guest_user_key');
        $guestKey = Cookie::has($cookieName) ? Cookie::get($cookieName) : bcrypt(random_int(10000, 99999) . time() . $request->ip() . $request->userAgent());

        $order = OrderDetail::query()->firstOrCreate([
            'guest_user_key' => $guestKey,
            'product_id' => $product->id,
            'discount_id' => $discountId,
            'color_id' => $request->color_id,
            'description' => $request->description,
            'quantity' => $request->quantity ?: 1,
            'unit_price' => $unitPrice,
        ]);
        $guestKeyExpiration = env('GUEST_KEY_EXPIRATION_IN_MINUTE', 10080);
        $cookie = cookie($cookieName, $guestKey, $guestKeyExpiration, '/', '/', true, true);

        return response(['order' => new OrderResource($order)], Response::HTTP_CREATED)->withCookie($cookie);
    }

    public function register(RegisterOrderForShopperRequest $request)
    {
        $product = $request->product;
        if ($request->discount_id) {
            $discount = Discount::query()->find($request->discount_id);
            $orderQuantityIsValid = $discount->minimum_order_quantity <= $request->quantity;
            $discountTimeIsValid = now()->greaterThan($discount->expired_at) && now()->lessThan($discount->started_at);
            $productIsValid = $discount->product_id === $product->id;
            $isShopper = $discount->customerGroup->group === CustomerGroup::GROUP_SHOPPERS;
            if ($isShopper && $orderQuantityIsValid && $productIsValid && $discountTimeIsValid) {

            }
        }
    }

    public function update(UpdateOrderRequest $request)
    {
        if ($order = $request->order_detail) {
            $order->update($request->toArray());
            return response(['order' => new OrderResource($order)], Response::HTTP_ACCEPTED);
        } else {
            throw new \Exception('Product not found in the cart', Response::HTTP_NOT_FOUND);
        }
    }

    public function delete(DeleteOrderProductRequest $request)
    {
        try {
            $request->order_detail->delete();
            return response(['message' => 'product removed from card successfully'], Response::HTTP_OK);
        } catch (\Exception $e) {
            return \response(['message' => 'Product not found in the cart'], Response::HTTP_NOT_FOUND);
        }
    }

    public function destroy()
    {
        OrderDetail::query()->where('guest_user_key', cookie(env('GUEST_USER_KEY', '__guest_user_key')))->delete();

        return \response(['message' => 'Cart deleted successfully'], Response::HTTP_OK);
    }

    public function create(RegisterOrderRequest $request)
    {
        try {
            $card = OrderDetail::query()->where('guest_user_key', $request->cookie(env('GUEST_USER_KEY', '__guest_user_key')))->get();
            if ($card->first()) {
                DB::beginTransaction();

                $card->map(function ($order) {
                    $order->guest_user_key = auth()->id();
                    $order->save();
                });

                $totalPrice = array_reduce($card->toArray(), function ($carry, $value) {
                    $carry += $value['unit_price'] * $value['quantity'];
                    return $carry;
                }, 0);

                if ($request->has('address_id')) {
                    $addressId = $request->address_id;
                } else {
                    $addressId = Address::query()->create($request->all())->id;
                }

                $order = Order::query()->create([
                    'orderable_id' => auth()->id(),
                    'orderable_type' => User::class,
                    'address_delivery_id' => $addressId,
                    'phone' => $request->phone,
                    'total_payment' => $totalPrice,
                ]);

                DB::commit();
                return \response(['order' => $order], Response::HTTP_CREATED);
            } else {
                return \response(['message' => 'No order is registered for the user'], Response::HTTP_NOT_ACCEPTABLE);
            }

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e->getMessage());
            return \response(['message' => 'An error has occurred in register order'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
