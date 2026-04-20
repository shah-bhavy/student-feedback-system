<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements MustVerifyEmail
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

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
            'deleted_at' => 'datetime',
        ];
    }

    public static function roles(): array
    {
        return ['admin', 'principal', 'faculty', 'student'];
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isPrincipal(): bool
    {
        return $this->role === 'principal';
    }

    public function isFaculty(): bool
    {
        return $this->role === 'faculty';
    }

    public function isStudent(): bool
    {
        return $this->role === 'student';
    }

    public function dashboardRouteName(): string
    {
        return match ($this->role) {
            'admin' => 'admin.dashboard',
            'principal' => 'principal.dashboard',
            'faculty' => 'faculty.dashboard',
            default => 'student.dashboard',
        };
    }

    public function feedbacks(): HasMany
    {
        return $this->hasMany(Feedback::class, 'student_id');
    }

    public function feedbackDraft(): HasOne
    {
        return $this->hasOne(FeedbackDraft::class, 'student_id');
    }
}
