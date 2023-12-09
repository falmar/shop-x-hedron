<?php

namespace Database\Seeders\Tests\Carts;

use Illuminate\Database\Seeder;

class DomainSeeder extends Seeder
{
    public function run(): void
    {
        $this->call(CartSeeder::class);
        $this->call(CartItemSeeder::class);
    }
}
