<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

class AddCreatedByToProductsTable extends Migration
{
    public function up()
    {
        $user = factory(\App\User::class)->create([
            'name' => 'Administrador',
            'email' => 'admin@admin.com',
            'password' => Hash::make(12345678),
        ]);

        Schema::table('products', function (Blueprint $table) use ($user) {
            $table->unsignedBigInteger('created_by')->default($user->id);

            $table->foreign('created_by')->references('id')->on('users');
        });
    }

    public function down()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropForeign(['created_by']);
            $table->dropColumn('created_by');
        });
    }
}
