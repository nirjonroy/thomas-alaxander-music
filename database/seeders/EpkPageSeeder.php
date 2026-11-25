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
                'sections' => $content['sections'],
                'booking_email' => 'info@thomasalexanderthevoice.com',
            ]);

            if (isset($content['hero_image'])) {
                $page->hero_image = $content['hero_image'];
            }

            if ($isNew && isset($content['defaults'])) {
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
                'title' => 'THOMAS ALEXANDER — THE VOICE',
                'subtitle' => 'Canadian Multi-Styled Vocalist · Theatre-Level Artist · Storyteller',
                'overview_content' => '<p>Thomas Alexander - The Voice - is a Canadian multi-styled vocalist, theatre-level artist, and storyteller whose work is rooted in lineage, story, and lived experience. His performances carry the emotional architecture of theatre, the improvisational intelligence of jazz, and the deep-timbered resonance of a voice shaped by a lifetime of music. Thomas brings audiences into a cinematic world where memory, ancestry, and artistry converge.</p><p>Behind The Voice is a lifetime of music, memory, family, survival, and discovery. Thomas Alexander\'s BlackIndigenous, copper-skinned ancestry, including his family\'s Creek and Cherokee lineage, is part of the heritage that shaped the man and artist he became. That history lives alongside the musical traditions and experiences that have shaped his life, informing the depth, emotional truth, and lived experience he brings to his work.</p>',
                'hero_image' => 'uploads/custom-images/slider-2025-10-14-11-55-21-8097.jpg',
                'sections' => [
                    ['type' => 'hero_detail', 'body' => 'Jazz, Soul, and Blues — stories only a lifetime can sing.'],
                    ['type' => 'quote', 'title' => 'Artist Statement', 'quote' => 'Every note I sing, the living crest I wear, every chart that memory stirs — is a thread in my ancestral tapestry. This is my offering. This is who I am. This is the living archive of The Voice.'],
                    ['type' => 'tags', 'title' => 'Genres & Lanes', 'items' => ['Golden Age Soul', 'Motown', 'Jazz Standards', 'Blues', 'Cinematic Vocal Works', 'Heritage-Rooted Ceremonial Works']],
                    ['type' => 'list', 'title' => 'Performance Range', 'items' => ['Duo to full big-band concerts', 'Soft-seat theatres, intimate rooms, corporate events, heritage ceremonies']],
                    ['type' => 'content', 'title' => 'Artist Identity', 'body' => '<p>Thomas Alexander — The Voice — is a Canadian multi-styled vocalist, theatre-level artist, and storyteller whose artistry stands at the intersection of Jazz, Soul, and Blues.</p><p>His BlackIndigenous, copper-skinned ancestry, including his family\'s Creek and Cherokee lineage, is part of the heritage that shaped his life, identity, and artistry.</p><p>As Chief &amp; Elder of the Five Feathers Lineage Society, Thomas brings a lifetime of lived experience to his work with honesty, depth, and a voice unmistakably his own.</p>'],
                    ['type' => 'content', 'title' => 'Short Bio', 'body' => '<p>Thomas Alexander - The Voice - is a Canadian multi-styled vocalist, theatre-level artist, storyteller, and memoirist whose artistry spans Jazz, Soul, R&amp;B, Gospel, Country, Pop, and musical theatre.</p><p>His concerts are shaped by the voices, histories, memories, and musical traditions that have influenced him throughout his life. Whether performing in theatres, intimate venues, concert settings, or ceremonial spaces, Thomas brings audiences into a world where every lyric carries meaning and every performance is grounded in lived experience.</p><p>As a vocalist, memoirist, and lineage storyteller, Thomas continues to build a body of work that honours his heritage while preserving the tradition of real singers. His artistry stands as a living archive, a testament to the power of voice, memory, and identity.</p>'],
                    ['type' => 'content', 'title' => 'Performance Identity', 'body' => '<p>Thomas Alexander - The Voice - delivers theatre-level performances shaped by musical craftsmanship, storytelling, and connection with the audience.</p><p>His concerts move naturally through Jazz, Soul, Blues, and other musical traditions, with each song approached through his own phrasing, interpretation, and lived experience.</p><p>Every phrase is intentional. Every moment is lived. Every performance carries the unmistakable character of The Voice.</p>'],
                    ['type' => 'tags', 'title' => 'Signature Genres', 'items' => ['Jazz Standards', 'Golden Age Soul', 'Motown', 'Blues Narratives', 'Cinematic Vocal Works', 'Heritage-Rooted Ceremonial Works']],
                    ['type' => 'tags', 'title' => 'Performance Qualities', 'items' => ['Tone-Anchored Phrasing', 'Emotional Truth', 'Cinematic Delivery', 'Theatre-Level Presence', 'Lived-Experience Storytelling']],
                    ['type' => 'tags', 'title' => 'Selected Repertoire (Sample Programme)', 'items' => ['A Song for You', 'Georgia on My Mind', 'Summertime', 'At Last', 'A Change Is Gonna Come', 'The Nearness of You', 'I\'d Rather Go Blind', 'Stand by Me', 'Just My Imagination']],
                    ['type' => 'signature_interpretations', 'title' => 'Signature Interpretations', 'body' => '<p>Thomas Alexander approaches classic repertoire as an interpreter rather than an imitator, reshaping familiar songs through his own phrasing, musical instincts, and lived experience.</p><div class="signature-song-block"><h4>Stand by Me</h4><p>A deeply soulful original interpretation with a particularly personal history. Thomas had the opportunity to meet Ben E. King and play him his arrangement of Stand by Me. Thomas remembers King saying that he loved the interpretation and had never thought of doing the song that way.</p></div><div class="signature-song-block"><h4>Just My Imagination</h4><p>Thomas brings his own interpretation to this soul classic, allowing the song to live through his distinctive phrasing, emotional approach, and vocal identity rather than reproducing the familiar recording.</p></div><p class="signature-closing-quote">Together, these interpretations reflect a defining principle of Thomas\'s artistry: honour the song, honour those who made it timeless, and still find something within it that is unmistakably my own.</p>'],
                    ['type' => 'performance_lanes', 'title' => 'Performance Formats', 'items' => [
                        ['title' => 'Theatre-Level Concerts', 'body' => 'Full ensemble presentations featuring rich harmonic arrangements, dynamic pacing, and complete staging.'],
                        ['title' => 'Intimate Storytelling Evenings', 'body' => 'Duo or trio formats focusing on the emotional architecture of the songs, accompanied by personal stories and reflections.'],
                        ['title' => 'Jazz & Soul Showcases', 'body' => 'Programmes tailored to jazz clubs and festival stages, exploring the intersection of classic soul and jazz standards.'],
                        ['title' => 'Heritage-Rooted Ceremonial Performances', 'body' => 'Special presentations incorporating ceremonial works, cultural storytelling, and lineage reflection.'],
                    ]],
                    ['type' => 'content', 'title' => 'Audience Experience', 'body' => '<p>Thomas\'s performances invite audiences into a world of emotional depth, lived experience, and storytelling through song.</p><p>Every note is intentional. Every phrase is lived. Every performance carries the unmistakable character of The Voice.</p>'],
                    ['type' => 'content', 'title' => 'Performance Highlights', 'body' => '<p>Thomas Alexander\'s BlackIndigenous, copper-skinned heritage, family history, and lived experience inform his artistry and storytelling. Lineage is the foundation he carries into the music, not a limitation on the music he performs.</p><p><strong>Ironwood Stage &amp; Grill - Calgary, AB</strong><br>Featured performances with The Ray Charles Tribute Orchestra and The Motown Tribute Orchestra.</p><p><strong>Alvin’s Jazz Club - Calgary, AB</strong><br>Theatre-level vocal performance featuring Golden Age Soul, Jazz Standards, and Cinematic Vocal Works.</p><p><strong>Five Feathers Lineage Society - Edmonton, AB</strong><br>Ceremonial vocal works, heritage storytelling, and cultural presentations as Chief &amp; Elder.</p><p><strong>Heritage-Rooted Ceremonial Works</strong><br>Lineage-rooted ceremonial works including Yamassee Rising and Blue Alberta Blue (heritage submission in progress).</p><p><strong>Festival &amp; Concert Performances</strong><br>Jazz, soul, and cinematic vocal performances presented in duo, trio, and full ensemble formats.</p>'],
                    ['type' => 'testimonials', 'title' => 'Peer Comments & Critical Reception', 'items' => [
                        [
                            'quote' => 'I have worked with Thomas Alexander on numerous music projects for decades, and I deeply appreciate his talent and work ethic. Thomas is a gifted performer who is professional and engaging on stage. He has great vocal ability and a remarkable capacity for authentic style and lyric presentation. It is a pleasure to work with such an artist, who I believe is worthy of serious consideration and merit.',
                            'source' => 'Charles Austin, ARCT, BMus, ME',
                            'credential' => 'Professor Emeritus, MacEwan University',
                        ],
                        [
                            'quote' => 'Thomas Alexander brings a level of class and vocal mastery rarely heard today. His performance is polished, heartfelt, and unforgettable.',
                            'source' => 'Lonnie Hanson',
                            'credential' => 'Long-Standing Edmonton Bassist',
                        ],
                        [
                            'quote' => 'The distinguished Gentleman with the golden voice. LET’S GO!!!',
                            'source' => 'Darren Jordan',
                            'credential' => 'Founder & Director, 5 Artists 1 Love Productions',
                        ],
                        [
                            'quote' => "There are singers who possess a good voice, singers who have mastered their craft, and then there are those rare artists whose voices seem to carry an entire lifetime of music within them. Thomas Alexander belongs unmistakably to the third category.\n\nHis voice has warmth, soul, maturity and an unmistakable individuality... Thomas demonstrates the rare ability to inhabit a song rather than merely sing it.\n\nThe truly exceptional ones make you feel that they are singing directly to you. Thomas is one of those performers.",
                            'source' => 'Jan Randall',
                            'credential' => 'Musician & Composer',
                        ],
                    ]],
                    ['type' => 'content', 'title' => 'Technical Requirements', 'body' => '<p><strong>Theatre-Level • Presenter-Ready • Clean &amp; Professional</strong></p><p><strong>Technical Performance Format:</strong> Duo configuration (Vocalist + Keys). Ensemble expands for larger venues; technical requirements scale accordingly.</p><p><strong>Sound Requirements:</strong> One high-quality vocal microphone (Shure SM7B, SM58, or equivalent) • Professional PA system suitable for room size • One monitor mix for vocalist • DI input for piano/keys</p><p><strong>Piano / Keys:</strong> Acoustic piano preferred • Weighted digital keyboard acceptable if professionally amplified</p><p><strong>Stage Requirements:</strong> Minimum performance area: 8\' x 8\' • Clean, unobstructed stage • Adequate professional stage lighting</p><p><strong>Additional Notes:</strong> Heritage-rooted ceremonial works are best presented in an attentive, respectful listening environment. Technical requirements scale with ensemble size and venue capacity. Full technical rider, stage plot, and input list are provided upon booking confirmation.</p>'],
                    ['type' => 'booking', 'title' => 'Booking & Contact', 'body' => $booking],
                    ['type' => 'content', 'title' => 'Five Feathers Lineage Society', 'body' => '<p><strong>Lineage-Rooted Vocal Artist · Theatre-Level Artist · Ceremonial Storyteller</strong></p><p>Thomas Alexander - The Voice - stands in a living tradition of musical artistry. His BlackIndigenous copper-skinned heritage, lived experience, and a lifetime devoted to music are part of the man behind the voice. His performances honour the artists and musical traditions that helped shape him while allowing every song to pass through a voice unmistakably his own.</p><p>Presented with care and professionalism by Five Feathers Publishing Company - Artist &amp; Booking Division, this work continues the living archive of The Voice.</p>'],
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
                'overview_content' => '<p>A celebration of timeless vocal artistry, where classic jazz, swing, and beloved standards are carried forward through Thomas Alexander\'s distinctive voice. Shaped by decades of performance and lived musical experience, Crooners offers audiences the elegance, warmth, and emotional truth of the great crooner tradition while allowing Thomas to bring his own character to every phrase.</p><p>Behind the voice is a lifetime of music, memory, family, and lived experience. His BlackIndigenous, copper-skinned coloured ancestry, including his family\'s Creek and Cherokee lineage, is part of the heritage that shaped the man and artist behind The Voice. It is part of who Thomas is and part of what he carries with him when he steps to the microphone.</p><p>His interpretations honour the great artists and musical traditions that helped shape him while allowing every song to live through a voice and a lifetime unmistakably his own.</p><p>Crooners invites audiences into a world of elegance, memory, and emotional depth. Every note is intentional. Every phrase is lived. The result is more than a collection of timeless songs. It is an intimate musical journey shaped by storytelling, heritage, and a lifetime devoted to the art of song.</p>',
                'hero_image' => 'uploads/custom-images/thomas-crooners-signature-2026.jpg',
                'sections' => [
                    ['type' => 'song_groups', 'title' => 'Featured Selections', 'groups' => [
                        ['title' => 'Featured Selections', 'items' => ['Fly Me to the Moon', 'The Way You Look Tonight', 'Beyond the Sea', 'Unforgettable', 'I’ve Got You Under My Skin', 'Misty', 'My Funny Valentine', 'Mona Lisa', 'At Last']],
                        ['title' => 'Platters Selections', 'items' => ['Twilight Time', 'Smoke Gets in Your Eyes', 'Harbor Lights', 'The Great Pretender', 'Only You']],
                        ['title' => 'Closing Crooner Classics', 'items' => ['Moon River', 'The Very Thought of You']],
                    ]],
                    ['type' => 'testimonials', 'title' => 'Testimonials', 'items' => [
                        [
                            'quote' => 'I have worked with Thomas Alexander on numerous music projects for decades, and I deeply appreciate his talent and work ethic. Thomas is a gifted performer who is professional and engaging on stage. He has great vocal ability and a remarkable capacity for authentic style and lyric presentation. It is a pleasure to work with such an artist, who I believe is worthy of serious consideration and merit.',
                            'source' => 'Charles Austin, ARCT, BMus, ME',
                            'credential' => 'Professor Emeritus, MacEwan University',
                        ],
                        [
                            'quote' => 'Thomas Alexander brings a level of class and vocal mastery rarely heard today. His performance is polished, heartfelt, and unforgettable.',
                            'source' => 'Lonnie Hanson',
                            'credential' => 'Long-standing and respected Edmonton bassist',
                        ],
                        [
                            'quote' => "Thomas Alexander possesses the instincts of a true storyteller. He knows when to push a phrase, when to relax behind the beat, when to let a note breathe and when to deliver it with authority. The result is a performance that feels lived rather than manufactured.\n\nHis influences may range from Motown and jazz to the great soul singers, but he does not sound like an imitation of any of them. He sounds like Thomas Alexander.\n\nSome performers impress you. Some entertain you. The truly exceptional ones make you feel that they are singing directly to you. Thomas is one of those performers.\n\nAnd that is why the title The Voice is more than a nickname. It is an accurate description of an artist whose instrument has become inseparable from his identity.",
                            'source' => 'Jan Randall',
                            'credential' => 'Musician & Composer',
                        ],
                        [
                            'quote' => 'The distinguished gentleman with the golden voice. Let’s go!',
                            'source' => 'Darren Jordan',
                            'credential' => 'Founder & Director, 5 Artists 1 Love Productions',
                        ],
                    ]],
                    ['type' => 'content', 'title' => 'Performance Highlights', 'body' => '<p><strong>Theatre-Level Performance</strong><br>Alvin\'s Jazz Club - Calgary, AB<br>Theatre-level vocal performance featuring Golden Age soul, jazz standards, and cinematic crooner works.</p><p><strong>Festival &amp; Concert Performance</strong><br>Crooners is suited to intimate rooms, jazz clubs, soft-seat theatres, festivals, concert presentations, and gala events.</p><p>These highlights reflect the versatility of Thomas\'s performance experience and the ability of Crooners to move from intimate settings to theatre-level presentations.</p>'],
                    ['type' => 'performance_lanes', 'title' => 'Technical Performance Format', 'items' => [
                        ['title' => 'Duo — Vocalist + Keys', 'body' => 'A classic crooner configuration ideal for intimate rooms and soft-seat theatres. Clean, elegant, presenter-friendly.'],
                        ['title' => 'Trio — Vocalist + Keys + Bass', 'body' => 'Adds warmth, depth, and rhythmic foundation. Perfect for jazz rooms, heritage venues, and small theatres.'],
                        ['title' => 'Quartet — Vocalist + Keys + Bass + Drums', 'body' => 'A full small‑ensemble experience with cinematic swing, jazz articulation, and classic crooner energy.'],
                        ['title' => 'Full Ensemble — Vocalist + Keys + Bass + Drums + Guitar / Horns / Background Vocals', 'body' => 'A theatre‑level production offering rich harmonic texture, expanded arrangements, and the full breadth of the Crooners repertoire. Ideal for festivals, large theatres, and gala events.'],
                    ]],
                    ['type' => 'content', 'title' => 'Short Bio', 'body' => '<p>Thomas Alexander - The Voice - is a Canadian multi-styled vocalist, theatre-level artist, and storyteller whose artistry spans jazz, soul, R&amp;B, gospel, country, pop, and musical theatre.</p><p>His BlackIndigenous, copper-skinned coloured ancestry, including his family\'s Creek and Cherokee lineage, is part of the heritage that shaped the man and artist behind The Voice.</p><p>As a rare classic vocal interpreter, Thomas brings warmth, authority, storytelling, and a lifetime of musical experience to every performance. Rather than imitate the legendary singers who shaped this repertoire, he honours their artistry while allowing each song to live through a voice unmistakably his own.</p>'],
                    ['type' => 'booking', 'title' => 'Booking & Contact', 'body' => $booking],
                    ['type' => 'content', 'title' => 'ONE FINAL NOTE', 'body' => '<p>Thomas Alexander - The Voice stands in the elegance of a vocalist shaped by lineage, mastery, and lived experience. His performances honour the past, elevate the present, and offer audiences a voice rooted in truth and timeless artistry.</p><p>He sings and speaks from the heart.</p><p>Presented with care and professionalism by Five Feathers Publishing Company - Artist &amp; Booking Division, this work continues the living archive of The Voice.</p><p><strong>This is Crooners. This is The Voice.</strong></p>'],
                ],
                'defaults' => [
                    'hero_image' => 'uploads/custom-images/thomas-crooners-signature-2026.jpg',
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
}
