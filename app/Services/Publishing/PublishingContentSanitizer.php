<?php

namespace App\Services\Publishing;

class PublishingContentSanitizer
{
    public function sanitize(?string $html): ?string
    {
        if ($html === null) {
            return null;
        }

        $clean = $this->preClean($html);

        if (app()->bound('purifier')) {
            return app('purifier')->clean($clean, $this->purifierConfig());
        }

        if (class_exists(\Purifier::class)) {
            return \Purifier::clean($clean, $this->purifierConfig());
        }

        return strip_tags($clean, '<p><br><h1><h2><h3><h4><h5><h6><strong><b><em><i><u><ul><ol><li><blockquote><a><span><table><thead><tbody><tr><th><td><img>');
    }

    private function preClean(string $html): string
    {
        $html = preg_replace('#<(script|style|iframe|object|embed|svg)\b[^>]*>.*?</\1>#is', '', $html) ?? '';
        $html = preg_replace('#<\s*/?\s*(script|style|iframe|object|embed|svg)\b[^>]*>#is', '', $html) ?? '';
        $html = preg_replace('/\s+on[a-z]+\s*=\s*("[^"]*"|\'[^\']*\'|[^\s>]+)/i', '', $html) ?? '';
        $html = preg_replace('/(href|src)\s*=\s*([\'"])\s*javascript:[^\'"]*\2/i', '$1="#"', $html) ?? '';

        return $html;
    }

    private function purifierConfig(): array
    {
        return [
            'HTML.Allowed' => 'p,br,h1,h2,h3,h4,h5,h6,strong,b,em,i,u,ul,ol,li,blockquote,a[href|title|target|rel],span,table,thead,tbody,tr,th,td,img[src|alt|title|width|height]',
            'HTML.SafeIframe' => false,
            'URI.AllowedSchemes' => [
                'http' => true,
                'https' => true,
                'mailto' => true,
                'tel' => true,
            ],
            'Attr.AllowedFrameTargets' => ['_blank'],
        ];
    }
}
