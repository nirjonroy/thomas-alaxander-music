-- ==============================================================================
-- THOMAS ALEXANDER — 4 FINAL CLIENT CONTENT UPDATES (5 TARGET RECORDS)
-- Production Database Update Script for Hostinger phpMyAdmin / MySQL
-- Target Database: u712512384_thomas
--
-- STRICT PRODUCTION SAFETY RULES:
-- 1. No invalid columns: Uses `status` (not `is_published`) for `epk_pages`.
--    Does not reference non-existent `title` column on `about_us`.
-- 2. Preserves publication state: DOES NOT modify `status` on any table.
-- 3. No assumed production IDs: Slugs are used as primary predicates.
-- 4. Safe structure:
--    - Part 1: READ-ONLY Preflight Verification & Target Count Check
--    - Part 2: Safe Content UPDATE Statements (5 queries)
--    - Part 3: Read-Only Verification & Affected Content Check
-- ==============================================================================


-- ==============================================================================
-- PART 1: READ-ONLY PREFLIGHT CHECK
-- RUN THIS PART FIRST in phpMyAdmin.
-- STOP AND DO NOT PROCEED IF TARGET ROW COUNTS DO NOT MATCH EXPECTATIONS.
-- ==============================================================================

-- 1.1 Inspect target rows and current status (All columns verified against schema)
-- Expected: Exactly 2 rows ('full-artist' and 'crooners')
SELECT id, slug, title, status, updated_at
FROM `epk_pages`
WHERE `slug` IN ('full-artist', 'crooners');

-- Expected: Exactly 2 rows ('identity' and 'five-feathers-lineage-society')
SELECT id, slug, page_name, status, updated_at
FROM `custom_pages`
WHERE `slug` IN ('identity', 'five-feathers-lineage-society');

-- Expected: Exactly 1 row (id = 1)
SELECT id, status, updated_at
FROM `about_us`
WHERE `id` = 1;

-- 1.2 Verify target row counts
-- Expected: epk_targets = 2, custom_pages_targets = 2, about_us_targets = 1
SELECT
    (SELECT COUNT(*) FROM `epk_pages` WHERE `slug` IN ('full-artist', 'crooners')) AS epk_targets,
    (SELECT COUNT(*) FROM `custom_pages` WHERE `slug` IN ('identity', 'five-feathers-lineage-society')) AS custom_pages_targets,
    (SELECT COUNT(*) FROM `about_us` WHERE `id` = 1) AS about_us_targets;

-- 1.3 Pre-update content status check (Confirms content is not yet applied)
-- Expected before update: ALL COUNTS = 0
SELECT
    (SELECT COUNT(*) FROM `epk_pages` WHERE `slug` IN ('full-artist', 'crooners') AND `sections` LIKE '%unmistakably my own%') AS epk_with_signature,
    (SELECT COUNT(*) FROM `custom_pages` WHERE `slug` = 'identity' AND `description` LIKE '%Copper-Skinned BlackIndigenous%') AS identity_with_copper_skinned,
    (SELECT COUNT(*) FROM `custom_pages` WHERE `slug` = 'five-feathers-lineage-society' AND `description` LIKE '%remembrance made living%') AS society_with_complete_body,
    (SELECT COUNT(*) FROM `about_us` WHERE `id` = 1 AND `about_us` LIKE '%Copper-Skinned BlackIndigenous%') AS about_us_with_copper_skinned;


-- ==============================================================================
-- PART 2: SAFE CONTENT UPDATES (RUN AFTER PREFLIGHT VERIFICATION PASSES)
-- ==============================================================================

-- ------------------------------------------------------------------------------
-- UPDATE 1: Full Artist EPK (/epk/full-artist)
-- - Adds "Stand by Me" and "Just My Imagination" to Featured Repertoire
-- - Adds complete SIGNATURE INTERPRETATIONS before performance formats / medley
-- - Quote ending: "unmistakably my own"
-- - Preserves: existing `status`, hero_image, gold_feather_image, SEO fields
-- ------------------------------------------------------------------------------
UPDATE `epk_pages`
SET `sections` = '[{\"type\":\"performance_lanes\",\"title\":\"Performance Lanes\",\"items\":[{\"title\":\"Jazz & Soul\",\"body\":\"Alvin\\u2019s Jazz Club, Yardbird Suite\"},{\"title\":\"Big Band & Orchestra\",\"body\":\"Tommy Banks Big Band, Cosmopolitan Orchestra\"},{\"title\":\"Tribute Shows\",\"body\":\"Ray Charles Tribute Orchestra, Motown Revue\"},{\"title\":\"Heritage-Rooted Performance\",\"body\":\"Winspear Centre (5A1L), cultural showcases\"},{\"title\":\"Touring & Feature Engagements\",\"body\":\"Twilight Time, Alberta Showcase\"}]},{\"type\":\"engagements\",\"title\":\"Notable Engagements\",\"items\":[\"Twilight Time \\u2014 Lead Male Vocalist\",\"Alberta Showcase\",\"Alvin\\u2019s Jazz Club \\u2014 Multiple Years\",\"Ironwood Bar & Grill \\u2014 Calgary\",\"Yardbird Suite\",\"Winspear Centre \\u2014 Five Artists One Love\",\"Ray Charles Tribute Orchestra \\u2014 Calgary & Red Deer\",\"Motown Revue \\u2014 Calgary & Red Deer\",\"Tommy Banks Big Band\",\"Cosmopolitan Orchestra \\u2014 Winspear\"]},{\"type\":\"tags\",\"title\":\"Featured Repertoire\",\"items\":[\"Jazz Standards\",\"Soul Classics\",\"Crooner Selections\",\"Big Band Features\",\"Ray Charles\",\"Motown\",\"Platters \\/ Doo-Wop\",\"Heritage-Rooted Narrative Pieces\",\"Stand by Me \\u2014 Signature Thomas Alexander Interpretation\",\"Just My Imagination \\u2014 Signature Thomas Alexander Interpretation\"]},{\"type\":\"signature_interpretations\",\"title\":\"Signature Interpretations\",\"body\":\"<p>Thomas Alexander approaches classic repertoire as an interpreter rather than an imitator, reshaping familiar songs through his own phrasing, musical instincts, and lived experience.<\\/p><div class=\\\"signature-song-block\\\"><h4>Stand by Me<\\/h4><p>A deeply soulful original interpretation with a particularly personal history. Thomas Alexander had the opportunity to meet Ben E. King and play him his arrangement of Stand by Me. Thomas remembers King saying that he loved the interpretation and had never thought of doing the song that way.<\\/p><\\/div><div class=\\\"signature-song-block\\\"><h4>Just My Imagination<\\/h4><p>Thomas Alexander brings his own interpretation to this soul classic, allowing the song to live through his distinctive phrasing, emotional approach, and vocal identity rather than reproducing the familiar recording.<\\/p><\\/div><p class=\\\"signature-closing-quote\\\">Together, these interpretations reflect a defining principle of Thomas\\u2019s artistry: honour the song, honour those who made it timeless, and still find something within it that is unmistakably my own.<\\/p>\"},{\"type\":\"testimonials\",\"title\":\"Testimonials\",\"items\":[{\"source\":\"Charles Austin, ARCT, BMus, ME\",\"credential\":\"Professor Emeritus, MacEwan University\",\"quote\":\"Thomas is a gifted performer who is professional and engaging on stage... worthy of serious consideration and merit.\"},{\"source\":\"Lonnie H.\",\"quote\":\"Thomas Alexander brings a level of class and vocal mastery rarely heard today.\"}]},{\"type\":\"medley\",\"title\":\"Live Performance Medley\",\"body\":\"Audio Medley \\u2014 Thomas Alexander (The Voice)<br>Presented by Five Feathers Music Agency\"},{\"type\":\"booking\",\"title\":\"Booking & Representation\",\"body\":\"<p>Five Feathers Music Agency<\\/p><p>Exclusive Representatives for<br>Thomas Alexander \\u2014 The Voice<\\/p><p><a href=\\\"mailto:info@thomasalexanderthevoice.com\\\">info@thomasalexanderthevoice.com<\\/a><\\/p><p>Edmonton, Alberta, Canada<\\/p><p>To request the full downloadable EPK, please contact Five Feathers Music Agency at info@thomasalexanderthevoice.com.<\\/p>\"}]',
    `updated_at` = NOW()
WHERE `slug` = 'full-artist';

-- ------------------------------------------------------------------------------
-- UPDATE 2: Crooners EPK (/epk/crooners)
-- - Adds complete SIGNATURE INTERPRETATIONS before Testimonials
-- - Songs excluded from Featured Selections
-- - Quote ending: "unmistakably my own"
-- - Preserves: existing `status`, hero_image, SEO fields
-- ------------------------------------------------------------------------------
UPDATE `epk_pages`
SET `sections` = '[{\"type\":\"tags\",\"title\":\"Featured Selections\",\"items\":[\"Fly Me to the Moon\",\"The Way You Look Tonight\",\"I\\u2019ve Got You Under My Skin\",\"Beyond the Sea\",\"Unforgettable\",\"Misty\",\"My Funny Valentine\",\"Moon River\",\"The Very Thought of You\",\"Mona Lisa\",\"What a Wonderful World\",\"At Last\",\"Harbor Lights\",\"Only You\",\"Twilight Time\",\"Smoke Gets in Your Eyes\"]},{\"type\":\"signature_interpretations\",\"title\":\"Signature Interpretations\",\"body\":\"<p>Thomas Alexander approaches classic repertoire as an interpreter rather than an imitator, reshaping familiar songs through his own phrasing, musical instincts, and lived experience.<\\/p><div class=\\\"signature-song-block\\\"><h4>Stand by Me<\\/h4><p>A deeply soulful original interpretation with a particularly personal history. Thomas Alexander had the opportunity to meet Ben E. King and play him his arrangement of Stand by Me. Thomas remembers King saying that he loved the interpretation and had never thought of doing the song that way.<\\/p><\\/div><div class=\\\"signature-song-block\\\"><h4>Just My Imagination<\\/h4><p>Thomas Alexander brings his own interpretation to this soul classic, allowing the song to live through his distinctive phrasing, emotional approach, and vocal identity rather than reproducing the familiar recording.<\\/p><\\/div><p class=\\\"signature-closing-quote\\\">Together, these interpretations reflect a defining principle of Thomas\\u2019s artistry: honour the song, honour those who made it timeless, and still find something within it that is unmistakably my own.<\\/p>\"},{\"type\":\"testimonials\",\"title\":\"Testimonials\",\"items\":[{\"source\":\"Charles Austin, ARCT, BMus, ME\",\"credential\":\"Professor Emeritus, MacEwan University\",\"quote\":\"Thomas is a gifted performer who is professional and engaging on stage... worthy of serious consideration and merit.\"},{\"source\":\"Lonnie H.\",\"quote\":\"Thomas Alexander brings a level of class and vocal mastery rarely heard today.\"}]},{\"type\":\"video\",\"title\":\"Live Performance\",\"body\":\"<p>Official Thomas Alexander performance preview.<\\/p>\",\"url\":\"https:\\/\\/www.youtube.com\\/watch?v=stRFQS7qAiU\",\"video_title\":\"A Change Is Gonna Come \\u2014 Thomas Alexander The Voice\"},{\"type\":\"booking\",\"title\":\"Booking & Contact\",\"body\":\"<p>Five Feathers Music Agency<\\/p><p>Exclusive Representatives for Thomas Alexander \\u2014 The Voice<\\/p><p><strong>Booking &amp; Inquiries:<\\/strong><br><a href=\\\"mailto:info@thomasalexanderthevoice.com\\\">info@thomasalexanderthevoice.com<\\/a><\\/p><p>Edmonton, Alberta, Canada<\\/p><p>Full Crooners EPK available upon request through Five Feathers Music Agency.<\\/p>\"}]',
    `updated_at` = NOW()
WHERE `slug` = 'crooners';

-- ------------------------------------------------------------------------------
-- UPDATE 3: Identity (/identity)
-- - Full About Thomas replacement text
-- - Restores "Copper-Skinned BlackIndigenous" in all 4 approved locations
-- - Preserves: Dual-Identity link, Professional Highlights, Contact info
-- - Strips: all editor notes
-- - Preserves: existing `status` (publication state)
-- ------------------------------------------------------------------------------
UPDATE `custom_pages`
SET `description` = '<div class=\"identity-lead-block\">\n    <h3>Thomas Alexander &mdash; The Voice</h3>\n    <p class=\"identity-subtitle-bar\">Copper-Skinned BlackIndigenous Canadian vocalist, performer, heritage artist, and recognized Legend of the Edmonton music scene.</p>\n    <p class=\"identity-credentials-bar\">Vocalist &bull; Heritage Artist &bull; Executive Director &bull; Chief &amp; Elder of the Five Feathers Lineage Society</p>\n</div>\n\n<div class=\"identity-section-block\">\n    <h4>Short Artist Bio</h4>\n    <p>Thomas Alexander &mdash; The Voice is a nationally recognized Copper-Skinned BlackIndigenous Canadian vocalist, performer, and executive artist whose work bridges performance, cultural identity, and organizational leadership. Recognized as one of the Legends of the Edmonton music scene, he brings decades of artistic excellence, ceremonial grounding, and executive clarity to stages across Canada and abroad.</p>\n</div>\n\n<div class=\"identity-section-block\">\n    <h4>Full Artist Biography</h4>\n    <p>Thomas Alexander &mdash; The Voice is a Copper-Skinned BlackIndigenous Canadian vocalist, heritage artist, and executive director whose artistic life is rooted in performance, lineage, and legacy stewardship. Recognized as one of the Legends of the Edmonton music scene, he has shaped Western Canada’s musical landscape for decades through his expressive vocal presence, interpretive depth, and multi-role stage work.</p>\n    <p>His performance history includes national stages across Canada and international appearances in Tokyo, Japan, reflecting a career that bridges local legacy and global reach. His performances are marked by clarity, emotional intelligence, and a grounded presence that resonates across audiences and communities.</p>\n    <p>Thomas’s artistic identity is inseparable from his cultural grounding. As Chief &amp; Elder of the Five Feathers Lineage Society, he carries forward the teachings, memory, and ancestral continuity of his Creek, Cherokee, and Yamassee lines. His work as a performer is informed by this lineage. Every note, every chart, and every interpretation becomes part of a living archive that honours the legacy of the Five Civilized Tribes and the Yamassee, whose roots and seed continue through the Creek and Seminole Nations.</p>\n    <p>Beyond performance, Thomas serves as Founder &amp; Executive Director of Five Feathers Publishing Company, where he oversees the Administrative &amp; Booking Division, the operational heart of his artistic and cultural work. His leadership is defined by professionalism, reliability, strong communication, and the ability to collaborate with presenters, theatres, and institutions across Canada.</p>\n</div>\n\n<div class=\"identity-section-block dual-identity-callout\">\n    <h4>Dual-Identity Integration</h4>\n    <p>Thomas carries a dual identity as both a ceremonial leader and an executive artist. His work moves fluidly between cultural stewardship and professional performance, forming a unified artistic-ceremonial presence.</p>\n    <p><a class=\"btn living-btn living-btn-outline\" href=\"/living-archive#dual-identity\">Read the Dual-Identity Page &rarr;</a></p>\n</div>\n\n<div class=\"identity-section-block\">\n    <h4>Professional Highlights</h4>\n    <ul>\n        <li>National performances across theatres, festivals, and cultural institutions</li>\n        <li>International performances including Tokyo, Japan</li>\n        <li>Recognized Legend of the Edmonton music scene</li>\n        <li>Copper-Skinned BlackIndigenous Canadian vocalist and heritage artist</li>\n        <li>Founder &amp; Executive Director of Five Feathers Publishing Company</li>\n        <li>Chief &amp; Elder of the Five Feathers Lineage Society</li>\n        <li>Presenter-ready artistic brand with strong communication and leadership capacity</li>\n        <li>Multi-role stage performer with advanced interpretive and vocal skills</li>\n    </ul>\n</div>\n\n<div class=\"identity-section-block identity-contact-box\">\n    <h4>Contact &amp; Representation</h4>\n    <p><strong>Email:</strong> <a href=\"mailto:info@thomasalexanderthevoice.com\">info@thomasalexanderthevoice.com</a><br>\n    <strong>Phone:</strong> <a href=\"tel:+18254400149\">(825) 440-0149</a><br>\n    <strong>Administrative &amp; Booking Division:</strong> Five Feathers Publishing Company<br>\n    <strong>Governing Authority:</strong> Five Feathers Lineage Society</p>\n</div>',
    `updated_at` = NOW()
WHERE `slug` = 'identity';

-- ------------------------------------------------------------------------------
-- UPDATE 4: Five Feathers Lineage Society (/five-feathers-lineage-society)
-- - Complete 8-section replacement body:
--     1. Founding Purpose
--     2. The Living Archive (with 3 bullets)
--     3. The Five Feathers (open feather preserved)
--     4. Memory, Truth & Responsibility (3 principles)
--     5. Cultural Stewardship
--     6. Companions Along the Journey
--     7. The Breathline
--     8. Our Commitment (6 bullets, closing declaration, tagline)
-- - Strips: all editor notes
-- - Preserves: existing `status` (publication state)
-- ------------------------------------------------------------------------------
UPDATE `custom_pages`
SET `page_name` = 'The Five Feathers Lineage Society',
    `description` = '<div class=\"society-lead-block\">\n    <h3>The Five Feathers Lineage Society</h3>\n    <p class=\"society-subtitle\">A lineage-rooted, memory-based cultural society grounded in ancestral continuity, responsibility, truth, and remembrance.</p>\n</div>\n\n<div class=\"society-section-block\">\n    <h4>Founding Purpose</h4>\n    <p>The Five Feathers Lineage Society exists to protect, preserve, and carry forward the cultural, historical, familial, and ceremonial memory of the Creek, Cherokee, and Yamassee lines within Thomas Alexander’s family lineage.</p>\n    <p>The Society is a living structure of remembrance. It carries teachings, stories, lived experience, family history, ancestral memory, and the responsibilities that accompany them so they are not forgotten, erased, or lost to the generations that follow.</p>\n    <p>Our people and our ancestral memory predate the colonial Indian Act. Our purpose is not defined by that Act.</p>\n    <p>The Five Feathers Lineage Society does not look to colonial legislation to create the ancestors who came before us, the memories carried through our families, or the responsibility we hold toward those who will come after us.</p>\n    <p>The Society stands upon lineage, memory, continuity, stewardship, truth-telling, and intergenerational responsibility.</p>\n    <p>We do not invent what was not there.</p>\n    <p>We do not erase what was.</p>\n    <p>And where the historical record remains unfinished, we continue the search with care, honesty, and respect.</p>\n</div>\n\n<div class=\"society-section-block\">\n    <h4>The Living Archive</h4>\n    <p>At the heart of the Society is the Living Archive.</p>\n    <p>A Living Archive is not simply a collection of objects or documents held in one place. It is memory carried forward through people, family, story, music, teaching, imagery, lived experience, research, and responsibility.</p>\n    <p>A Living Archive is not a place, but a path.</p>\n    <p>What is carried forward is not kept for ourselves alone. It is preserved so that children, grandchildren, and future generations may know that there was a path behind them, that others walked it before them, and that something was deliberately left for them to carry farther.</p>\n    <p>The Living Archive therefore reaches backward and forward at the same time:</p>\n    <ul>\n        <li>Honouring those who came before.</li>\n        <li>Serving those who are here now.</li>\n        <li>Leaving a path for those still to come.</li>\n    </ul>\n</div>\n\n<div class=\"society-section-block\">\n    <h4>The Five Feathers</h4>\n    <p>The Five Feathers are not merely a visual identity.</p>\n    <p>They represent the continuing work of remembrance, lineage, responsibility, discovery, and the carrying forward of ancestral memory.</p>\n    <p>Within the Living Crest, the feathers form part of a larger story. They remind us that lineage is not simply something inherited. It carries responsibility.</p>\n    <p>One feather remains open for future ancestral confirmation.</p>\n    <p>Its openness is intentional.</p>\n    <p>It acknowledges that responsible lineage work must leave room for discovery, evidence, family memory, and truths that may not yet have been recovered.</p>\n    <p>We honour what we know.</p>\n    <p>We continue searching for what remains hidden.</p>\n    <p>And we do not fill an unanswered space merely because we desire an answer.</p>\n</div>\n\n<div class=\"society-section-block\">\n    <h4>Memory, Truth &amp; Responsibility</h4>\n    <p>The Society\'s work is grounded in a simple principle:</p>\n    <ul>\n        <li>Tell the truth without spite.</li>\n        <li>Do not invent what was not there.</li>\n        <li>Do not erase what was.</li>\n    </ul>\n    <p>Our history contains achievement, beauty, survival, displacement, exclusion, family memory, unanswered questions, and truths that were not always preserved within official records.</p>\n    <p>The work of remembrance requires us to carry all of it.</p>\n    <p>Memory does not replace historical research, and historical documentation does not erase what families carried through lived experience. The Living Archive makes room for both while distinguishing carefully between what is remembered, what is documented, and what remains to be discovered.</p>\n</div>\n\n<div class=\"society-section-block\">\n    <h4>Cultural Stewardship</h4>\n    <p>The Five Feathers Lineage Society understands stewardship as service rather than possession.</p>\n    <p>Stories are carried so they can be remembered.</p>\n    <p>Teachings are carried so they can continue.</p>\n    <p>History is preserved so future generations have somewhere to begin.</p>\n    <p>Leadership carries responsibility to the people.</p>\n    <p>And ancestral memory carries an obligation to those who have not yet arrived.</p>\n    <p>This principle extends throughout the Society\'s work in heritage, music, storytelling, education, ceremonial expression, family history, and the continuing development of the Living Archive.</p>\n</div>\n\n<div class=\"society-section-block\">\n    <h4>Companions Along the Journey</h4>\n    <p>Within the Living Archive, the natural world also carries reminders.</p>\n    <p>The animal does not create the lesson.</p>\n    <p>The journey has already begun teaching it.</p>\n    <p>The companion may help us recognize what is already there and remind us of what we are meant to remember.</p>\n    <p>Water carries memory onward.</p>\n    <p>Wind carries seed.</p>\n    <p>Rest prepares life for what comes next.</p>\n    <p>And even devastated ground can become abundant with life again.</p>\n    <p>These teachings remind us that remembrance is not static.</p>\n    <p>It is remembrance made living.</p>\n</div>\n\n<div class=\"society-section-block\">\n    <h4>The Breathline</h4>\n    <p>The Breathline is the continuity carried from generation to generation through memory, voice, story, teaching, responsibility, and lived experience.</p>\n    <p>It is not confined to a document.</p>\n    <p>It is heard in the stories families tell.</p>\n    <p>It lives in music and voice.</p>\n    <p>It appears in what one generation teaches another.</p>\n    <p>And it continues whenever someone accepts the responsibility to carry something meaningful forward.</p>\n</div>\n\n<div class=\"society-section-block society-commitment-block\">\n    <h4>Our Commitment</h4>\n    <p>The Five Feathers Lineage Society exists so that what has been carried this far does not disappear here.</p>\n    <ul>\n        <li>We will protect the memories entrusted to us.</li>\n        <li>We will research what remains unanswered.</li>\n        <li>We will distinguish memory from documentation without dishonouring either.</li>\n        <li>We will tell difficult truths without revenge.</li>\n        <li>We will make room for future discovery.</li>\n        <li>And we will leave something worthy for those who walk after us.</li>\n    </ul>\n    <p class=\"society-closing-statement\"><strong>We were never erased.<br>We were replanted.</strong></p>\n    <p class=\"society-closing-tagline\"><em>This is remembrance made living.</em></p>\n</div>',
    `updated_at` = NOW()
WHERE `slug` = 'five-feathers-lineage-society';

-- ------------------------------------------------------------------------------
-- UPDATE 5: About Us Table Synchronization (/about-thomas-alexander)
-- - Keeps the legacy /about-thomas-alexander route in sync with /identity
-- - Preserves: existing `status`, `banner_image`, `video_background`, `image_two`
-- ------------------------------------------------------------------------------
UPDATE `about_us`
SET `about_us` = '<div class=\"identity-lead-block\">\n    <h3>Thomas Alexander &mdash; The Voice</h3>\n    <p class=\"identity-subtitle-bar\">Copper-Skinned BlackIndigenous Canadian vocalist, performer, heritage artist, and recognized Legend of the Edmonton music scene.</p>\n    <p class=\"identity-credentials-bar\">Vocalist &bull; Heritage Artist &bull; Executive Director &bull; Chief &amp; Elder of the Five Feathers Lineage Society</p>\n</div>\n\n<div class=\"identity-section-block\">\n    <h4>Short Artist Bio</h4>\n    <p>Thomas Alexander &mdash; The Voice is a nationally recognized Copper-Skinned BlackIndigenous Canadian vocalist, performer, and executive artist whose work bridges performance, cultural identity, and organizational leadership. Recognized as one of the Legends of the Edmonton music scene, he brings decades of artistic excellence, ceremonial grounding, and executive clarity to stages across Canada and abroad.</p>\n</div>\n\n<div class=\"identity-section-block\">\n    <h4>Full Artist Biography</h4>\n    <p>Thomas Alexander &mdash; The Voice is a Copper-Skinned BlackIndigenous Canadian vocalist, heritage artist, and executive director whose artistic life is rooted in performance, lineage, and legacy stewardship. Recognized as one of the Legends of the Edmonton music scene, he has shaped Western Canada’s musical landscape for decades through his expressive vocal presence, interpretive depth, and multi-role stage work.</p>\n    <p>His performance history includes national stages across Canada and international appearances in Tokyo, Japan, reflecting a career that bridges local legacy and global reach. His performances are marked by clarity, emotional intelligence, and a grounded presence that resonates across audiences and communities.</p>\n    <p>Thomas’s artistic identity is inseparable from his cultural grounding. As Chief &amp; Elder of the Five Feathers Lineage Society, he carries forward the teachings, memory, and ancestral continuity of his Creek, Cherokee, and Yamassee lines. His work as a performer is informed by this lineage. Every note, every chart, and every interpretation becomes part of a living archive that honours the legacy of the Five Civilized Tribes and the Yamassee, whose roots and seed continue through the Creek and Seminole Nations.</p>\n    <p>Beyond performance, Thomas serves as Founder &amp; Executive Director of Five Feathers Publishing Company, where he oversees the Administrative &amp; Booking Division, the operational heart of his artistic and cultural work. His leadership is defined by professionalism, reliability, strong communication, and the ability to collaborate with presenters, theatres, and institutions across Canada.</p>\n</div>\n\n<div class=\"identity-section-block dual-identity-callout\">\n    <h4>Dual-Identity Integration</h4>\n    <p>Thomas carries a dual identity as both a ceremonial leader and an executive artist. His work moves fluidly between cultural stewardship and professional performance, forming a unified artistic-ceremonial presence.</p>\n    <p><a class=\"btn living-btn living-btn-outline\" href=\"/living-archive#dual-identity\">Read the Dual-Identity Page &rarr;</a></p>\n</div>\n\n<div class=\"identity-section-block\">\n    <h4>Professional Highlights</h4>\n    <ul>\n        <li>National performances across theatres, festivals, and cultural institutions</li>\n        <li>International performances including Tokyo, Japan</li>\n        <li>Recognized Legend of the Edmonton music scene</li>\n        <li>Copper-Skinned BlackIndigenous Canadian vocalist and heritage artist</li>\n        <li>Founder &amp; Executive Director of Five Feathers Publishing Company</li>\n        <li>Chief &amp; Elder of the Five Feathers Lineage Society</li>\n        <li>Presenter-ready artistic brand with strong communication and leadership capacity</li>\n        <li>Multi-role stage performer with advanced interpretive and vocal skills</li>\n    </ul>\n</div>\n\n<div class=\"identity-section-block identity-contact-box\">\n    <h4>Contact &amp; Representation</h4>\n    <p><strong>Email:</strong> <a href=\"mailto:info@thomasalexanderthevoice.com\">info@thomasalexanderthevoice.com</a><br>\n    <strong>Phone:</strong> <a href=\"tel:+18254400149\">(825) 440-0149</a><br>\n    <strong>Administrative &amp; Booking Division:</strong> Five Feathers Publishing Company<br>\n    <strong>Governing Authority:</strong> Five Feathers Lineage Society</p>\n</div>',
    `updated_at` = NOW()
WHERE `id` = 1;


-- ==============================================================================
-- PART 3: POST-UPDATE VERIFICATION
-- RUN THIS PART TO CONFIRM THAT EXACTLY 5 RECORDS WERE UPDATED AND CONTENT MATCHES
-- ==============================================================================

-- 3.1 Inspect updated timestamps and preserved status
SELECT id, slug, title, status, updated_at
FROM `epk_pages`
WHERE `slug` IN ('full-artist', 'crooners');

SELECT id, slug, page_name, status, updated_at
FROM `custom_pages`
WHERE `slug` IN ('identity', 'five-feathers-lineage-society');

SELECT id, status, updated_at
FROM `about_us`
WHERE `id` = 1;

-- 3.2 Verify content is now present
-- Expected after update:
-- epk_with_signature = 2
-- identity_with_copper_skinned = 1
-- society_with_complete_body = 1
-- about_us_with_copper_skinned = 1
SELECT
    (SELECT COUNT(*) FROM `epk_pages` WHERE `slug` IN ('full-artist', 'crooners') AND `sections` LIKE '%unmistakably my own%') AS epk_with_signature,
    (SELECT COUNT(*) FROM `custom_pages` WHERE `slug` = 'identity' AND `description` LIKE '%Copper-Skinned BlackIndigenous%') AS identity_with_copper_skinned,
    (SELECT COUNT(*) FROM `custom_pages` WHERE `slug` = 'five-feathers-lineage-society' AND `description` LIKE '%remembrance made living%') AS society_with_complete_body,
    (SELECT COUNT(*) FROM `about_us` WHERE `id` = 1 AND `about_us` LIKE '%Copper-Skinned BlackIndigenous%') AS about_us_with_copper_skinned;
