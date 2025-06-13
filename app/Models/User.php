<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
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
        'jira_email',
        'jira_api_key',
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

    protected $encrypted = [
        'google_token',
        'google_refresh_token',
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
            'google_token_expires_at' => 'datetime',
            'google_token' => 'encrypted',
            'google_refresh_token' => 'encrypted',
            'jira_api_key' => 'encrypted',

        ];
    }

    public function hasGoogleCalendar(): bool
    {
        return filled($this->google_refresh_token);
    }

    public function forgetGoogleCalendar(): void
    {
        $this->forceFill([
            'google_id'              => null,
            'google_token'           => null,
            'google_refresh_token'   => null,
            'google_token_expires_at'=> null,
        ])->save();
    }

}
