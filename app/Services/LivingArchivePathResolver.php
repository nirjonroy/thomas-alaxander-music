<?php

namespace App\Services;

use App\Models\LivingArchiveEntry;
use Illuminate\Support\Collection;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class LivingArchivePathResolver
{
    private Collection $entriesById;

    public function __construct()
    {
        $this->entriesById = collect();
    }

    public function resolve(string $path): array
    {
        $segments = $this->segments($path);

        if (empty($segments)) {
            throw new NotFoundHttpException();
        }

        $entries = LivingArchiveEntry::published()
            ->ordered()
            ->get([
                'id',
                'parent_id',
                'title',
                'slug',
                'section_label',
                'teaser',
                'featured_image',
                'featured_image_alt',
                'page_type',
                'sort_order',
                'status',
                'published_at',
                'updated_at',
                'created_at',
            ]);
        $this->entriesById = $entries->keyBy('id');
        $ancestors = collect();
        $parentId = null;
        $current = null;

        foreach ($segments as $segment) {
            $current = $entries->first(function (LivingArchiveEntry $entry) use ($segment, $parentId) {
                return $entry->slug === $segment && $entry->parent_id === $parentId;
            });

            if (! $current) {
                throw new NotFoundHttpException();
            }

            $ancestors->push($current);
            $parentId = $current->id;
        }

        $archivePage = LivingArchiveEntry::published()->findOrFail($current->id);
        $ancestors = $ancestors->slice(0, -1)->values();
        $directChildren = $this->childrenOf($entries, $current->id);
        $siblings = $this->childrenOf($entries, $current->parent_id);
        $rootPages = $this->childrenOf($entries, null);
        $relatedPages = $this->relatedPages($entries, $current, $ancestors, $siblings);

        $currentIndex = $siblings->search(fn (LivingArchiveEntry $entry) => $entry->id === $current->id);

        return [
            'archivePage' => $archivePage,
            'ancestors' => $ancestors,
            'directChildren' => $directChildren,
            'relatedPages' => $relatedPages,
            'rootPages' => $rootPages,
            'previousPage' => $currentIndex !== false && $currentIndex > 0 ? $siblings->get($currentIndex - 1) : null,
            'nextPage' => $currentIndex !== false ? $siblings->get($currentIndex + 1) : null,
        ];
    }

    public function pathFor(LivingArchiveEntry $entry): string
    {
        $segments = collect([$entry->slug]);
        $parent = $this->parentFor($entry);

        while ($parent) {
            $segments->prepend($parent->slug);
            $parent = $this->parentFor($parent);
        }

        return $segments->implode('/');
    }

    private function segments(string $path): array
    {
        return array_values(array_filter(explode('/', trim($path, '/')), fn ($segment) => $segment !== ''));
    }

    private function childrenOf(Collection $entries, ?int $parentId): Collection
    {
        return $entries
            ->filter(fn (LivingArchiveEntry $entry) => $entry->parent_id === $parentId)
            ->values();
    }

    private function parentFor(LivingArchiveEntry $entry): ?LivingArchiveEntry
    {
        if (! $entry->parent_id) {
            return null;
        }

        return $this->entriesById->get($entry->parent_id) ?: $entry->parent;
    }

    private function relatedPages(Collection $entries, LivingArchiveEntry $current, Collection $ancestors, Collection $siblings): Collection
    {
        $related = $siblings
            ->reject(fn (LivingArchiveEntry $entry) => $entry->id === $current->id)
            ->values();

        $root = $ancestors->first() ?: $current;

        if ($root->slug === 'heritage') {
            $heritageChildren = $this->childrenOf($entries, $root->id)
                ->reject(fn (LivingArchiveEntry $entry) => $entry->id === $current->id)
                ->values();

            $related = $related
                ->merge($heritageChildren)
                ->unique('id')
                ->values();
        }

        return $related->take(6);
    }
}
