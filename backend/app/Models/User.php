<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'type',
        'password',
        'verification_code',
        'verification_expires_at',
        'is_verified',
        'reset_token',
        'reset_expires_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
        'verification_code',
        'reset_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'verification_expires_at' => 'datetime',
            'reset_expires_at' => 'datetime',
            'is_verified' => 'boolean',
        ];
    }

    public function listings()
    {
        return $this->hasMany(Listing::class, 'seller_id');
    }

    // Generál 6 számjegyű véletlen kódot
    public function generateVerificationCode(): string
    {
        $code = str_pad((string)random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        $this->verification_code = $code;
        $this->verification_expires_at = now()->addMinutes(30);
        $this->save();
        return $code;
    }

    // Generál jelszó-visszaállítási tokent
    public function generateResetToken(): string
    {
        $token = Str::random(64);
        $this->reset_token = hash('sha256', $token);
        $this->reset_expires_at = now()->addMinutes(60);
        $this->save();
        return $token;
    }

    // Ellenőrzi a megerősítő kódot
    public function verifyCode(string $code): bool
    {
        if ($this->verification_code !== $code) {
            return false;
        }
        if ($this->verification_expires_at->isPast()) {
            return false;
        }
        
        $this->is_verified = true;
        $this->verification_code = null;
        $this->verification_expires_at = null;
        $this->save();
        
        return true;
    }

    // Ellenőrzi a reset tokent
    public function verifyResetToken(string $token): bool
    {
        if (!$this->reset_token || hash('sha256', $token) !== $this->reset_token) {
            return false;
        }
        if ($this->reset_expires_at->isPast()) {
            return false;
        }
        return true;
    }

    // Jelszó visszaállítása
    public function resetPassword(string $newPassword): void
    {
        $this->password = bcrypt($newPassword);
        $this->reset_token = null;
        $this->reset_expires_at = null;
        $this->save();
    }
}