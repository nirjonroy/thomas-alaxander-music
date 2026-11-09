<?php

namespace App\Services;

use App\Models\JourneyCompanion;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use LogicException;

class CompanionJourneyDemoService
{
    public const PREFIX = '[DEMO CONTENT — NOT CLIENT APPROVED]';

    public function seed(): array
    {
        $this->assertSafeEnvironment();

        $companions = JourneyCompanion::ordered()->get();
        $this->assertCanonicalCompanions($companions);

        if ($this->snapshotExists()) {
            $this->assertSnapshotMatchesCurrentDatabase($this->readSnapshot());

            return ['seeded' => 0, 'preserved' => $companions->count() * 2, 'snapshot' => $this->snapshotPath()];
        }

        $demoContent = $this->demoContent();
        $snapshot = [
            'version' => 1,
            'database' => DB::connection()->getDatabaseName(),
            'created_at' => now()->toIso8601String(),
            'records' => [],
        ];

        foreach ($companions as $companion) {
            $demo = $demoContent[$companion->slug];
            $snapshot['records'][$companion->id] = [
                'id' => $companion->id,
                'slug' => $companion->slug,
                'fields' => [
                    'expanded_teaching' => [
                        'original' => $companion->expanded_teaching,
                        'generated' => blank($companion->expanded_teaching) ? $demo['expanded_teaching'] : null,
                    ],
                    'remember_teaching' => [
                        'original' => $companion->remember_teaching,
                        'generated' => blank($companion->remember_teaching) ? $demo['remember_teaching'] : null,
                    ],
                ],
            ];
        }

        // Save restoration data before altering any companion record.
        $this->writeSnapshot($snapshot);

        $seeded = 0;
        $preserved = 0;

        DB::transaction(function () use ($companions, $snapshot, &$seeded, &$preserved): void {
            foreach ($companions as $companion) {
                $updates = [];

                foreach ($snapshot['records'][$companion->id]['fields'] as $field => $values) {
                    if ($values['generated'] === null) {
                        $preserved++;
                        continue;
                    }

                    $updates[$field] = $values['generated'];
                    $seeded++;
                }

                if ($updates !== []) {
                    $companion->update($updates);
                }
            }
        });

        return ['seeded' => $seeded, 'preserved' => $preserved, 'snapshot' => $this->snapshotPath()];
    }

    public function rollback(): array
    {
        $this->assertSafeEnvironment();

        if (! $this->snapshotExists()) {
            throw new LogicException('No Companion Journey demo snapshot was found. Nothing was changed.');
        }

        $snapshot = $this->readSnapshot();
        $this->assertSnapshotMatchesCurrentDatabase($snapshot);

        $restored = 0;
        $skipped = 0;

        DB::transaction(function () use ($snapshot, &$restored, &$skipped): void {
            foreach ($snapshot['records'] as $record) {
                $companion = JourneyCompanion::find($record['id']);

                if (! $companion || $companion->slug !== $record['slug']) {
                    $skipped += count($record['fields']);
                    continue;
                }

                $updates = [];
                foreach ($record['fields'] as $field => $values) {
                    if ($values['generated'] === null) {
                        continue;
                    }

                    if ($companion->{$field} === $values['generated']) {
                        $updates[$field] = $values['original'];
                        $restored++;
                    } elseif ($companion->{$field} !== $values['original']) {
                        // Keep administrator edits made after the demo seeding run.
                        $skipped++;
                    }
                }

                if ($updates !== []) {
                    $companion->update($updates);
                }
            }
        });

        if ($skipped === 0) {
            Storage::disk('local')->delete($this->snapshotPath());
        }

        return ['restored' => $restored, 'skipped' => $skipped, 'snapshot' => $this->snapshotPath()];
    }

    public function assertSafeEnvironment(): void
    {
        if (! app()->environment(['local', 'testing'])) {
            throw new LogicException('Companion Journey demo content may only run in local or testing environments.');
        }

        if (! filter_var(config('companion_demo.enabled'), FILTER_VALIDATE_BOOL)) {
            throw new LogicException('Set COMPANION_JOURNEY_DEMO_ENABLED=true before using Companion Journey demo content.');
        }

        $approvedDatabase = (string) config('companion_demo.approved_database');
        $currentDatabase = (string) DB::connection()->getDatabaseName();
        $connection = config('database.connections.' . config('database.default'));
        $host = (string) ($connection['host'] ?? '');

        if ($approvedDatabase === '' || $approvedDatabase !== $currentDatabase) {
            throw new LogicException('The active database is not the explicitly approved Companion Journey demo database.');
        }

        if ($host !== '' && ! in_array($host, ['127.0.0.1', 'localhost', '::1'], true)) {
            throw new LogicException('Companion Journey demo content requires a local database host.');
        }
    }

    private function assertCanonicalCompanions(Collection $companions): void
    {
        if ($companions->count() !== count(JourneyCompanion::CANONICAL_ORDER)) {
            throw new LogicException('The canonical Companion records are incomplete. Demo content was not applied.');
        }

        foreach ($companions as $index => $companion) {
            $sequence = $index + 1;
            if ((int) $companion->sequence !== $sequence
                || $companion->name !== JourneyCompanion::CANONICAL_ORDER[$sequence]) {
                throw new LogicException('The canonical Companion sequence is invalid. Demo content was not applied.');
            }
        }
    }

    private function snapshotPath(): string
    {
        return (string) config('companion_demo.snapshot_path');
    }

    private function snapshotExists(): bool
    {
        return Storage::disk('local')->exists($this->snapshotPath());
    }

    private function readSnapshot(): array
    {
        $snapshot = json_decode(Storage::disk('local')->get($this->snapshotPath()), true);

        if (! is_array($snapshot) || ! isset($snapshot['records'], $snapshot['database'])) {
            throw new LogicException('The Companion Journey demo snapshot is invalid. No changes were made.');
        }

        return $snapshot;
    }

    private function writeSnapshot(array $snapshot): void
    {
        Storage::disk('local')->put(
            $this->snapshotPath(),
            json_encode($snapshot, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_THROW_ON_ERROR)
        );
    }

    private function assertSnapshotMatchesCurrentDatabase(array $snapshot): void
    {
        if (($snapshot['database'] ?? null) !== DB::connection()->getDatabaseName()) {
            throw new LogicException('The demo snapshot belongs to a different database. No changes were made.');
        }
    }

    private function demoContent(): array
    {
        $prefix = self::PREFIX;

        return [
            'wolf' => $this->passages($prefix, 'This sample layout text explores leadership as a practice of staying accountable to the people who rely on one another. It is written only to test paragraph rhythm, image balance, and reading length.', 'A second sample paragraph gives the editorial column room to breathe beside the Wolf painting while keeping the focus on service, protection, and responsibility.', 'Remember that leadership is measured by care, follow-through, and the space made for others to stand securely.'),
            'hawk' => $this->passages($prefix, 'This placeholder considers the value of pausing long enough to see the wider pattern before choosing a direction. It is demonstration copy for the preview, not a client-approved teaching.', 'The second paragraph tests a measured reading pace around vision, discernment, foresight, and perspective without making historical or ceremonial claims.', 'Remember to look again before acting; a wider view can reveal a more responsible next step.'),
            'raven' => $this->passages($prefix, 'This sample passage uses memory and voice as editorial themes: what is learned can be carried forward when it is named clearly and shared with care.', 'Its purpose is to test how a longer text block sits with the Raven artwork and the surrounding archive typography.', 'Remember that careful listening, clear language, and thoughtful problem solving can help useful knowledge travel onward.'),
            'owl' => $this->passages($prefix, 'This preview copy gives reflection and discernment a quiet pace. It is intentionally fictional scaffolding for layout review and must be replaced with approved client material.', 'A second paragraph creates room for the page to demonstrate comfortable long-form reading beside the original painting.', 'Remember that listening fully can change what becomes visible, and that truth benefits from patience.'),
            'eagle' => $this->passages($prefix, 'This demonstration text treats perspective as a design theme: distance can help a person notice relationships, responsibilities, and consequences that are hard to see up close.', 'It exists solely to exercise the editorial presentation for the Eagle chapter and is not approved cultural content.', 'Remember to meet what is entrusted to you with reverence, attention, and care.'),
            'panther' => $this->passages($prefix, 'This sample explores patience and presence as practical qualities. It gives the Panther chapter enough length to validate the desktop two-column composition and the mobile reading flow.', 'The wording is deliberately general so it cannot be mistaken for a traditional or client-authored teaching.', 'Remember that not every moment asks for speed; sometimes the clearest action follows careful attention.'),
            'bear' => $this->passages($prefix, 'This layout copy considers strength as a form of stewardship: steadiness can make protection and care more visible in ordinary decisions.', 'The second paragraph provides realistic content density while preserving clear separation between sample text and approved archive material.', 'Remember that strength can be calm, protective, and responsive to the needs around it.'),
            'lynx' => $this->passages($prefix, 'This preview passage tests a quieter editorial voice around observation, solitude, recognition, and inner knowing. It is generated display copy only.', 'It also ensures the Lynx chapter has enough body text to assess rhythm, line length, and visual balance across breakpoints.', 'Remember to make room for observation; what is recognized slowly can still matter deeply.'),
            'deer' => $this->passages($prefix, 'This sample uses continuity and stewardship as broad design prompts, showing how a two-paragraph teaching will read beside the Deer artwork.', 'It remains intentionally non-authoritative and should be replaced as soon as approved text is provided.', 'Remember that legacy is shaped through the care given to those who come after us.'),
            'fox' => $this->passages($prefix, 'This temporary passage explores adaptability and awareness in neutral language. Its only job is to support review of the cardless editorial layout and responsive spacing.', 'A second paragraph tests a natural reading cadence without presenting generated material as heritage instruction.', 'Remember that another way forward may appear when attention remains flexible and respectful.'),
            'otter' => $this->passages($prefix, 'This demonstration copy brings the sequence to a close with broad themes of family, resilience, renewal, protection, and joy. It is not client-approved content.', 'The additional paragraph supplies enough length to inspect the final artwork chapter before the closing archive sections.', 'Remember that renewal can include connection, care, play, and the resilience to begin again.'),
        ];
    }

    private function passages(string $prefix, string $first, string $second, string $remember): array
    {
        return [
            'expanded_teaching' => $prefix . "\n\n" . $first . "\n\n" . $second,
            'remember_teaching' => $prefix . "\n\n" . $remember,
        ];
    }
}
