<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateRatingTable extends Migration
{
    public function up()
    {
        Schema::create('ratings', function (Blueprint $table) {
            $table->id();
            $table->float('score');
            $table->timestamps();

            /*
            Equivalente a
            $table->unsignedBigInteger('rateable_id');
            $table->string('rateable_type');

            $table->integer('qualifier_id');
            $table->string('qualifier_type');
             */
            $table->nullableMorphs('rateable');
            $table->nullableMorphs('qualifier');
        });
    }

    public function down()
    {
        Schema::dropIfExists('ratings');
    }
}
