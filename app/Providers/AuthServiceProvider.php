<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;

use App\Models\Note;
use App\Models\NoteGroup;
use App\Models\Task;
use App\Models\TaskGroup;
use App\Policies\NoteGroupPolicy;
use App\Policies\NotePolicy;
use App\Policies\TaskGroupPolicy;
use App\Policies\TaskPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        NoteGroup::class => NoteGroupPolicy::class,
        Note::class => NotePolicy::class,
        TaskGroup::class => TaskGroupPolicy::class,
        Task::class => TaskPolicy::class
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        //
    }
}
