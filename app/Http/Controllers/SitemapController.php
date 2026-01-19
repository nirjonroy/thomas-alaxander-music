<?php

namespace App\Http\Controllers;

use App\Models\Blog;
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

        $urls = array_merge($staticUrls, $blogUrls, $productUrls);

        return response()
            ->view('frontend.sitemap', compact('urls'))
            ->header('Content-Type', 'application/xml');
    }
}
