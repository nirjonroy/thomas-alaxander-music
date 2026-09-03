<?php

namespace App\Services\Publishing;

use App\Models\PublishingMedia;
use App\Models\PublishingMediaAttachment;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Intervention\Image\Facades\Image;
use Throwable;

class PublishingMediaService
{
    private const EXTENSIONS = ['jpg', 'jpeg', 'png', 'webp'];
    private const MIME_TYPES = ['image/jpeg', 'image/png', 'image/webp'];
    private const MAX_BYTES = 4194304;
    public const CANONICAL_DIRECTORY = 'uploads/publishing-media';
    public const CANONICAL_MAX_BYTES = 10485760;
    public const CANONICAL_MAX_DIMENSION = 8000;
    public const CANONICAL_MAX_PIXELS = 64000000;
    public const PURPOSES = ['blog_featured', 'blog_meta', 'event_featured', 'event_meta', 'general'];

    public function validateImage(UploadedFile $file): void
    {
        $extension = strtolower($file->getClientOriginalExtension());
        $mime = $file->getMimeType();

        if (! in_array($extension, self::EXTENSIONS, true)) {
            throw ValidationException::withMessages(['file' => 'The file extension is not allowed.']);
        }

        if (! in_array($mime, self::MIME_TYPES, true)) {
            throw ValidationException::withMessages(['file' => 'The file MIME type is not allowed.']);
        }

        if (($file->getSize() ?: 0) > self::MAX_BYTES) {
            throw ValidationException::withMessages(['file' => 'The file may not be greater than 4096 kilobytes.']);
        }

        if (! @getimagesize($file->getRealPath())) {
            throw ValidationException::withMessages(['file' => 'The file is not a valid image.']);
        }
    }

    public function validateCanonicalImage(UploadedFile $file): array
    {
        if (! $file->isValid() || ($file->getSize() ?: 0) <= 0) {
            throw ValidationException::withMessages(['file' => 'The file is missing or empty.']);
        }

        if (($file->getSize() ?: 0) > self::CANONICAL_MAX_BYTES) {
            throw ValidationException::withMessages(['file' => 'The file may not be greater than 10240 kilobytes.']);
        }

        $clientExtension = strtolower((string) $file->getClientOriginalExtension());
        if (! in_array($clientExtension, self::EXTENSIONS, true)) {
            throw ValidationException::withMessages(['file' => 'The file extension is not allowed.']);
        }

        if (preg_match('/\.(php|phtml|phar|html?|js|exe|sh|bat|cmd)(\.|$)/i', (string) $file->getClientOriginalName())) {
            throw ValidationException::withMessages(['file' => 'Executable file names are not allowed.']);
        }

        $imageInfo = @getimagesize($file->getRealPath());
        if (! $imageInfo || empty($imageInfo['mime'])) {
            throw ValidationException::withMessages(['file' => 'The file is not a valid image.']);
        }

        $actualMime = strtolower($imageInfo['mime']);
        $reportedMime = strtolower((string) $file->getMimeType());

        if (! in_array($actualMime, self::MIME_TYPES, true) || ! in_array($reportedMime, self::MIME_TYPES, true)) {
            throw ValidationException::withMessages(['file' => 'The file MIME type is not allowed.']);
        }

        if ($actualMime !== $reportedMime) {
            throw ValidationException::withMessages(['file' => 'The file MIME type does not match the image contents.']);
        }

        $extension = $this->extensionForMime($actualMime);
        if ($extension === null || ($clientExtension === 'jpg' ? 'jpeg' : $clientExtension) !== ($extension === 'jpg' ? 'jpeg' : $extension)) {
            throw ValidationException::withMessages(['file' => 'The file extension does not match the image contents.']);
        }

        $width = (int) ($imageInfo[0] ?? 0);
        $height = (int) ($imageInfo[1] ?? 0);

        if ($width < 1 || $height < 1) {
            throw ValidationException::withMessages(['file' => 'The image dimensions could not be read.']);
        }

        if ($width > self::CANONICAL_MAX_DIMENSION || $height > self::CANONICAL_MAX_DIMENSION || ($width * $height) > self::CANONICAL_MAX_PIXELS) {
            throw ValidationException::withMessages(['file' => 'The image dimensions are too large.']);
        }

        try {
            Image::make($file->getRealPath());
        } catch (Throwable $exception) {
            if (function_exists('imagecreatetruecolor') || extension_loaded('imagick')) {
                throw ValidationException::withMessages(['file' => 'The file could not be decoded as a supported image.']);
            }
        }

        return [
            'mime_type' => $actualMime,
            'extension' => $extension,
            'width' => $width,
            'height' => $height,
            'size' => (int) $file->getSize(),
        ];
    }

    public function storeCanonicalImage(UploadedFile $file, array $metadata = [], ?int $adminId = null, ?int $tokenId = null): array
    {
        $image = $this->validateCanonicalImage($file);
        $uuid = (string) Str::uuid();
        $filename = 'publishing-' . $uuid . '.' . $image['extension'];
        $relativePath = self::CANONICAL_DIRECTORY . '/' . $filename;
        $directory = public_path(self::CANONICAL_DIRECTORY);

        if (! is_dir($directory)) {
            mkdir($directory, 0755, true);
        }

        try {
            Image::make($file->getRealPath())->save(public_path($relativePath), 88);
        } catch (Throwable $exception) {
            copy($file->getRealPath(), public_path($relativePath));
        }

        $checksum = hash_file('sha256', public_path($relativePath));
        $duplicate = PublishingMedia::query()
            ->active()
            ->where('checksum', $checksum)
            ->oldest('id')
            ->first();

        $media = PublishingMedia::create([
            'uuid' => $uuid,
            'uploaded_by_admin_id' => $adminId,
            'token_id' => $tokenId,
            'media_type' => 'image',
            'purpose' => $metadata['purpose'] ?? 'general',
            'relative_path' => $relativePath,
            'stored_name' => $filename,
            'original_name' => $this->safeOriginalName($file->getClientOriginalName()),
            'mime_type' => $image['mime_type'],
            'extension' => $image['extension'],
            'size' => filesize(public_path($relativePath)) ?: $image['size'],
            'width' => $image['width'],
            'height' => $image['height'],
            'checksum' => $checksum,
            'alt_text' => $metadata['alt_text'] ?? null,
            'caption' => $metadata['caption'] ?? null,
            'status' => 1,
        ]);

        return [
            'media' => $media,
            'duplicate_of_media_id' => $duplicate?->id,
        ];
    }

    public function storeImage(UploadedFile $file, string $directory = 'uploads/custom-images'): array
    {
        $this->validateImage($file);

        $directory = trim($directory, '/');
        if (str_contains($directory, '..')) {
            throw ValidationException::withMessages(['directory' => 'Invalid upload directory.']);
        }

        $extension = strtolower($file->getClientOriginalExtension());
        $filename = 'publishing-' . Str::uuid() . '.' . $extension;
        $relativePath = $directory . '/' . $filename;
        $absoluteDirectory = public_path($directory);

        if (! is_dir($absoluteDirectory)) {
            mkdir($absoluteDirectory, 0755, true);
        }

        try {
            Image::make($file)->save(public_path($relativePath));
        } catch (Throwable $exception) {
            copy($file->getRealPath(), public_path($relativePath));
        }

        return [
            'path' => $relativePath,
            'filename' => $filename,
            'url' => asset($relativePath),
        ];
    }

    public function deriveBlogFeaturedImage(PublishingMedia $media, ?string $oldPath = null): string
    {
        $stored = $this->deriveImage($media, 'uploads/custom-images');
        $this->deleteApiManagedFile($oldPath);

        return $stored['path'];
    }

    public function deriveMetaImage(PublishingMedia $media, ?string $oldPath = null): string
    {
        $stored = $this->deriveImage($media, 'uploads/website-images');
        $this->deleteApiManagedFile($oldPath);

        return $stored['path'];
    }

    public function deriveEventFeaturedImage(PublishingMedia $media, ?string $oldFilename = null): string
    {
        if (! $media->isActive() || $media->media_type !== 'image') {
            throw ValidationException::withMessages(['featured_media_id' => 'The selected media is not an active image.']);
        }

        $filename = 'publishing-' . Str::uuid() . '.' . $media->extension;

        foreach (['uploads/custom-images', 'uploads/custom-images2'] as $directory) {
            $absoluteDirectory = public_path($directory);

            if (! is_dir($absoluteDirectory)) {
                mkdir($absoluteDirectory, 0755, true);
            }
        }

        try {
            Image::make(public_path($media->relative_path))->resize(800, 800)->save(public_path('uploads/custom-images/' . $filename), 88);
            Image::make(public_path($media->relative_path))->resize(300, 300)->save(public_path('uploads/custom-images2/' . $filename), 88);
        } catch (Throwable $exception) {
            copy(public_path($media->relative_path), public_path('uploads/custom-images/' . $filename));
            copy(public_path($media->relative_path), public_path('uploads/custom-images2/' . $filename));
        }

        $this->deleteApiManagedEventImages($oldFilename);

        return $filename;
    }

    public function storeEventImage(UploadedFile $file): array
    {
        $this->validateImage($file);

        $extension = strtolower($file->getClientOriginalExtension());
        $filename = 'publishing-' . Str::uuid() . '.' . $extension;

        foreach (['uploads/custom-images', 'uploads/custom-images2'] as $directory) {
            $absoluteDirectory = public_path($directory);

            if (! is_dir($absoluteDirectory)) {
                mkdir($absoluteDirectory, 0755, true);
            }
        }

        try {
            Image::make($file)->resize(800, 800)->save(public_path('uploads/custom-images/' . $filename));
            Image::make($file)->resize(300, 300)->save(public_path('uploads/custom-images2/' . $filename));
        } catch (Throwable $exception) {
            copy($file->getRealPath(), public_path('uploads/custom-images/' . $filename));
            copy($file->getRealPath(), public_path('uploads/custom-images2/' . $filename));
        }

        return [
            'path' => 'uploads/custom-images/' . $filename,
            'filename' => $filename,
            'url' => asset('uploads/custom-images/' . $filename),
        ];
    }

    public function deleteApiManagedEventImages(?string $filename): void
    {
        if (! $this->isApiManagedEventFilename($filename)) {
            return;
        }

        foreach (['uploads/custom-images', 'uploads/custom-images2', 'uploads/main-image'] as $directory) {
            $this->deleteApiManagedFile($directory . '/' . $filename);
        }
    }

    public function deleteApiManagedFile(?string $path): bool
    {
        if (! $this->isApiManagedPath($path)) {
            return false;
        }

        $absolutePath = public_path($path);

        if (is_file($absolutePath)) {
            return unlink($absolutePath);
        }

        return false;
    }

    public function deleteCanonicalMedia(PublishingMedia $media): bool
    {
        if (! $this->isCanonicalPublishingPath($media->relative_path, $media->stored_name)) {
            return false;
        }

        $absolutePath = public_path($media->relative_path);

        if (is_file($absolutePath)) {
            return unlink($absolutePath);
        }

        return false;
    }

    public function findUsableMedia(int $id, string $role): PublishingMedia
    {
        $media = PublishingMedia::query()->active()->find($id);

        if (! $media || $media->media_type !== 'image') {
            throw ValidationException::withMessages([$this->fieldForRole($role) => 'The selected media is not an active image.']);
        }

        $allowedPurposes = $this->allowedPurposesForRole($role);
        if (! in_array($media->purpose, $allowedPurposes, true)) {
            throw ValidationException::withMessages([$this->fieldForRole($role) => 'The selected media purpose is not valid for this use.']);
        }

        if (! $this->isCanonicalPublishingPath($media->relative_path, $media->stored_name) || ! is_file(public_path($media->relative_path))) {
            throw ValidationException::withMessages([$this->fieldForRole($role) => 'The selected media file is unavailable.']);
        }

        return $media;
    }

    public function syncAttachment(Model $attachable, PublishingMedia $media, string $role): array
    {
        $query = PublishingMediaAttachment::query()
            ->where('attachable_type', get_class($attachable))
            ->where('attachable_id', $attachable->getKey())
            ->where('role', $role);

        $existing = $query->get();
        $detached = $existing->where('publishing_media_id', '!=', $media->id)->values();

        foreach ($detached as $attachment) {
            $attachment->delete();
        }

        $attachment = PublishingMediaAttachment::firstOrCreate([
            'publishing_media_id' => $media->id,
            'attachable_type' => get_class($attachable),
            'attachable_id' => $attachable->getKey(),
            'role' => $role,
        ], [
            'created_at' => now(),
        ]);

        return [
            'attached' => $attachment,
            'detached' => $detached,
        ];
    }

    public function detachRole(Model $attachable, string $role): array
    {
        $attachments = PublishingMediaAttachment::query()
            ->where('attachable_type', get_class($attachable))
            ->where('attachable_id', $attachable->getKey())
            ->where('role', $role)
            ->get();

        foreach ($attachments as $attachment) {
            $attachment->delete();
        }

        return $attachments->all();
    }

    public function toApiArray(PublishingMedia $media, ?int $duplicateOfMediaId = null, bool $includeAttachments = false): array
    {
        $data = [
            'id' => $media->id,
            'uuid' => $media->uuid,
            'media_type' => $media->media_type,
            'purpose' => $media->purpose,
            'mime_type' => $media->mime_type,
            'extension' => $media->extension,
            'size' => (int) $media->size,
            'width' => $media->width,
            'height' => $media->height,
            'checksum' => $media->checksum,
            'url' => asset($media->relative_path),
            'alt_text' => $media->alt_text,
            'caption' => $media->caption,
            'status' => (int) $media->status,
            'duplicate_of_media_id' => $duplicateOfMediaId,
            'created_at' => optional($media->created_at)->toIso8601String(),
            'updated_at' => optional($media->updated_at)->toIso8601String(),
        ];

        if ($includeAttachments) {
            $data['attachments'] = $media->attachments->map(fn ($attachment) => [
                'resource_type' => class_basename($attachment->attachable_type),
                'resource_id' => (int) $attachment->attachable_id,
                'role' => $attachment->role,
            ])->values()->all();
        }

        return $data;
    }

    public function isApiManagedPath(?string $path): bool
    {
        if (! $path || str_contains($path, '..') || str_starts_with($path, '/') || preg_match('/^[a-zA-Z]:\\\\/', $path)) {
            return false;
        }

        return preg_match('#^uploads/(custom-images|custom-images2|website-images)/publishing-[0-9a-fA-F-]+\.(jpe?g|png|webp)$#', $path) === 1;
    }

    public function isCanonicalPublishingPath(?string $path, ?string $storedName = null): bool
    {
        if (! $path || str_contains($path, '..') || str_starts_with($path, '/') || preg_match('/^[a-zA-Z]:\\\\/', $path)) {
            return false;
        }

        if ($storedName && ! str_ends_with($path, '/' . $storedName)) {
            return false;
        }

        return preg_match('#^' . preg_quote(self::CANONICAL_DIRECTORY, '#') . '/publishing-[0-9a-fA-F-]+\.(jpe?g|png|webp)$#', $path) === 1;
    }

    private function isApiManagedEventFilename(?string $filename): bool
    {
        if (! $filename || str_contains($filename, '/') || str_contains($filename, '\\') || str_contains($filename, '..')) {
            return false;
        }

        return preg_match('/^publishing-[0-9a-fA-F-]+\.(jpe?g|png|webp)$/', $filename) === 1;
    }

    private function deriveImage(PublishingMedia $media, string $directory): array
    {
        if (! $media->isActive() || $media->media_type !== 'image') {
            throw ValidationException::withMessages(['media_id' => 'The selected media is not an active image.']);
        }

        if (! $this->isCanonicalPublishingPath($media->relative_path, $media->stored_name) || ! is_file(public_path($media->relative_path))) {
            throw ValidationException::withMessages(['media_id' => 'The selected media file is unavailable.']);
        }

        $filename = 'publishing-' . Str::uuid() . '.' . $media->extension;
        $relativePath = trim($directory, '/') . '/' . $filename;
        $absoluteDirectory = public_path($directory);

        if (! is_dir($absoluteDirectory)) {
            mkdir($absoluteDirectory, 0755, true);
        }

        try {
            Image::make(public_path($media->relative_path))->save(public_path($relativePath), 88);
        } catch (Throwable $exception) {
            copy(public_path($media->relative_path), public_path($relativePath));
        }

        return [
            'path' => $relativePath,
            'filename' => $filename,
            'url' => asset($relativePath),
        ];
    }

    private function extensionForMime(string $mime): ?string
    {
        return match ($mime) {
            'image/jpeg' => 'jpg',
            'image/png' => 'png',
            'image/webp' => 'webp',
            default => null,
        };
    }

    private function safeOriginalName(?string $name): ?string
    {
        $name = trim((string) $name);

        if ($name === '') {
            return null;
        }

        $name = str_replace(["\0", '/', '\\'], '', $name);

        return Str::limit($name, 255, '');
    }

    private function allowedPurposesForRole(string $role): array
    {
        return match ($role) {
            'blog_featured' => ['blog_featured', 'general'],
            'blog_meta' => ['blog_meta', 'general'],
            'event_featured' => ['event_featured', 'general'],
            'event_meta' => ['event_meta', 'general'],
            default => ['general'],
        };
    }

    private function fieldForRole(string $role): string
    {
        return str_ends_with($role, '_meta') ? 'meta_media_id' : 'featured_media_id';
    }
}
