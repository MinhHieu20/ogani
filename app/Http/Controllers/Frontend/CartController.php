<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Products;
use App\Models\Cart;
use Illuminate\Support\Facades\Auth;


class CartController extends Controller
{
    public function addToCart(Request $request)
    {
        $product_qty = $request->input('product_qty');
        $product_id = $request->input ('product_id');
        if(Auth::check())
        {
            $prod_check = Products::where('id',$product_id)->first();
    
            if($prod_check)
            {                
                if(Cart::where('prod_id',$product_id)->where('user_id',Auth::id())->exists())
                {
                    return response()->json(['status' => $prod_check->pname." Already Added to cart"]);
                }
                else
                {
                    $cartItem = new Cart();
                    $cartItem->user_id = Auth::id();
                    $cartItem->prod_id = $product_id;
                    $cartItem->prod_qty = $product_qty;
                    $cartItem->save();
                    return response()->json(['status' => $prod_check->pname." Added to cart"]);
                    
                }
            }
        }
        else{
            return response()->json(['status' => "Login to Continue"]);
        }
    }

    public function deleteCart(Request $request)
    {
        if(Auth::check())
        {
            $prod_id = $request->input('prod_id');
            if(Cart::where('prod_id',$prod_id)->where('user_id',Auth::id())->exists())
                {
                    $cartItem = Cart::where('prod_id',$prod_id)->where('user_id',Auth::id())->first();
                    $cartItem->delete();
                    return response()->json(['status' => "Product deleted successful"]);
                }
        }
        else{
            return response()->json(['status' => "Login to Continue"]);
        }
    }
}
