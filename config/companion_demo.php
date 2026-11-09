<?php

return [
    /*
     * Demo content is intentionally opt-in. Set both values in a local-only
     * environment before running the seeder or rollback command.
     */
    'enabled' => env('COMPANION_JOURNEY_DEMO_ENABLED', false),
    'approved_database' => env('COMPANION_JOURNEY_DEMO_DATABASE'),
    'snapshot_path' => env('COMPANION_JOURNEY_DEMO_SNAPSHOT', 'companion-journey-demo/snapshot.json'),
];
