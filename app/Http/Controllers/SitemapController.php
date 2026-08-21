<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use App\Models\EpkPage;
use App\Models\Event;
use App\Models\LivingArchiveEntry;
use App\Models\Product;
use Illuminate\Http\Response;

class SitemapController extends Controller
{
    public function index(): Response
    {
        $staticUrls = [
            [
                'loc' => route('front.home'),
                'lastmod' => now()->toAtomString(),
                'changefreq' => 'daily',
                'priority' => '1.0',
            ],
            [
                'loc' => route('front.home.about'),
                'lastmod' => now()->toAtomString(),
                'changefreq' => 'monthly',
                'priority' => '0.6',
            ],
            [
                'loc' => route('front.blog'),
                'lastmod' => now()->toAtomString(),
                'changefreq' => 'weekly',
                'priority' => '0.7',
            ],
            [
                'loc' => route('front.events'),
                'lastmod' => now()->toAtomString(),
                'changefreq' => 'weekly',
                'priority' => '0.7',
            ],
            [
                'loc' => route('front.contact_us'),
                'lastmod' => now()->toAtomString(),
                'changefreq' => 'monthly',
                'priority' => '0.6',
            ],
            [
                'loc' => route('front.home.living-archive'),
                'lastmod' => now()->toAtomString(),
                'changefreq' => 'weekly',
                'priority' => '0.8',
            ],
        ];

        $blogUrls = Blog::query()
            ->latest('updated_at')
            ->get(['slug','updated_at'])
            ->map(function ($blog) {
                return [
                    'loc' => route('front.blog_details', $blog->slug),
                    'lastmod' => optional($blog->updated_at ?? $blog->created_at)->toAtomString() ?? now()->toAtomString(),
                    'changefreq' => 'weekly',
                    'priority' => '0.6',
                ];
            })->toArray();

        $eventUrls = Event::query()
            ->where('status', 1)
            ->whereNotNull('slug')
            ->where('slug', '!=', '')
            ->latest('updated_at')
            ->get(['slug','updated_at','created_at'])
            ->map(function ($event) {
                return [
                    'loc' => route('front.events.show', $event->slug),
                    'lastmod' => optional($event->updated_at ?? $event->created_at)->toAtomString() ?? now()->toAtomString(),
                    'changefreq' => 'weekly',
                    'priority' => '0.6',
                ];
            })->toArray();

        $productUrls = Product::query()
            ->where('status', 1)
            ->latest('updated_at')
            ->get(['slug','updated_at'])
            ->map(function ($product) {
                return [
                    'loc' => route('front.product.show', $product->slug),
                    'lastmod' => optional($product->updated_at ?? $product->created_at)->toAtomString() ?? now()->toAtomString(),
                    'changefreq' => 'weekly',
                    'priority' => '0.5',
                ];
            })->toArray();

        $archiveEntries = LivingArchiveEntry::published()
            ->ordered()
            ->get(['id', 'parent_id', 'slug', 'updated_at', 'created_at']);
        $archiveEntriesById = $archiveEntries->keyBy('id');
        $archiveUrls = $archiveEntries
            ->map(function ($entry) use ($archiveEntriesById) {
                return [
                    'loc' => route('front.living-archive.show', ['path' => $this->archivePath($entry, $archiveEntriesById)]),
                    'lastmod' => optional($entry->updated_at ?? $entry->created_at)->toAtomString() ?? now()->toAtomString(),
                    'changefreq' => 'monthly',
                    'priority' => $entry->parent_id ? '0.6' : '0.7',
                ];
            })->toArray();

        $epkUrls = EpkPage::published()
            ->ordered()
            ->get(['slug', 'updated_at', 'created_at'])
            ->map(function ($page) {
                return [
                    'loc' => $page->publicUrl(),
                    'lastmod' => optional($page->updated_at ?? $page->created_at)->toAtomString() ?? now()->toAtomString(),
                    'changefreq' => 'monthly',
                    'priority' => '0.7',
                ];
            })->toArray();

        $urls = array_merge($staticUrls, $blogUrls, $eventUrls, $productUrls, $archiveUrls, $epkUrls);

        return response()
            ->view('frontend.sitemap', compact('urls'))
            ->header('Content-Type', 'application/xml');
    }

    private function archivePath(LivingArchiveEntry $entry, $entriesById): string
    {
        $segments = [$entry->slug];
        $parentId = $entry->parent_id;

        while ($parentId && $entriesById->has($parentId)) {
            $parent = $entriesById->get($parentId);
            array_unshift($segments, $parent->slug);
            $parentId = $parent->parent_id;
        }

        return implode('/', $segments);
    }
}
