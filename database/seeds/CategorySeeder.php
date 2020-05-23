<?php

use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run()
    {
        factory(\App\Category::class, 5)->create();
    }
}
