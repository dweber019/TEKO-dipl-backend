<?php

namespace App\Providers;

use App\Models\Comment;
use App\Models\Group;
use App\Models\Lesson;
use App\Models\Subject;
use App\Models\Task;
use App\Models\TaskItem;
use App\Models\User;
use App\Policies\CommentPolicy;
use App\Policies\GroupPolicy;
use App\Policies\LessonPolicy;
use App\Policies\SubjectPolicy;
use App\Policies\TaskItemPolicy;
use App\Policies\TaskPolicy;
use App\Policies\UserPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        'App\Model' => 'App\Policies\ModelPolicy',
        Group::class => GroupPolicy::class,
        Subject::class => SubjectPolicy::class,
        Lesson::class => LessonPolicy::class,
        Task::class => TaskPolicy::class,
        Comment::class => CommentPolicy::class,
        TaskItem::class => TaskItemPolicy::class,
        User::class => UserPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        //
    }
}
