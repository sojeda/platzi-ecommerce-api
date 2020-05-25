<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductRatingRequest;
use App\Http\Resources\ProductResource;
use App\Product;
use App\User;
use Illuminate\Http\Request;

class ProductRatingController extends Controller
{
    public function rate(Product $product, ProductRatingRequest $request)
    {
        /** @var User $user */
        $user = $request->user();
        $user->rate($product, $request->get('score'), $request->get('comments'));

        return new ProductResource($product);
    }

    public function unrate(Product $product, Request $request)
    {
        /** @var User $user */
        $user = $request->user();
        $user->unrate($product);

        return new ProductResource($product);
    }
}
