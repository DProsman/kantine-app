<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'rol',
        'werkt_bij_kantine',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'werkt_bij_kantine' => 'boolean',
        ];
    }

    public function bestellingen(): HasMany
    {
        return $this->hasMany(Bestelling::class);
    }

    public function isPersoneel(): bool
    {
        return $this->rol === 'personeel';
    }

    public function isBeheerder(): bool
    {
        return $this->rol === 'beheerder';
    }

    /**
     * Personeel krijgt korting. Studenten met een bijbaan in de kantine ook
     * (afspraak met de facilitair manager, zie mail van 3 september).
     */
    public function krijgtPersoneelskorting(): bool
    {
        return $this->isPersoneel() || $this->werkt_bij_kantine;
    }
}
