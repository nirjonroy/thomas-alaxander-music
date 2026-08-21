<?php

namespace Database\Seeders;

use App\Models\EpkPage;
use Illuminate\Database\Seeder;

class EpkPageSeeder extends Seeder
{
    public function run(): void
    {
        $agency = config('artist_representation.agency');
        $email = config('artist_representation.email');
        $location = config('artist_representation.location');
        $line = config('artist_representation.line');
        $shortLine = config('artist_representation.short_line');
        $bookingLabel = config('artist_representation.booking_label');

        $pages = [
            [
                'title' => 'Thomas Alexander — The Voice',
                'slug' => 'full-artist',
                'subtitle' => $shortLine,
                'hero_image' => 'uploads/custom-images/slider-2025-10-14-11-55-21-8097.jpg',
                'hero_image_alt' => 'Thomas Alexander performing and seated in a gold jacket',
                'gold_feather_image' => 'uploads/website-images/logo-2025-02-14-08-43-44-5421.png',
                'gold_feather_image_alt' => 'Thomas Alexander The Voice logo',
                'overview_content' => '<p>Thomas Alexander — The Voice — is a heritage-rooted vocalist whose artistry is shaped by his Copper-colored skin lineage, unified Black Indigenous ancestry, and deep Alberta homesteader roots. His performances blend classic jazz, soul, crooner repertoire, big band features, tribute show mastery, and narrative storytelling with a vocal warmth carried through generations. His career spans intimate jazz clubs, orchestral halls, cultural showcases, and touring productions across Western Canada.</p>',
                'sections' => [
                    [
                        'type' => 'performance_lanes',
                        'title' => 'Performance Lanes',
                        'items' => [
                            ['title' => 'Jazz & Soul', 'body' => 'Alvin\'s Jazz Club, Yardbird Suite'],
                            ['title' => 'Big Band & Orchestra', 'body' => 'Tommy Banks Big Band, Cosmopolitan Orchestra'],
                            ['title' => 'Tribute Shows', 'body' => 'Ray Charles Tribute Orchestra, Motown Revue'],
                            ['title' => 'Heritage-Rooted Performance', 'body' => 'Winspear Centre (5A1L), cultural showcases'],
                            ['title' => 'Touring & Feature Engagements', 'body' => 'Twilight Time, Alberta Showcase'],
                        ],
                    ],
                    [
                        'type' => 'engagements',
                        'title' => 'Notable Engagements',
                        'items' => [
                            'Twilight Time - Lead Male Vocalist',
                            'Alberta Showcase',
                            'Alvin\'s Jazz Club - Multiple Years',
                            'Ironwood Bar & Grill - Calgary',
                            'Yardbird Suite',
                            'Winspear Centre - Five Artists One Love',
                            'Ray Charles Tribute Orchestra - Calgary & Red Deer',
                            'Motown Revue - Calgary & Red Deer',
                            'Tommy Banks Big Band',
                            'Cosmopolitan Orchestra - Winspear',
                        ],
                    ],
                    [
                        'type' => 'tags',
                        'title' => 'Featured Repertoire',
                        'items' => [
                            'Jazz Standards',
                            'Soul Classics',
                            'Crooner Selections',
                            'Big Band Features',
                            'Ray Charles',
                            'Motown',
                            'Platters / Doo-Wop',
                            'Heritage-Rooted Narrative Pieces',
                        ],
                    ],
                    [
                        'type' => 'testimonials',
                        'title' => 'Testimonials',
                        'items' => [
                            [
                                'source' => 'Charles Austin, ARCT, BMus, ME',
                                'credential' => 'Professor Emeritus, MacEwan University',
                                'quote' => 'Thomas is a gifted performer who is professional and engaging on stage... worthy of serious consideration and merit.',
                            ],
                            [
                                'source' => 'Lonnie H.',
                                'quote' => 'Thomas Alexander brings a level of class and vocal mastery rarely heard today.',
                            ],
                        ],
                    ],
                    [
                        'type' => 'medley',
                        'title' => 'Live Performance Medley',
                        'body' => 'Audio Medley - Thomas Alexander (The Voice)<br>'.$shortLine,
                    ],
                    [
                        'type' => 'booking',
                        'title' => 'Booking & Representation',
                        'body' => '<p>'.$line.'</p><p><strong>'.$bookingLabel.':</strong><br><a href="mailto:'.$email.'">'.$email.'</a></p><p>'.$location.'</p><p>To request the full artist EPK, please contact '.$agency.'.</p>',
                    ],
                ],
                'status' => true,
                'sort_order' => 1,
                'booking_email' => $email,
                'audio_title' => 'Audio Medley - Thomas Alexander (The Voice)',
                'seo_title' => 'Thomas Alexander — The Voice | Full Artist EPK',
                'seo_description' => 'Explore the official Thomas Alexander — The Voice electronic press kit, featuring artist biography, repertoire, notable engagements, testimonials and booking information through '.$agency.'.',
            ],
            [
                'title' => 'CROONERS',
                'slug' => 'crooners',
                'subtitle' => 'A Legacy of Elegance, Heritage, and Timeless Musical Artistry',
                'hero_image' => 'uploads/custom-images/thomas-alex-2025-02-15-07-52-26-1719.jpeg',
                'hero_image_alt' => 'Thomas Alexander performing on stage',
                'gold_feather_image' => 'uploads/website-images/logo-2025-02-14-08-43-44-5421.png',
                'gold_feather_image_alt' => 'Thomas Alexander The Voice logo',
                'overview_content' => '<p>A celebration of timeless vocal artistry, blending classic jazz, swing, and beloved standards with Thomas Alexander\'s signature warmth and stage presence. The show brings audiences back to an era of elegance, melody, and unforgettable songs - delivered with authenticity, charm, and a voice shaped by decades of performance.</p><p>A performance shaped by heritage, mastery, and the timeless elegance of true musical royalty.</p>',
                'sections' => [
                    [
                        'type' => 'tags',
                        'title' => 'Featured Selections',
                        'items' => [
                            'Fly Me to the Moon',
                            'The Way You Look Tonight',
                            'I\'ve Got You Under My Skin',
                            'Beyond the Sea',
                            'Unforgettable',
                            'Misty',
                            'My Funny Valentine',
                            'Moon River',
                            'The Very Thought of You',
                            'Mona Lisa',
                            'What a Wonderful World',
                            'At Last',
                            'Harbor Lights',
                            'Only You',
                            'Twilight Time',
                            'Smoke Gets in Your Eyes',
                        ],
                    ],
                    [
                        'type' => 'testimonials',
                        'title' => 'Testimonials',
                        'items' => [
                            [
                                'source' => 'Charles Austin, ARCT, BMus, ME',
                                'credential' => 'Professor Emeritus, MacEwan University',
                                'quote' => 'Thomas is a gifted performer who is professional and engaging on stage... worthy of serious consideration and merit.',
                            ],
                            [
                                'source' => 'Lonnie H.',
                                'quote' => 'Thomas Alexander brings a level of class and vocal mastery rarely heard today.',
                            ],
                        ],
                    ],
                    [
                        'type' => 'video',
                        'title' => 'Live Performance',
                        'body' => '<p>Official Thomas Alexander performance preview.</p>',
                        'url' => 'https://www.youtube.com/watch?v=stRFQS7qAiU',
                        'video_title' => 'A Change Is Gonna Come - Thomas Alexander The Voice',
                    ],
                    [
                        'type' => 'booking',
                        'title' => 'Booking & Contact',
                        'body' => '<p>'.$line.'</p><p><strong>'.$bookingLabel.':</strong><br><a href="mailto:'.$email.'">'.$email.'</a></p><p>'.$location.'</p><p>Full Crooners EPK available upon request through '.$agency.'.</p>',
                    ],
                ],
                'video_url' => 'https://www.youtube.com/watch?v=stRFQS7qAiU',
                'video_title' => 'A Change Is Gonna Come - Thomas Alexander The Voice',
                'status' => true,
                'sort_order' => 2,
                'booking_email' => $email,
                'seo_title' => 'Thomas Alexander — The Voice | Crooners EPK',
                'seo_description' => 'Explore the Crooners EPK for Thomas Alexander — The Voice, featuring timeless jazz standards, repertoire, testimonials, live performance and booking information.',
            ],
        ];

        foreach ($pages as $page) {
            EpkPage::updateOrCreate(
                ['slug' => $page['slug']],
                $page
            );
        }
    }
}
