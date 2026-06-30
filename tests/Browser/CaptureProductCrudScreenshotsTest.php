<?php

use App\Models\Product;
use App\Models\User;
use Laravel\Dusk\Browser;

beforeEach(function () {
    Browser::$storeScreenshotsAt = base_path('docs/dusk/images/crud');
});

/**
 * @group crud-screenshots
 */
test('capture product index page screenshot', function () {
    // Seed 5 products
    Product::factory()->create(['name' => 'Product 1', 'price' => 100.00]);
    Product::factory()->create(['name' => 'Product 2', 'price' => 200.50]);
    Product::factory()->create(['name' => 'Product 3', 'price' => 150.75]);
    Product::factory()->create(['name' => 'Product 4', 'price' => 300.25]);
    Product::factory()->create(['name' => 'Product 5', 'price' => 250.00]);

    $user = User::factory()->create();

    $this->browse(function (Browser $browser) use ($user) {
        $browser->loginAs($user)
            ->visit('/products')
            ->pause(4000)
            ->screenshot('product-index');
    });
});

test('capture product create page screenshot', function () {
    $user = User::factory()->create();

    $this->browse(function (Browser $browser) use ($user) {
        $browser->loginAs($user)
            ->visit('/products/create')
            ->pause(500)
            ->screenshot('product-create');
    });
});

test('capture product show page screenshot', function () {
    $user = User::factory()->create();
    $product = Product::factory()->create();

    $this->browse(function (Browser $browser) use ($user, $product) {
        $browser->loginAs($user)
            ->visit("/products/{$product->id}")
            ->pause(500)
            ->screenshot('product-show');
    });
});

test('capture product edit page screenshot', function () {
    $user = User::factory()->create();
    $product = Product::factory()->create();

    $this->browse(function (Browser $browser) use ($user, $product) {
        $browser->loginAs($user)
            ->visit("/products/{$product->id}/edit")
            ->pause(500)
            ->screenshot('product-edit');
    });
});
