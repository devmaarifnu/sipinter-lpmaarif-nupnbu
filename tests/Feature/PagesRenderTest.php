<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Support\Facades\Route;
use Tests\TestCase;

/**
 * Menelusuri seluruh route GET statis (tanpa parameter) untuk setiap role
 * dan memastikan halaman minimal tidak menghasilkan error 500.
 *
 * Route yang mengembalikan 403/404/302 dianggap wajar.
 */
class PagesRenderTest extends TestCase
{
    private function userUntuk(string $role): ?User
    {
        return User::where('role', $role)->where('status_active', 'active')->first();
    }

    /**
     * gatherMiddleware() mengembalikan nama alias (mis. "onlyadmin"),
     * bukan nama class, sehingga pencocokan harus memakai keduanya.
     *
     * @param  array<int, string>  $middleware
     */
    private function punyaMiddleware(array $middleware, string $alias): bool
    {
        $class = [
            'onlyoperator' => \App\Http\Middleware\OnlyOperator::class,
            'onlyadmin' => \App\Http\Middleware\OnlyAdmin::class,
            'superadmin' => \App\Http\Middleware\SuperAdmin::class,
            'mustlogin' => \App\Http\Middleware\MustLogin::class,
        ][$alias];

        return in_array($alias, $middleware, true) || in_array($class, $middleware, true);
    }

    /**
     * Login sesungguhnya per role. actingAs() saja tidak cukup karena
     * guard sesi di-reset pada tiap request di dalam satu test.
     */
    private function loginSebagai(?User $user): void
    {
        \Illuminate\Support\Facades\Auth::logout();

        if ($user === null) {
            return;
        }

        $this->app['auth']->guard('web')->login($user);
        $this->app['session.store']->put('_token', 'smoke-test-token');
    }

    public function test_seluruh_halaman_statis_per_role_tidak_error_500(): void
    {
        $pemetaan = [
            'public' => null,
            'operator' => $this->userUntuk('operator'),
            'admin pusat' => $this->userUntuk('admin pusat') ?? $this->userUntuk('super admin'),
        ];

        $gagal = [];

        foreach (Route::getRoutes() as $route) {
            if (!in_array('GET', $route->methods(), true)) {
                continue;
            }
            if (str_contains($route->uri(), '{')) {
                continue;
            }

            $middleware = $route->gatherMiddleware();
            $role = null;
            if ($this->punyaMiddleware($middleware, 'onlyoperator')) {
                $role = 'operator';
            } elseif ($this->punyaMiddleware($middleware, 'onlyadmin')
                || $this->punyaMiddleware($middleware, 'superadmin')) {
                $role = 'admin pusat';
            } elseif ($this->punyaMiddleware($middleware, 'mustlogin')) {
                // Route ber-login tanpa middleware role khusus (mis. /mysatpen):
                // uji dengan operator karena itu role paling luas.
                $role = 'operator';
            } else {
                $role = 'public';
            }

            $user = $pemetaan[$role] ?? null;
            if ($role !== 'public' && $user === null) {
                continue;
            }

            $this->loginSebagai($user);
            $uri = '/'.ltrim($route->uri(), '/');

            try {
                $status = $this->get($uri)->getStatusCode();
            } catch (\Throwable $e) {
                $gagal[] = sprintf('GET %s -> %s: %s', $uri, get_class($e), $e->getMessage());
                continue;
            }

            if ($status >= 500) {
                $gagal[] = sprintf('GET %s -> HTTP %d', $uri, $status);
            }
        }

        $this->assertSame([], $gagal, "Halaman error 500:\n".implode("\n", $gagal));
    }
}
