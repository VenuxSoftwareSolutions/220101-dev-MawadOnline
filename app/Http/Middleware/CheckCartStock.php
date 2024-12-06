<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\Wishlist;

class CheckCartStock
{
    public function handle(Request $request, Closure $next)
    {
        $carts = Cart::where('user_id', auth()->user()->id)->get();

        if ($carts && $carts->count() > 0) {
            $outOfStockProducts = [];

            $carts = $carts->filter(function ($cart) use (&$outOfStockProducts) {
                if ($cart->product->getTotalQuantity() == 0) {
                    $outOfStockProducts[] = $cart->product->name;

                    $existingWishlist = Wishlist::where('user_id', auth()->user()->id)
                        ->where('product_id', $cart->product->id)
                        ->first();

                    // Move to Wishlist if not already in the wishlist
                    if ($existingWishlist === null) {
                        Wishlist::create([
                            'user_id' => auth()->user()->id,
                            'product_id' => $cart->product->id,
                        ]);
                    }

                    $cart->delete();
                    return false;
                }

                return true;
            });

            if (count($outOfStockProducts) > 0) {
                flash(
                    __('Product :product is out of stock! We\'ve placed it on your wishlist', [
                        "product" => implode(", ", $outOfStockProducts)
                    ]),
                )->warning();

                return redirect()->route('cart');
            }
        }

        return $next($request);
    }
}
