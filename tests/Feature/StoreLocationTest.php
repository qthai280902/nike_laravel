<?php

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class StoreLocationTest extends TestCase
{
    #[Test]
    public function stores_page_displays_locations_with_google_maps_links(): void
    {
        $this->get(route('stores'))
            ->assertOk()
            ->assertSee('Nike Vincom Bà Triệu')
            ->assertSee('Nike Tràng Tiền Plaza')
            ->assertSee('Nike Vincom Đồng Khởi')
            ->assertSee('Nike Crescent Mall')
            ->assertSee('Nike Vincom Đà Nẵng')
            ->assertSee('https://www.google.com/maps/search/?api=1&query=', false)
            ->assertSee('Mở Google Maps');
    }
}
