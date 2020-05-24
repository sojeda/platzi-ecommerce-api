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
            // Sql Lite Testing
            $table->nullableMorphs('rateable');
            $table->nullableMorphs('qualifier');
        });

        \App\Rating::all()->each(function (\App\Rating $rating) {
            $rating->qualifier_type = \App\User::class;
            $rating->qualifier_id = $rating->user_id;
            $rating->rateable_type = $rating->product_id;
            $rating->rateable_id = \App\Product::class;
            $rating->save();
        });

        Schema::table('ratings', function (Blueprint $table) {
            if (env('DB_CONNECTION') !== 'sqlite') {
                // Not working on SQL Lite
                $table->dropForeign('ratings_product_id_foreign');
                $table->dropForeign('ratings_user_id_foreign');
            }
            $table->dropColumn(['user_id', 'product_id']);
        });
    }

    public function down()
    {
        Schema::table('ratings', function (Blueprint $table) {
            $table->dropColumn(['qualifier_type', 'qualifier_id', 'rateable_type', 'rateable_id']);
            $table->unsignedBigInteger('product_id');
            $table->unsignedBigInteger('user_id');

            $table->foreign('product_id')->references('id')->on('products');
            $table->foreign('user_id')->references('id')->on('users');
        });
    }
}
