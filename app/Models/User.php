<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

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
        'nim',
        'fakultas',
        'program_studi',
        'organisasi',
        'jabatan',
        'photo_profile',
        'is_active',
    ];

    /**
     * Get the URL for the user's profile photo or fallback to UI Avatars.
     */
    public function getAvatarUrlAttribute(): string
    {
        if ($this->photo_profile && \Illuminate\Support\Facades\Storage::disk('public')->exists($this->photo_profile)) {
            return asset('storage/' . $this->photo_profile);
        }

        return 'https://ui-avatars.com/api/?name=' . urlencode($this->name) . '&background=cbd5e1&color=334155';
    }

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
            'is_active' => 'boolean',
        ];
    }
}
