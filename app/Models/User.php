<?php
namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable;

    protected $fillable = [
        'tenant_id','branch_id','name','email','password',
        'role','is_active','support_online','preferred_language','phone','device_token',
    ];

    protected $hidden = ['password','remember_token'];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password'          => 'hashed',
        'is_active'         => 'boolean',
        'support_online'    => 'boolean',
    ];

    public function hasRole(string ...$roles): bool
    {
        return in_array($this->role, $roles);
    }

    public function isAdmin(): bool { return $this->role === 'admin'; }
    public function isDriver(): bool { return $this->role === 'driver'; }

    public function branch() { return $this->belongsTo(Branch::class); }
    public function tenant() { return $this->belongsTo(Tenant::class); }
}
