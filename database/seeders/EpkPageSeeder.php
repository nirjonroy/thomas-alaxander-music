<?php

namespace Database\Seeders;

use App\Models\EpkPage;
use Illuminate\Database\Seeder;

class EpkPageSeeder extends Seeder
{
    public function run(): void
    {
        foreach ($this->pages() as $slug => $content) {
            $page = EpkPage::firstOrNew(['slug' => $slug]);
            $isNew = ! $page->exists;

            $page->fill([
                'title' => $content['title'],
                'subtitle' => $content['subtitle'],
                'overview_content' => $content['overview_content'],
                'sections' => $this->mergeSections($page->sections ?? [], $content['sections']),
                'booking_email' => 'info@thomasalexanderthevoice.com',
            ]);

            if ($isNew) {
                $page->fill($content['defaults']);
            }

            $page->save();
        }
    }

    private function pages(): array
    {
        $booking = '<p>All performance inquiries, bookings, and representation are handled exclusively through:</p>'
            .'<p><strong>Five Feathers Publishing Company</strong><br>'
            .'Artist &amp; Booking Division<br>'
            .'Official Representative of:<br>'
            .'Thomas Alexander - The Voice</p>'
            .'<p>Edmonton &amp; Calgary, Alberta, Canada<br>'
            .'<a href="mailto:info@thomasalexanderthevoice.com">info@thomasalexanderthevoice.com</a><br>'
            .'1 (825) 440-0149</p>';

        return [
            'full-artist' => [
                'title' => 'THOMAS ALEXANDER - THE VOICE',
                'subtitle' => 'Heritage Vocalist | Theatre-Level Artist',
                'overview_content' => '<p>Thomas Alexander is a heritage vocalist and theatre-level artist whose work is rooted in lineage, story, and lived experience. His performances carry the emotional architecture of theatre, the improvisational intelligence of jazz, and the deep-timbred resonance of heritage singing. With a lifetime of lived narrative behind every note, Thomas brings audiences into a cinematic world where memory, ancestry, and artistry converge.</p><p>His artistry is shaped by a unified Black Indigenous, copper-skinned lineage, with Creek, Cherokee, and Prairie homesteader roots carried forward through the Five Feathers Living Archive. His documented family history and lived experience inform the work. Every chart, every phrase, every performance is part of that ancestral tapestry.</p>',
                'sections' => [
                    ['type' => 'hero_detail', 'body' => 'Jazz, Soul, and Blues - stories only a lifetime can sing.<br><strong>Music. Storytelling. Legacy.</strong>'],
                    ['type' => 'quote', 'title' => 'Artist Statement', 'quote' => 'Every note I sing, the living crest I wear, every chart that memory stirs - is a thread in my ancestral tapestry. This is my offering. This is who I am. This is the living archive of The Voice.'],
                    ['type' => 'tags', 'title' => 'Genres & Lanes', 'items' => ['Golden Age Soul', 'Motown', 'Jazz Standards', 'Cinematic Vocal Works', 'Heritage-Rooted Ceremonial Works']],
                    ['type' => 'list', 'title' => 'Performance Range', 'items' => ['Duo to full big-band concerts', 'Soft-seat theatres, intimate rooms, corporate events, heritage ceremonies']],
                    ['type' => 'content', 'title' => 'Artist Identity', 'body' => '<p>Thomas Alexander - The Voice - is a heritage-level vocalist whose work stands at the intersection of Jazz, Soul, and Blues. Rooted in a Black Indigenous, copper-skinned lineage, and serving as Chief &amp; Elder of the Five Feathers Lineage Society, Thomas carries a ceremonial, theatre-level presence shaped by lived experience and ancestral truth.</p><p>His performances blend cinematic storytelling with emotional depth, delivering songs with the weight, clarity, and authenticity of a lifetime. Rooted in lineage and guided by the discipline of old-school vocal craft, Thomas brings audiences into a world where every note is a thread in an ancestral tapestry.</p><p>His signature identity, &quot;stories only a lifetime can sing,&quot; defines his artistic offering.</p>'],
                    ['type' => 'content', 'title' => 'Short Bio', 'body' => '<p>Thomas Alexander - The Voice - is a heritage vocalist, theatre-level artist, and ceremonial storyteller whose work emerges from a Black Indigenous, copper-skinned lineage, shaped by ancestry and lived experience.</p><p>As Chief &amp; Elder of the Five Feathers Lineage Society, Thomas carries forward the legacy of Creek, Cherokee, and Yamassee roots, weaving ancestral truth into every performance. His voice is grounded in emotional architecture, the discipline of singing from memory, from experience, and from the stories carried across generations.</p><p>Raised within a tradition of real singers, those who shaped their craft before machines could correct pitch, Thomas embodies an old-school vocal discipline that prioritizes tone, breath, resonance, and authenticity. His performances are cinematic, intimate, and deeply human, blending Jazz, Soul, and Blues with the weight of a lifetime. Each phrase is delivered with clarity and intention, shaped by the belief that music is not merely performance, but offering.</p>'],
                    ['type' => 'content', 'title' => 'Performance Identity', 'body' => '<p>Thomas Alexander - The Voice - delivers theatre-level performances rooted in Jazz, Soul, and Blues. His concerts are cinematic, emotionally grounded, and lineage-rooted, shaped by the discipline of old-school vocal craft. Every phrase is intentional, every moment lived, and every performance carries the weight of memory, ancestry, and story.</p>'],
                    ['type' => 'content', 'title' => 'Audience Experience', 'body' => '<p>Thomas\'s performances invite audiences into a world of emotional depth, ancestral resonance, and cinematic storytelling.</p><p>Every note is intentional. Every phrase is lived. Every moment is ceremonial.</p>'],
                    ['type' => 'booking', 'title' => 'Booking & Contact', 'body' => $booking],
                ],
                'defaults' => [
                    'hero_image' => 'uploads/custom-images/slider-2025-10-14-11-55-21-8097.jpg',
                    'hero_image_alt' => 'Thomas Alexander performing and seated in a gold jacket',
                    'gold_feather_image' => 'uploads/website-images/logo-2025-02-14-08-43-44-5421.png',
                    'gold_feather_image_alt' => 'Thomas Alexander The Voice logo',
                    'status' => true,
                    'sort_order' => 1,
                    'seo_title' => 'Thomas Alexander - The Voice | Full Artist EPK',
                    'seo_description' => 'Explore the official Thomas Alexander - The Voice electronic press kit, featuring artist biography, performance formats and booking information.',
                ],
            ],
            'crooners' => [
                'title' => 'CROONERS',
                'subtitle' => 'A Legacy of Elegance, Heritage, and Timeless Musical Artistry',
                'overview_content' => '<p>A celebration of timeless vocal artistry, where classic jazz, swing, and beloved standards are carried forward through Thomas Alexander\'s heritage-rooted voice. Shaped by decades of performance and lived musical experience, the show offers audiences the elegance, warmth, and emotional truth of the great crooner tradition while bringing Thomas\'s own distinctive voice to every phrase.</p><p>Thomas Alexander\'s artistry is shaped by his Black Indigenous, Copper Skin Creek, and Cherokee lineage, a heritage carried forward through generations and expressed through the emotional resonance of his voice. His interpretations of classic crooner and Platters repertoire honour memory, culture, and the living archive he represents as The Voice. As a cultural steward, Thomas brings forward a vocal tradition shaped by resilience, survival, and generational artistry.</p><p>Crooners invites audiences into a world of elegance, memory, and emotional depth. Every note is intentional. Every phrase is lived. The result is more than a collection of timeless songs: it is an intimate musical journey shaped by heritage, storytelling, and a lifetime devoted to the art of song.</p>',
                'sections' => [
                    ['type' => 'song_groups', 'title' => 'Featured Selections', 'groups' => [
                        ['title' => 'Featured Selections', 'items' => ['Fly Me to the Moon', 'The Way You Look Tonight', 'Beyond the Sea', 'Unforgettable', 'I\'ve Got You Under My Skin', 'Misty', 'My Funny Valentine', 'Mona Lisa', 'At Last']],
                        ['title' => 'Platters Selections', 'items' => ['Twilight Time', 'Smoke Gets in Your Eyes', 'Harbor Lights', 'The Great Pretender', 'Only You']],
                        ['title' => 'Closing Crooner Classics', 'items' => ['Moon River', 'The Very Thought of You']],
                    ]],
                    ['type' => 'performance_lanes', 'title' => 'Technical Performance Format', 'items' => [
                        ['title' => 'Duo - Vocalist + Keys', 'body' => 'A classic crooner configuration ideal for intimate rooms and soft-seat theatres. Clean, elegant, presenter-friendly.'],
                        ['title' => 'Trio - Vocalist + Keys + Bass', 'body' => 'Adds warmth, depth, and rhythmic foundation. Perfect for jazz rooms, heritage venues, and small theatres.'],
                        ['title' => 'Quartet - Vocalist + Keys + Bass + Drums', 'body' => 'A full small-ensemble experience with cinematic swing, jazz articulation, and classic crooner energy.'],
                        ['title' => 'Full Ensemble - Vocalist + Keys + Bass + Drums + Guitar / Horns / Background Vocals', 'body' => 'A theatre-level production offering rich harmonic texture, expanded arrangements, and the full breadth of the Crooners repertoire. Ideal for festivals, large theatres, and gala events.'],
                    ]],
                    ['type' => 'content', 'title' => 'Short Bio', 'body' => '<p>Thomas Alexander - The Voice - is a heritage vocalist, theatre-level artist, and storyteller whose career crosses jazz, soul, R&amp;B, gospel, country, pop, and musical theatre. His artistry is grounded in decades of performance and in his Black Indigenous lineage, carrying Creek, Cherokee, and Yamassee roots forward through music.</p><p>A Rare Classic Vocal Interpreter, Thomas brings warmth, authority, storytelling, and lived musical experience to every performance. Rather than imitate the legendary singers who shaped this repertoire, he honours their artistry while bringing his own distinctive voice to the song.</p>'],
                    ['type' => 'booking', 'title' => 'Booking & Contact', 'body' => $booking],
                    ['type' => 'content', 'title' => 'Closing Identity', 'body' => '<p>Thomas Alexander - The Voice stands in a living tradition of musical royalty. Every note he sings carries ancestral memory, cultural continuity, and the elegance of a vocalist shaped by lineage, mastery, and lived experience. His performances honour the past, elevate the present, and offer audiences a voice rooted in heritage, truth, and timeless artistry.</p><p>Presented with care and professionalism by Five Feathers Publishing Company - Artist &amp; Booking Division, this work continues the living archive of The Voice.</p><p>This is Crooners. This is The Voice.</p>'],
                ],
                'defaults' => [
                    'hero_image' => 'uploads/custom-images/thomas-alex-2025-02-15-07-52-26-1719.jpeg',
                    'hero_image_alt' => 'Thomas Alexander performing on stage',
                    'gold_feather_image' => 'uploads/website-images/logo-2025-02-14-08-43-44-5421.png',
                    'gold_feather_image_alt' => 'Thomas Alexander The Voice logo',
                    'video_url' => 'https://www.youtube.com/watch?v=stRFQS7qAiU',
                    'video_title' => 'A Change Is Gonna Come - Thomas Alexander The Voice',
                    'status' => true,
                    'sort_order' => 2,
                    'seo_title' => 'Thomas Alexander - The Voice | Crooners EPK',
                    'seo_description' => 'Explore the Crooners EPK for Thomas Alexander - The Voice, featuring classic repertoire, performance formats and booking information.',
                ],
            ],
        ];
    }

    private function mergeSections(array $existing, array $replacement): array
    {
        $replacementTitles = collect($replacement)
            ->map(fn (array $section) => trim(strip_tags((string) ($section['title'] ?? ''))))
            ->filter()
            ->all();

        $preserved = collect($existing)
            ->reject(function (array $section) use ($replacementTitles) {
                $title = trim(strip_tags((string) ($section['title'] ?? '')));

                return ($section['type'] ?? null) === 'booking' || in_array($title, $replacementTitles, true);
            })
            ->values()
            ->all();

        return array_merge($replacement, $preserved);
    }
}
