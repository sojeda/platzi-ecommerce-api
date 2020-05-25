<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductRatingRequest;
use App\Http\Resources\ProductResource;
use App\Product;
use App\Rating;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

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

    public function approve(Rating $rating)
    {
        Gate::authorize('admin', $rating);

        $rating->approve();
        $rating->save();

        return response()->json();
    }
}
