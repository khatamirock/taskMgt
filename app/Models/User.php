<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;


class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;
    use HasApiTokens;

    protected $fillable = ['name','email','password','role'];

    public function isAdmin():bool
    {
        return $this->role === 'admin';
    }

    public function projects():BelongsToMany
    {
        return $this-> belongsToMany(Project::class);
    }

    public function tasks():HasMany
    {
        return $this->hasMany(Task::class,'assigned_to'); //lession !! why do we need foreign key...
        // // > $users[1]->tasks()->get()
//    Illuminate\Database\QueryException  SQLSTATE[HY000]: General error: 1 no such column: tasks.user_id (Connection: sqlite, SQL: select * from "tasks" where "tasks"."user_id" = 3 and "tasks"."user_id" is not null).

    }

    public function createdProjects(): HasMany
    {
        return $this->hasMany(Project::class, 'created_by');
    }
    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}
