<?php

namespace App\Providers;

use App\Article;
use App\Models\User;
use App\Policies\ArticlePolicy;
use Illuminate\Auth\Access\Response;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        Gate::before(function (User $user, string $ability) {
            if ($user->isModerator()) {
                return true;
            }

            return null;
        });


        Gate::policy(
            Article::class,
            ArticlePolicy::class
        );


        Gate::define('create-comment', function (User $user) {
            if ($user->role?->name === 'reader') {
                return Response::allow();
            }

            return Response::deny(
                'Добавлять комментарии может только читатель или модератор.'
            );
        });


        Gate::define('delete-comment', function (User $user) {
            return Response::deny(
                'Удалять комментарии может только модератор.'
            );
        });
    }
}