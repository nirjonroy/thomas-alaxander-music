<?php

namespace App\Http\Controllers\WEB\Frontend;

use App\Http\Controllers\Controller;
use App\Services\LivingArchivePathResolver;

class LivingArchiveController extends Controller
{
    public function show(string $path, LivingArchivePathResolver $resolver)
    {
        return view('frontend.living-archive.show', array_merge(
            $resolver->resolve($path),
            ['resolver' => $resolver]
        ));
    }
}
