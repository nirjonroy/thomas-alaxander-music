<?php

namespace Tests\Feature;

use App\Models\Admin;
use App\Support\PublishingApiResponse;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;
use Tests\TestCase;

class PublishingApiAuthTest extends TestCase
{
    use DatabaseTransactions;

    protected function setUp(): void
    {
        parent::setUp();

        if (!Route::has('api.v1.publishing.test.blogs-read')) {
            Route::prefix('api/v1/publishing')
                ->as('api.v1.publishing.test.')
                ->middleware(['auth:sanctum', 'throttle:publishing-api'])
                ->group(function () {
                    Route::get('ability-probe/blogs-read', function () {
                        return PublishingApiResponse::success('Ability accepted.');
                    })->middleware('abilities:publishing:blogs.read')->name('blogs-read');
                });
        }
    }

    public function test_publishing_health_requires_a_token(): void
    {
        $this->getJson('/api/v1/publishing/health')
            ->assertStatus(401)
            ->assertJson([
                'success' => false,
                'message' => 'Unauthenticated.',
            ]);
    }

    public function test_publishing_health_rejects_invalid_token(): void
    {
        $this->withHeader('Authorization', 'Bearer invalid-token')
            ->getJson('/api/v1/publishing/health')
            ->assertStatus(401)
            ->assertJson([
                'success' => false,
                'message' => 'Unauthenticated.',
            ]);
    }

    public function test_valid_sanctum_token_can_access_health_and_me(): void
    {
        [$admin, $plainToken] = $this->publishingToken([
            'publishing:blogs.read',
            'publishing:events.read',
        ]);

        $this->withToken($plainToken)
            ->getJson('/api/v1/publishing/health')
            ->assertOk()
            ->assertJson([
                'success' => true,
                'message' => 'Publishing API is available.',
                'data' => [
                    'service' => 'publishing-api',
                    'version' => 'v1',
                ],
            ]);

        $this->withToken($plainToken)
            ->getJson('/api/v1/publishing/me')
            ->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.identity.id', $admin->id)
            ->assertJsonPath('data.identity.type', 'Admin')
            ->assertJsonPath('data.identity.name', 'Publishing Automation')
            ->assertJsonPath('data.identity.email', $admin->email)
            ->assertJsonPath('data.token.abilities', [
                'publishing:blogs.read',
                'publishing:events.read',
            ]);
    }

    public function test_ability_protected_route_rejects_missing_ability(): void
    {
        [, $plainToken] = $this->publishingToken(['publishing:events.read']);

        $this->withToken($plainToken)
            ->getJson('/api/v1/publishing/ability-probe/blogs-read')
            ->assertStatus(403)
            ->assertJson([
                'success' => false,
                'message' => 'Authenticated token does not have the required ability.',
            ]);
    }

    public function test_ability_protected_route_accepts_correct_ability(): void
    {
        [, $plainToken] = $this->publishingToken(['publishing:blogs.read']);

        $this->withToken($plainToken)
            ->getJson('/api/v1/publishing/ability-probe/blogs-read')
            ->assertOk()
            ->assertJson([
                'success' => true,
                'message' => 'Ability accepted.',
            ]);
    }

    public function test_publishing_routes_use_the_publishing_rate_limiter(): void
    {
        $middleware = Route::getRoutes()
            ->getByName('api.v1.publishing.health')
            ->gatherMiddleware();

        $this->assertContains('throttle:publishing-api', $middleware);
    }

    public function test_me_does_not_expose_plaintext_or_hashed_token_data(): void
    {
        [$admin, $plainToken, $hashedToken] = $this->publishingToken(['publishing:blogs.read']);

        $this->withToken($plainToken)
            ->getJson('/api/v1/publishing/me')
            ->assertOk()
            ->assertSee('Publishing Automation')
            ->assertDontSee($plainToken, false)
            ->assertDontSee($hashedToken, false)
            ->assertDontSee((string) $admin->password, false);
    }

    private function publishingToken(array $abilities): array
    {
        $admin = Admin::create([
            'name' => 'Publishing Automation',
            'email' => 'publishing-'.Str::uuid().'@example.test',
            'password' => Hash::make(Str::random(32)),
            'status' => 1,
            'admin_type' => 0,
        ]);

        $token = $admin->createToken('Publishing API Test Token', $abilities);

        return [$admin, $token->plainTextToken, $token->accessToken->token];
    }
}
