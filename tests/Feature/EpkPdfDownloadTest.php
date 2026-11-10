<?php

namespace Tests\Feature;

use App\Models\Admin;
use App\Models\EpkPage;
use File;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class EpkPdfDownloadTest extends TestCase
{
    use DatabaseTransactions;

    private function actingAsAdmin(): Admin
    {
        $admin = Admin::updateOrCreate(
            ['email' => 'epk-pdf-admin@example.com'],
            [
                'name' => 'EPK PDF Admin',
                'password' => bcrypt('password'),
                'status' => 1,
                'admin_type' => 1,
            ]
        );

        $this->actingAs($admin, 'admin');

        return $admin;
    }

    public function test_admin_can_upload_pdf_document(): void
    {
        $this->actingAsAdmin();

        $page = EpkPage::firstOrCreate(
            ['slug' => 'test-pdf-artist'],
            [
                'title' => 'Test PDF Artist',
                'status' => 1,
            ]
        );

        $pdf = UploadedFile::fake()->create('sample-epk.pdf', 1024, 'application/pdf');

        $response = $this->put(route('admin.epk-page.update', $page->id), [
            'title' => 'Test PDF Artist Updated',
            'status' => 1,
            'pdf_file' => $pdf,
        ]);

        $response->assertRedirect(route('admin.epk-page.index'));

        $page->refresh();
        $this->assertNotNull($page->pdf_file);
        $this->assertStringStartsWith('uploads/epk/epk-pdf-', $page->pdf_file);
        $this->assertStringEndsWith('.pdf', $page->pdf_file);

        $fullPath = public_path($page->pdf_file);
        $this->assertFileExists($fullPath);

        // Clean up
        if (File::exists($fullPath)) {
            File::delete($fullPath);
        }
    }

    public function test_admin_replacement_does_not_delete_previous_pdf_unless_requested(): void
    {
        $this->actingAsAdmin();

        $page = EpkPage::firstOrCreate(
            ['slug' => 'test-replacement-epk'],
            [
                'title' => 'Test Replacement EPK',
                'status' => 1,
            ]
        );

        // First upload
        $pdf1 = UploadedFile::fake()->create('first-epk.pdf', 500, 'application/pdf');
        $this->put(route('admin.epk-page.update', $page->id), [
            'title' => 'Test Replacement EPK',
            'status' => 1,
            'pdf_file' => $pdf1,
        ]);

        $page->refresh();
        $firstPath = public_path($page->pdf_file);
        $this->assertFileExists($firstPath);

        // Replace without removal flag
        $pdf2 = UploadedFile::fake()->create('second-epk.pdf', 600, 'application/pdf');
        $this->put(route('admin.epk-page.update', $page->id), [
            'title' => 'Test Replacement EPK',
            'status' => 1,
            'pdf_file' => $pdf2,
        ]);

        $page->refresh();
        $secondPath = public_path($page->pdf_file);
        $this->assertFileExists($secondPath);
        $this->assertNotEquals($firstPath, $secondPath);

        // Previous PDF must still exist on disk (Rule: Never delete during replacement unless requested)
        $this->assertFileExists($firstPath);

        // Clean up both
        if (File::exists($firstPath)) {
            File::delete($firstPath);
        }
        if (File::exists($secondPath)) {
            File::delete($secondPath);
        }
    }

    public function test_admin_can_explicitly_remove_pdf_document(): void
    {
        $this->actingAsAdmin();

        $page = EpkPage::firstOrCreate(
            ['slug' => 'test-remove-epk'],
            [
                'title' => 'Test Remove EPK',
                'status' => 1,
            ]
        );

        $pdf = UploadedFile::fake()->create('to-remove.pdf', 300, 'application/pdf');
        $this->put(route('admin.epk-page.update', $page->id), [
            'title' => 'Test Remove EPK',
            'status' => 1,
            'pdf_file' => $pdf,
        ]);

        $page->refresh();
        $fullPath = public_path($page->pdf_file);
        $this->assertFileExists($fullPath);

        // Explicitly remove
        $this->put(route('admin.epk-page.update', $page->id), [
            'title' => 'Test Remove EPK',
            'status' => 1,
            'remove_pdf_file' => 1,
        ]);

        $page->refresh();
        $this->assertNull($page->pdf_file);
        $this->assertFileDoesNotExist($fullPath);
    }

    public function test_pdf_upload_validation_rejects_non_pdf_and_over_25mb(): void
    {
        $this->actingAsAdmin();

        $page = EpkPage::firstOrCreate(
            ['slug' => 'test-val-epk'],
            [
                'title' => 'Test Validation EPK',
                'status' => 1,
            ]
        );

        // Non-pdf (e.g. text file)
        $badFile = UploadedFile::fake()->create('fake.txt', 100, 'text/plain');
        $response = $this->from(route('admin.epk-page.edit', $page->id))
            ->put(route('admin.epk-page.update', $page->id), [
                'title' => 'Test Validation EPK',
                'status' => 1,
                'pdf_file' => $badFile,
            ]);
        $response->assertSessionHasErrors('pdf_file');

        // File exceeding 25MB (25601 KB)
        $oversizedFile = UploadedFile::fake()->create('huge.pdf', 26000, 'application/pdf');
        $response = $this->from(route('admin.epk-page.edit', $page->id))
            ->put(route('admin.epk-page.update', $page->id), [
                'title' => 'Test Validation EPK',
                'status' => 1,
                'pdf_file' => $oversizedFile,
            ]);
        $response->assertSessionHasErrors('pdf_file');
    }

    public function test_unauthorized_user_cannot_upload_pdf(): void
    {
        $page = EpkPage::firstOrCreate(
            ['slug' => 'test-unauth-epk'],
            [
                'title' => 'Test Unauth EPK',
                'status' => 1,
            ]
        );

        $pdf = UploadedFile::fake()->create('unauth.pdf', 500, 'application/pdf');

        $response = $this->put(route('admin.epk-page.update', $page->id), [
            'title' => 'Hacked',
            'status' => 1,
            'pdf_file' => $pdf,
        ]);

        $response->assertForbidden();
    }

    public function test_published_epk_pdf_can_be_viewed_inline(): void
    {
        $page = EpkPage::where('slug', 'full-artist')->firstOrFail();

        // Create temporary PDF file
        $dir = public_path('uploads/epk');
        if (!File::isDirectory($dir)) {
            File::makeDirectory($dir, 0755, true);
        }
        $filename = 'epk-pdf-test-view.pdf';
        file_put_contents($dir . '/' . $filename, '%PDF-1.4 test view');

        $page->pdf_file = 'uploads/epk/' . $filename;
        $page->save();

        $response = $this->get(route('front.epk.pdf.view', 'full-artist'));
        $response->assertOk();
        $response->assertHeader('Content-Type', 'application/pdf');
        $this->assertStringContainsString('inline', (string) $response->headers->get('Content-Disposition'));

        // Clean up
        @unlink($dir . '/' . $filename);
        $page->pdf_file = null;
        $page->save();
    }

    public function test_published_epk_pdf_can_be_downloaded(): void
    {
        $page = EpkPage::where('slug', 'crooners')->firstOrFail();

        $dir = public_path('uploads/epk');
        if (!File::isDirectory($dir)) {
            File::makeDirectory($dir, 0755, true);
        }
        $filename = 'epk-pdf-test-download.pdf';
        file_put_contents($dir . '/' . $filename, '%PDF-1.4 test download');

        $page->pdf_file = 'uploads/epk/' . $filename;
        $page->save();

        $response = $this->get(route('front.epk.pdf.download', 'crooners'));
        $response->assertOk();
        $response->assertHeader('Content-Type', 'application/pdf');
        $this->assertStringContainsString('attachment', (string) $response->headers->get('Content-Disposition'));
        $this->assertStringContainsString('Thomas-Alexander-Crooners-EPK.pdf', (string) $response->headers->get('Content-Disposition'));

        // Clean up
        @unlink($dir . '/' . $filename);
        $page->pdf_file = null;
        $page->save();
    }

    public function test_missing_pdf_or_unpublished_epk_returns_404(): void
    {
        // Published EPK with no PDF
        $page = EpkPage::where('slug', 'full-artist')->firstOrFail();
        $page->pdf_file = null;
        $page->save();

        $this->get(route('front.epk.pdf.view', 'full-artist'))->assertNotFound();
        $this->get(route('front.epk.pdf.download', 'full-artist'))->assertNotFound();

        // Published EPK with non-existent file on disk
        $page->pdf_file = 'uploads/epk/does-not-exist.pdf';
        $page->save();

        $this->get(route('front.epk.pdf.view', 'full-artist'))->assertNotFound();
        $this->get(route('front.epk.pdf.download', 'full-artist'))->assertNotFound();

        // Unpublished EPK
        $draft = EpkPage::create([
            'title' => 'Draft EPK',
            'slug' => 'draft-epk',
            'status' => 0,
            'pdf_file' => 'uploads/epk/some-file.pdf',
        ]);

        $this->get('/epk/draft-epk/pdf')->assertNotFound();
        $this->get('/epk/draft-epk/download')->assertNotFound();

        $page->pdf_file = null;
        $page->save();
    }

    public function test_frontend_buttons_appear_only_when_pdf_exists(): void
    {
        $page = EpkPage::where('slug', 'full-artist')->firstOrFail();

        // When pdf_file is null
        $page->pdf_file = null;
        $page->save();

        $res1 = $this->get('/epk/full-artist');
        $res1->assertOk();
        $res1->assertDontSee('View EPK PDF');
        $res1->assertDontSee('Download EPK PDF');
        $res1->assertSee('Request EPK');

        // When pdf_file exists
        $dir = public_path('uploads/epk');
        if (!File::isDirectory($dir)) {
            File::makeDirectory($dir, 0755, true);
        }
        $filename = 'epk-pdf-test-buttons.pdf';
        file_put_contents($dir . '/' . $filename, '%PDF-1.4 test buttons');

        $page->pdf_file = 'uploads/epk/' . $filename;
        $page->save();

        $res2 = $this->get('/epk/full-artist');
        $res2->assertOk();
        $res2->assertSee('View EPK PDF');
        $res2->assertSee('Download EPK PDF');
        $res2->assertSee(route('front.epk.pdf.view', 'full-artist'));
        $res2->assertSee(route('front.epk.pdf.download', 'full-artist'));

        // Clean up
        @unlink($dir . '/' . $filename);
        $page->pdf_file = null;
        $page->save();
    }
}
