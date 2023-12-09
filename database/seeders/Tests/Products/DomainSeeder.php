<?php

namespace Database\Seeders\Tests\Products;

use Illuminate\Database\Seeder;

class DomainSeeder extends Seeder
{
    public function run(): void
    {
        $this->call(ProductSeeder::class);
    }
}
