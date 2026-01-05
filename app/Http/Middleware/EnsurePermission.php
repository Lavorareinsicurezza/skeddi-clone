<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class EnsurePermission
{
    public function handle(Request $request, Closure $next, string $base, ?string $op = null): mixed
    {
        $method = $request->route()?->getActionMethod() ?? '';

        $actionToSuffix = [
            'index' => 'view',
            'show' => 'view',
            'create' => 'create',
            'store' => 'create',
            'edit' => 'edit',
            'update' => 'edit',
            'destroy' => 'delete',
            'save' => 'edit',
            'renew' => 'edit',
            'getDocuments' => 'view',
            'storeDocument' => 'create',
            'deleteDocument' => 'delete',
            'downloadDocument' => 'view',
        ];

        $suffix = $op ?: ($actionToSuffix[$method] ?? 'view');
        $permission = Str::of($suffix . ' ' . $base)->lower()->toString();

        if (! $request->user()?->can($permission)) {
            abort(403);
        }

        return $next($request);
    }
}
