<?php

namespace Tests\Feature;

use Tests\TestCase;

class FinalContentPagesTest extends TestCase
{
    public function test_living_legacy_page_renders_final_content(): void
    {
        $response = $this->get(route('front.living-legacy'));

        $response->assertOk();
        $response->assertSee('Thomas Alexander — Chief &amp; Elder', false);
        $response->assertSee('Five Feathers Lineage Society');
        $response->assertSee('Copper-coloured skinned homesteader heritage');
        $response->assertSee('Living Archive of The Voice');
    }

    public function test_music_page_renders_final_content(): void
    {
        $response = $this->get(route('front.music'));

        $response->assertOk();
        $response->assertSee('Thomas Alexander — The Voice');
        $response->assertSee('Jazz. Soul. Blues. Stories Only a Lifetime Can Sing.');
        $response->assertSee('Five Feathers Music Agency — Booking Division');
        $response->assertSee('info@thomasalexanderthevoice.com');
    }

    public function test_legacy_all_songs_url_redirects_to_music_page(): void
    {
        $this->get(route('front.product.all.product'))
            ->assertRedirect(route('front.music'));
    }

    public function test_epk_contact_inquiry_page_renders_booking_context(): void
    {
        $response = $this->get(route('front.contact_us', ['inquiry' => 'epk']));

        $response->assertOk();
        $response->assertSee('Request EPK');
        $response->assertSee('Five Feathers Music Agency');
        $response->assertSee('EPK Request — Thomas Alexander (The Voice)');
        $response->assertSee('info@thomasalexanderthevoice.com');
    }
}
