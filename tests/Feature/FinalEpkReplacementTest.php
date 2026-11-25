<?php

namespace Tests\Feature;

use App\Models\EpkPage;
use Database\Seeders\EpkPageSeeder;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class FinalEpkReplacementTest extends TestCase
{
    use DatabaseTransactions;

    public function test_final_epk_seed_replaces_copy_with_a2_and_a2b_specifications(): void
    {
        $this->seed(EpkPageSeeder::class);

        $fullArtist = EpkPage::where('slug', 'full-artist')->firstOrFail();
        $crooners = EpkPage::where('slug', 'crooners')->firstOrFail();

        // 1. Full Artist Checks
        $this->assertSame('THOMAS ALEXANDER — THE VOICE', $fullArtist->title);
        $this->assertStringContainsString('Thomas Alexander - The Voice - is a Canadian multi-styled vocalist', $fullArtist->overview_content);
        $this->assertStringContainsString('BlackIndigenous', $fullArtist->overview_content);
        $this->assertStringNotContainsString('Black Indigenous', $fullArtist->overview_content);

        // Signature Interpretations in Full Artist
        $sigSection = collect($fullArtist->sections)->firstWhere('type', 'signature_interpretations');
        $this->assertNotNull($sigSection);
        $this->assertStringContainsString('Stand by Me', $sigSection['body']);
        $this->assertStringContainsString('Just My Imagination', $sigSection['body']);
        $this->assertStringContainsString('Ben E. King', $sigSection['body']);
        $this->assertStringContainsString('unmistakably my own', $sigSection['body']);

        // Artist Testimonials
        $artistTestimonials = collect($fullArtist->sections)->firstWhere('type', 'testimonials')['items'];
        $this->assertCount(4, $artistTestimonials);
        $this->assertSame('Lonnie Hanson', $artistTestimonials[1]['source']);
        $this->assertSame('Long-Standing Edmonton Bassist', $artistTestimonials[1]['credential']);

        // 2. Crooners Checks
        $this->assertSame('CROONERS', $crooners->title);
        $this->assertSame('uploads/custom-images/thomas-crooners-signature-2026.jpg', $crooners->hero_image);
        $this->assertStringContainsString('A celebration of timeless vocal artistry', $crooners->overview_content);
        $this->assertStringContainsString('BlackIndigenous', $crooners->overview_content);
        $this->assertStringNotContainsString('Black Indigenous', $crooners->overview_content);

        // Repertoire Groups
        $songGroups = collect($crooners->sections)->firstWhere('type', 'song_groups')['groups'];
        $this->assertCount(3, $songGroups);
        $this->assertSame('Featured Selections', $songGroups[0]['title']);
        $this->assertCount(9, $songGroups[0]['items']);
        $this->assertSame('Platters Selections', $songGroups[1]['title']);
        $this->assertCount(5, $songGroups[1]['items']);
        $this->assertSame('Closing Crooner Classics', $songGroups[2]['title']);
        $this->assertCount(2, $songGroups[2]['items']);

        // Crooners Technical Performance Format ("presenter-friendly")
        $formats = collect($crooners->sections)->firstWhere('type', 'performance_lanes')['items'];
        $this->assertCount(4, $formats);
        $this->assertStringContainsString('presenter-friendly', $formats[0]['body']);
        $this->assertStringNotContainsString('presenterfriendly', $formats[0]['body']);

        // Crooners Testimonials (Lonnie Hanson, Jan Randall, Darren Jordan)
        $croonerTestimonials = collect($crooners->sections)->firstWhere('type', 'testimonials')['items'];
        $this->assertCount(4, $croonerTestimonials);
        $this->assertSame('Lonnie Hanson', $croonerTestimonials[1]['source']);
        $this->assertSame('Long-standing and respected Edmonton bassist', $croonerTestimonials[1]['credential']);
        $this->assertSame('Jan Randall', $croonerTestimonials[2]['source']);
        $this->assertSame('Musician & Composer', $croonerTestimonials[2]['credential']);
        $this->assertSame('Darren Jordan', $croonerTestimonials[3]['source']);
        $this->assertSame('Founder & Director, 5 Artists 1 Love Productions', $croonerTestimonials[3]['credential']);

        // Crooners ONE FINAL NOTE
        $finalNote = collect($crooners->sections)->firstWhere('title', 'ONE FINAL NOTE');
        $this->assertNotNull($finalNote);
        $this->assertStringContainsString('Thomas Alexander - The Voice stands in the elegance of a vocalist', $finalNote['body']);
        $this->assertStringContainsString('He sings and speaks from the heart.', $finalNote['body']);
        $this->assertStringContainsString('This is Crooners. This is The Voice.', $finalNote['body']);

        // Booking on both
        foreach ([$fullArtist, $crooners] as $page) {
            $booking = collect($page->sections)->firstWhere('type', 'booking')['body'];
            $this->assertStringContainsString('Five Feathers Publishing Company', $booking);
            $this->assertStringContainsString('Artist &amp; Booking Division', $booking);
            $this->assertStringContainsString('1 (825) 440-0149', $booking);
        }
    }
}
