<?php

namespace Tests\Feature;

use App\Models\Admin;
use App\Models\CompanionPageSetting;
use App\Models\JourneyCompanion;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class CompanionAdminAuthTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * 1. Unauthenticated users cannot access any companion admin routes and are redirected to admin.login.
     */
    public function test_unauthenticated_user_is_redirected_to_admin_login(): void
    {
        $wolf = JourneyCompanion::where('slug', 'wolf')->firstOrFail();

        $this->get(route('admin.companions-journey.index'))
            ->assertRedirect(route('admin.login'));

        $this->get(route('admin.companions-journey.settings'))
            ->assertRedirect(route('admin.login'));

        $this->get(route('admin.companions-journey.edit', $wolf->id))
            ->assertRedirect(route('admin.login'));

        $this->get(route('admin.companions-journey.preview'))
            ->assertRedirect(route('admin.login'));

        $this->get(route('admin.companions-journey.preview-as-public'))
            ->assertRedirect(route('admin.login'));

        $this->put(route('admin.companions-journey.update', $wolf->id), [
            'is_active' => false,
        ])->assertRedirect(route('admin.login'));

        $this->put(route('admin.companions-journey.settings.update'), [
            'is_active' => false,
        ])->assertRedirect(route('admin.login'));
    }

    /**
     * 2. Ordinary frontend users (store customers) are strictly blocked from companion admin routes.
     */
    public function test_ordinary_frontend_user_cannot_access_companion_admin_routes(): void
    {
        $wolf = JourneyCompanion::where('slug', 'wolf')->firstOrFail();

        $customer = User::create([
            'name' => 'Ordinary Customer',
            'email' => 'customer-fixture-' . uniqid() . '@example.com',
            'password' => bcrypt('password'),
            'status' => 1,
            'active_status' => 1,
        ]);

        $this->actingAs($customer, 'web');

        $this->get(route('admin.companions-journey.index'))
            ->assertRedirect(route('admin.login'));

        $this->get(route('admin.companions-journey.settings'))
            ->assertRedirect(route('admin.login'));

        $this->get(route('admin.companions-journey.edit', $wolf->id))
            ->assertRedirect(route('admin.login'));

        $this->get(route('admin.companions-journey.preview'))
            ->assertRedirect(route('admin.login'));

        $this->put(route('admin.companions-journey.update', $wolf->id), [
            'is_active' => false,
        ])->assertRedirect(route('admin.login'));

        $this->put(route('admin.companions-journey.settings.update'), [
            'is_active' => false,
        ])->assertRedirect(route('admin.login'));
    }

    /**
     * 3. Complete Step 5 test sequence using the application's actual admin user session.
     */
    public function test_complete_admin_journey_with_web_guard_administrator(): void
    {
        $wolf = JourneyCompanion::where('slug', 'wolf')->firstOrFail();

        // 1. Unauthenticated user opens Companion index -> redirected to admin login
        $this->get(route('admin.companions-journey.index'))
            ->assertRedirect(route('admin.login'));

        // 2. Administrator logs in using the established web-guard staff/admin architecture
        $adminUser = User::create([
            'name' => 'Test Staff Admin',
            'email' => 'staff-admin-fixture-' . uniqid() . '@example.com',
            'password' => bcrypt('password'),
            'status' => 2, // Established admin/staff status
        ]);
        $adminUser->active_status = 1;
        $adminUser->save();

        $adminRole = Role::where('name', 'Admin')->where('guard_name', 'web')->first();
        if ($adminRole) {
            $adminUser->assignRole($adminRole);
        }

        $this->actingAs($adminUser, 'web');

        // 3. Administrator opens dashboard -> HTTP 200
        $this->get(route('admin.dashboard'))
            ->assertOk();

        // 4. Administrator clicks Companion index -> HTTP 200
        $this->get(route('admin.companions-journey.index'))
            ->assertOk()
            ->assertSee('Companions Along the Journey')
            ->assertSee('Wolf');

        // 5. Administrator opens Companion settings -> HTTP 200
        $this->get(route('admin.companions-journey.settings'))
            ->assertOk()
            ->assertSee('Page Settings');

        // 6. Administrator opens the edit page for Wolf -> HTTP 200
        $this->get(route('admin.companions-journey.edit', $wolf->id))
            ->assertOk()
            ->assertSee('Edit Companion: Wolf');

        // 7. Administrator opens Companion Preview -> HTTP 200
        $this->get(route('admin.companions-journey.preview'))
            ->assertOk()
            ->assertSee('COMPANIONS ALONG THE JOURNEY')
            ->assertSee('<meta name="robots" content="noindex, nofollow">', false);

        // 8. Administrator can submit an authorized Companion update
        $this->put(route('admin.companions-journey.update', $wolf->id), [
            'themes' => $wolf->themes,
            'is_active' => false,
        ])->assertRedirect(route('admin.companions-journey.index'));

        // 9. Administrator can submit an authorized Page Settings update
        $this->put(route('admin.companions-journey.settings.update'), [
            'entrance_title' => 'COMPANIONS ALONG THE JOURNEY',
            'is_active' => false,
        ])->assertRedirect();

        // 10. Administrator logs out -> protected routes are no longer accessible
        $this->post(route('admin.logout'))
            ->assertRedirect(route('admin.login'));

        $this->get(route('admin.companions-journey.index'))
            ->assertRedirect(route('admin.login'));

        $this->get(route('admin.companions-journey.settings'))
            ->assertRedirect(route('admin.login'));

        $this->get(route('admin.companions-journey.preview'))
            ->assertRedirect(route('admin.login'));

        $this->get(route('admin.companions-journey.edit', $wolf->id))
            ->assertRedirect(route('admin.login'));
    }

    /**
     * 4. An administrator authenticated under the 'admin' guard is also fully authorized.
     */
    public function test_administrator_authenticated_under_admin_guard_is_also_authorized(): void
    {
        $wolf = JourneyCompanion::where('slug', 'wolf')->firstOrFail();

        $admin = Admin::create([
            'name' => 'Direct Guard Admin',
            'email' => 'guard-admin-fixture-' . uniqid() . '@example.com',
            'password' => bcrypt('password'),
            'status' => 1,
            'admin_type' => 1,
        ]);

        $this->actingAs($admin, 'admin');

        $this->get(route('admin.companions-journey.index'))
            ->assertOk()
            ->assertSee('Companions Along the Journey');

        $this->get(route('admin.companions-journey.settings'))
            ->assertOk();

        $this->get(route('admin.companions-journey.edit', $wolf->id))
            ->assertOk();

        $this->get(route('admin.companions-journey.preview'))
            ->assertOk();
    }

    /**
     * 5. Negative test: Staff user with status=2 but active_status=0 (disabled) is denied.
     */
    public function test_inactive_staff_user_is_denied(): void
    {
        $wolf = JourneyCompanion::where('slug', 'wolf')->firstOrFail();

        $inactiveStaff = User::create([
            'name' => 'Inactive Staff',
            'email' => 'inactive-staff-' . uniqid() . '@example.com',
            'password' => bcrypt('password'),
            'status' => 2,
            'active_status' => 0, // Disabled
        ]);

        $adminRole = Role::where('name', 'Admin')->where('guard_name', 'web')->first();
        if ($adminRole) {
            $inactiveStaff->assignRole($adminRole);
        }

        $this->actingAs($inactiveStaff, 'web');

        $this->get(route('admin.companions-journey.index'))
            ->assertRedirect(route('admin.login'));

        $this->get(route('admin.companions-journey.preview'))
            ->assertRedirect(route('admin.login'));

        $this->get(route('admin.companions-journey.edit', $wolf->id))
            ->assertRedirect(route('admin.login'));
    }

    /**
     * 6. Negative test: Staff user with status=2 and active_status=1 but missing admin.dashboard permission is denied.
     */
    public function test_staff_user_without_admin_dashboard_permission_is_denied(): void
    {
        $wolf = JourneyCompanion::where('slug', 'wolf')->firstOrFail();

        $unauthorizedStaff = User::create([
            'name' => 'Unauthorized Staff',
            'email' => 'unauth-staff-' . uniqid() . '@example.com',
            'password' => bcrypt('password'),
            'status' => 2,
            'active_status' => 1,
            // No roles or permissions assigned
        ]);

        $this->actingAs($unauthorizedStaff, 'web');

        $this->get(route('admin.companions-journey.index'))
            ->assertRedirect(route('admin.login'));

        $this->get(route('admin.companions-journey.preview'))
            ->assertRedirect(route('admin.login'));

        $this->get(route('admin.companions-journey.edit', $wolf->id))
            ->assertRedirect(route('admin.login'));
    }

    /**
     * 7. Negative test: Ordinary user (status=1) is denied even if granted admin.dashboard permission.
     */
    public function test_customer_with_unrelated_permission_is_denied(): void
    {
        $wolf = JourneyCompanion::where('slug', 'wolf')->firstOrFail();

        $customer = User::create([
            'name' => 'Customer With Rogue Permission',
            'email' => 'customer-perm-' . uniqid() . '@example.com',
            'password' => bcrypt('password'),
            'status' => 1, // Regular user status
            'active_status' => 1,
        ]);

        // Give permission directly or via role
        $customer->givePermissionTo('admin.dashboard');

        $this->actingAs($customer, 'web');

        $this->get(route('admin.companions-journey.index'))
            ->assertRedirect(route('admin.login'));

        $this->get(route('admin.companions-journey.preview'))
            ->assertRedirect(route('admin.login'));

        $this->get(route('admin.companions-journey.edit', $wolf->id))
            ->assertRedirect(route('admin.login'));
    }

    /**
     * 8. Negative test: Inactive direct admin (status=0 in admin guard) is denied.
     */
    public function test_inactive_admin_guard_user_is_denied(): void
    {
        $wolf = JourneyCompanion::where('slug', 'wolf')->firstOrFail();

        $inactiveAdmin = Admin::create([
            'name' => 'Inactive Direct Admin',
            'email' => 'inactive-admin-' . uniqid() . '@example.com',
            'password' => bcrypt('password'),
            'status' => 0, // Inactive
            'admin_type' => 1,
        ]);

        $this->actingAs($inactiveAdmin, 'admin');

        $this->get(route('admin.companions-journey.index'))
            ->assertRedirect(route('admin.login'));

        $this->get(route('admin.companions-journey.preview'))
            ->assertRedirect(route('admin.login'));

        $this->get(route('admin.companions-journey.edit', $wolf->id))
            ->assertRedirect(route('admin.login'));
    }
}
