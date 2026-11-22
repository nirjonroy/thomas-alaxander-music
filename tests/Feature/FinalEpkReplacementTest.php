<?php

namespace Tests\Feature;

use App\Models\EpkPage;
use Database\Seeders\EpkPageSeeder;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class FinalEpkReplacementTest extends TestCase
{
    use DatabaseTransactions;

    public function test_final_epk_seed_replaces_copy_without_changing_media_or_publication_values(): void
    {
        $fullArtist = EpkPage::updateOrCreate(
            ['slug' => 'full-artist'],
            [
                'title' => 'Previous artist title',
                'hero_image' => 'uploads/epk/existing-artist.jpg',
                'gold_feather_image' => 'uploads/epk/existing-logo.png',
                'og_image' => 'uploads/epk/existing-og.jpg',
                'pdf_file' => 'uploads/epk/existing-artist.pdf',
                'audio_url' => 'uploads/epk/existing-audio.mp3',
                'status' => false,
                'sections' => [
                    ['type' => 'signature_interpretations', 'title' => 'Signature Interpretations', 'body' => '<p>Previously approved content.</p>'],
                ],
            ]
        );

        $crooners = EpkPage::updateOrCreate(
            ['slug' => 'crooners'],
            [
                'title' => 'Previous crooners title',
                'hero_image' => 'uploads/epk/existing-crooners.jpg',
                'pdf_file' => 'uploads/epk/existing-crooners.pdf',
                'video_url' => 'https://www.youtube.com/watch?v=stRFQS7qAiU',
                'status' => false,
                'sections' => [
                    ['type' => 'testimonials', 'title' => 'Testimonials', 'items' => [['source' => 'Existing', 'quote' => 'Previously approved testimonial.']]],
                ],
            ]
        );

        $this->seed(EpkPageSeeder::class);

        $fullArtist->refresh();
        $crooners->refresh();

        $this->assertSame('THOMAS ALEXANDER - THE VOICE', $fullArtist->title);
        $this->assertStringContainsString('Thomas Alexander is a heritage vocalist', $fullArtist->overview_content);
        $this->assertSame('uploads/epk/existing-artist.jpg', $fullArtist->hero_image);
        $this->assertSame('uploads/epk/existing-logo.png', $fullArtist->gold_feather_image);
        $this->assertSame('uploads/epk/existing-og.jpg', $fullArtist->og_image);
        $this->assertSame('uploads/epk/existing-artist.pdf', $fullArtist->pdf_file);
        $this->assertSame('uploads/epk/existing-audio.mp3', $fullArtist->audio_url);
        $this->assertFalse($fullArtist->status);
        $this->assertSame('Artist Statement', collect($fullArtist->sections)->firstWhere('type', 'quote')['title']);
        $this->assertNotNull(collect($fullArtist->sections)->firstWhere('type', 'signature_interpretations'));
        $this->assertSame(
            ['Artist Statement', 'Genres & Lanes', 'Performance Range', 'Artist Identity', 'Short Bio', 'Performance Identity', 'Audience Experience', 'Booking & Contact'],
            collect($fullArtist->sections)
                ->whereIn('title', ['Artist Statement', 'Genres & Lanes', 'Performance Range', 'Artist Identity', 'Short Bio', 'Performance Identity', 'Audience Experience', 'Booking & Contact'])
                ->pluck('title')
                ->all()
        );

        $this->assertSame('CROONERS', $crooners->title);
        $this->assertStringContainsString('A celebration of timeless vocal artistry', $crooners->overview_content);
        $this->assertSame('uploads/epk/existing-crooners.jpg', $crooners->hero_image);
        $this->assertSame('uploads/epk/existing-crooners.pdf', $crooners->pdf_file);
        $this->assertSame('https://www.youtube.com/watch?v=stRFQS7qAiU', $crooners->video_url);
        $this->assertFalse($crooners->status);

        $songGroups = collect($crooners->sections)->firstWhere('type', 'song_groups')['groups'];
        $this->assertSame(['Fly Me to the Moon', 'The Way You Look Tonight', 'Beyond the Sea', 'Unforgettable', "I've Got You Under My Skin", 'Misty', 'My Funny Valentine', 'Mona Lisa', 'At Last'], $songGroups[0]['items']);
        $this->assertSame(['Twilight Time', 'Smoke Gets in Your Eyes', 'Harbor Lights', 'The Great Pretender', 'Only You'], $songGroups[1]['items']);
        $this->assertSame(['Moon River', 'The Very Thought of You'], $songGroups[2]['items']);
        $this->assertSame('Technical Performance Format', collect($crooners->sections)->firstWhere('type', 'performance_lanes')['title']);
        $this->assertCount(4, collect($crooners->sections)->firstWhere('type', 'performance_lanes')['items']);
        $this->assertSame(['Short Bio', 'Booking & Contact', 'Closing Identity'], collect($crooners->sections)->whereIn('title', ['Short Bio', 'Booking & Contact', 'Closing Identity'])->pluck('title')->all());
        $this->assertNotNull(collect($crooners->sections)->firstWhere('type', 'testimonials'));

        foreach ([$fullArtist, $crooners] as $page) {
            $booking = collect($page->sections)->firstWhere('type', 'booking')['body'];
            $this->assertStringContainsString('Five Feathers Publishing Company', $booking);
            $this->assertStringContainsString('Artist &amp; Booking Division', $booking);
            $this->assertStringContainsString('1 (825) 440-0149', $booking);
        }
    }
}
