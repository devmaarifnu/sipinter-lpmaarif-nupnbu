<?php

namespace Tests\Feature;

use App\Models\User;
use Tests\TestCase;

/**
 * Smoke test pasca-upgrade Laravel 13.
 *
 * Memakai actingAs() + koneksi DB yang sudah ada (tanpa RefreshDatabase,
 * karena database project ini bukan database testing terpisah).
 */
class UpgradeSmokeTest extends TestCase
{
    private function userDenganRole(string $role): ?User
    {
        return User::where('role', $role)->where('status_active', 'active')->first();
    }

    public function test_halaman_publik_dapat_dirender(): void
    {
        foreach (['/', '/kontak', '/informasi', '/login', '/ceknpsn'] as $uri) {
            $this->get($uri)->assertStatus(200);
        }
    }

    public function test_halaman_login_dapat_diakses(): void
    {
        $this->get('/login')
            ->assertStatus(200)
            ->assertSee('_token', false);
    }

    public function test_operator_dapat_membuka_dashboard_dan_halaman_satpen(): void
    {
        $user = $this->userDenganRole('operator');
        if (!$user) {
            $this->markTestSkipped('Tidak ada user operator aktif di database.');
        }

        $this->actingAs($user)->get('/dashboard')->assertStatus(200);
        $this->actingAs($user)->get('/satpen')->assertStatus(200);
    }

    public function test_admin_dapat_membuka_dashboard(): void
    {
        $user = $this->userDenganRole('admin pusat')
            ?? $this->userDenganRole('super admin');
        if (!$user) {
            $this->markTestSkipped('Tidak ada user admin aktif di database.');
        }

        $this->actingAs($user)->get('/a')->assertStatus(200);
    }

    public function test_route_names_hash_tidak_duplikat(): void
    {
        $names = collect(app('router')->getRoutes()->getRoutes())
            ->map(fn ($route) => $route->getName())
            ->filter()
            ->countBy()
            ->filter(fn ($jumlah) => $jumlah > 1);

        $this->assertCount(0, $names, 'Nama route duplikat: '.$names->keys()->implode(', '));
    }

    public function test_nama_route_hasil_rename_tersedia(): void
    {
        foreach ([
            'admin.ptk.file',
            'admin.dashboard',
            'mysatpen.edit',
            'mysatpen.revisi',
            'mysatpen.perpanjang.page',
            'mysatpen.perpanjang.submit',
            'ptk.file',
        ] as $nama) {
            $this->assertTrue(app('router')->getRoutes()->hasNamedRoute($nama), "Route '$nama' tidak ditemukan.");
        }
    }

    public function test_nama_route_lama_tidak_lagi_menimpa(): void
    {
        $routeAdmin = app('router')->getRoutes()->getByName('admin.ptk.file');
        $routeOperator = app('router')->getRoutes()->getByName('ptk.file');

        $this->assertStringContainsString('a/npyp/file', $routeAdmin->uri());
        $this->assertStringContainsString('npyp/file', $routeOperator->uri());
    }
}
