<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateRatingTable extends Migration
{
    public function up()
    {
        Schema::table('ratings', function (Blueprint $table) {
            /*
            Equivalente a
            $table->unsignedBigInteger('rateable_id');
            $table->string('rateable_type');

            $table->integer('qualifier_id');
            $table->string('qualifier_type');
             */
            $table->morphs('rateable');
            $table->morphs('qualifier');
        });

        \App\Rating::all()->each(function (\App\Rating $rating) {
            $rating->rater_type = \App\User::class;
            $rating->rater_id = $rating->user_id;
            $rating->rateable_type = $rating->product_id;
            $rating->rateable_id = \App\Product::class;
            $rating->save();
        });

        Schema::table('ratings', function (Blueprint $table) {
            $table->dropColumn(['user_id', 'product_id']);
        });
    }

    public function down()
    {
        Schema::table('ratings', function (Blueprint $table) {
            $table->dropColumn(['rater_type', 'rater_id', 'rateable_type', 'rateable_id']);
            $table->unsignedBigInteger('product_id');

            $table->foreign('product_id')->references('id')->on('products');
            $table->foreign('user_id')->references('id')->on('users');
        });
    }
}
