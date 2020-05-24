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
    }

    public function down()
    {
        Schema::table('ratings', function (Blueprint $table) {
            $table->dropColumn(['rater_type', 'rater_id', 'rateable_type', 'rateable_id']);
        });
    }
}
