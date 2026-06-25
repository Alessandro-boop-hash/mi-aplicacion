<?php

namespace Tests\Feature;

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RoleAccessTest extends TestCase
{
    use RefreshDatabase;

    public function test_public_registration_creates_cliente_user(): void
    {
        $response = $this->post('/register', [
            'name' => 'Cliente Prueba',
            'email' => 'cliente.prueba@test.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'role' => 'cliente',
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect(route('dashboard'));

        $this->assertDatabaseHas('users', [
            'email' => 'cliente.prueba@test.com',
            'role' => UserRole::Cliente->value,
        ]);
    }

    public function test_public_registration_can_assign_admin_role(): void
    {
        $this->post('/register', [
            'name' => 'Admin Test',
            'email' => 'admin.test@test.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'role' => 'admin',
        ]);

        $this->assertDatabaseHas('users', [
            'email' => 'admin.test@test.com',
            'role' => UserRole::Admin->value,
        ]);
    }

    public function test_cliente_cannot_access_admin_routes(): void
    {
        $cliente = User::factory()->create([
            'role' => UserRole::Cliente,
        ]);

        $this->actingAs($cliente)
            ->get('/admin')
            ->assertForbidden();

        $this->actingAs($cliente)
            ->get('/admin/usuarios')
            ->assertForbidden();
    }

    public function test_cliente_cannot_access_operario_routes(): void
    {
        $cliente = User::factory()->create([
            'role' => UserRole::Cliente,
        ]);

        $this->actingAs($cliente)
            ->get('/operario/corte')
            ->assertForbidden();

        $this->actingAs($cliente)
            ->get('/operario/estampado')
            ->assertForbidden();
    }

    public function test_cliente_can_access_own_routes(): void
    {
        $cliente = User::factory()->create([
            'role' => UserRole::Cliente,
        ]);

        $this->actingAs($cliente)
            ->get('/cliente/pedidos')
            ->assertOk();

        $this->actingAs($cliente)
            ->get('/cliente/reclamos')
            ->assertOk();
    }

    public function test_dashboard_redirects_cliente_to_inicio(): void
    {
        $cliente = User::factory()->create([
            'role' => UserRole::Cliente,
        ]);

        $this->actingAs($cliente)
            ->get('/dashboard')
            ->assertRedirect(route('cliente.inicio'));
    }

    public function test_admin_can_access_admin_routes(): void
    {
        $admin = User::factory()->create([
            'role' => UserRole::Admin,
        ]);

        $this->actingAs($admin)
            ->get('/admin')
            ->assertOk();
    }

    public function test_password_is_hashed_on_registration(): void
    {
        $this->post('/register', [
            'name' => 'Hash Test',
            'email' => 'hash@test.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $user = User::where('email', 'hash@test.com')->first();

        $this->assertNotEquals('password123', $user->password);
        $this->assertTrue(password_verify('password123', $user->password));
    }
}
