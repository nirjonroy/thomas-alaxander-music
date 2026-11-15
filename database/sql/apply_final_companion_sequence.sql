-- Final Companion sequence safeguard.
-- Run only after deploying the approved Laravel seeder and source file:
-- php artisan db:seed --class=Database\\Seeders\\ApprovedCompanionContentSeeder --force
-- This SQL intentionally does not contain alternate prose; the seeder parses
-- docs/client-handoff/Thomas_Final_Companion_Teachings.txt verbatim.

START TRANSACTION;

SET @companion_sequence_offset := (SELECT COALESCE(MAX(sequence), 0) + 100 FROM journey_companions);

UPDATE journey_companions
SET sequence = sequence + @companion_sequence_offset
WHERE slug IN ('wolf', 'hawk', 'bear', 'raven', 'owl', 'panther', 'deer', 'eagle', 'fox', 'lynx', 'otter');

UPDATE journey_companions
SET sequence = CASE slug
    WHEN 'wolf' THEN 1 WHEN 'hawk' THEN 2 WHEN 'bear' THEN 3
    WHEN 'raven' THEN 4 WHEN 'owl' THEN 5 WHEN 'panther' THEN 6
    WHEN 'deer' THEN 7 WHEN 'eagle' THEN 8 WHEN 'fox' THEN 9
    WHEN 'lynx' THEN 10 WHEN 'otter' THEN 11
END,
name = CASE slug
    WHEN 'wolf' THEN 'Wolf' WHEN 'hawk' THEN 'Hawk' WHEN 'bear' THEN 'Bear'
    WHEN 'raven' THEN 'Raven' WHEN 'owl' THEN 'Owl' WHEN 'panther' THEN 'Panther'
    WHEN 'deer' THEN 'Deer' WHEN 'eagle' THEN 'Eagle' WHEN 'fox' THEN 'Fox'
    WHEN 'lynx' THEN 'Lynx' WHEN 'otter' THEN 'Otter'
END,
is_active = 0
WHERE slug IN ('wolf', 'hawk', 'bear', 'raven', 'owl', 'panther', 'deer', 'eagle', 'fox', 'lynx', 'otter');

UPDATE companion_page_settings SET is_active = 0;

COMMIT;
