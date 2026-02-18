<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens;
    use HasFactory;
    use HasProfilePhoto;
    use Notifiable;
    use TwoFactorAuthenticatable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'phone',
        'google_id',
        'google_token',
        'google_refresh_token',
        'avatar',
        'password',
        'rol',
        'role_id',
        'evento_id', // Nuevo: Evento asignado (solo staff)
        'permissions', // Sobreescritura de permisos por usuario
    ];
    
    // RELACIÓN CON ROLE
    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    // RELACIÓN CON EVENTO (Para Staff)
    public function evento()
    {
        return $this->belongsTo(Evento::class);
    }

    // RELACIÓN CON TICKETS (Usuario)
    public function tickets()
    {
        return $this->hasMany(Ticket::class);
    }
    
    // COMPATIBILIDAD CON CÓDIGO VIEJO ($user->rol)
    // Cuando se pida $user->rol, devolvemos el nombre del rol asociado
    public function getRolAttribute($value)
    {
        return $this->role ? $this->role->name : $value;
    }

    // CHECK PERMISSION
    public function hasPermission($permission)
    {
        // 1. Check User specific override
        if ($this->permissions && array_key_exists($permission, $this->permissions)) {
             return $this->permissions[$permission];
        }

        // 2. Check Role permissions
        if (!$this->role || !$this->role->permissions) {
            return false;
        }
        return $this->role->permissions[$permission] ?? false;
    }


    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'two_factor_secret',
        'two_factor_recovery_codes',
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
            'permissions' => 'array',
        ];
    }

    /**
     * Get the user's initials
     */
    public function initials(): string
    {
        return Str::of($this->name)
            ->explode(' ')
            ->take(2)
            ->map(fn ($word) => Str::substr($word, 0, 1))
            ->implode('');
    }
    /**
     * FUNCIÓN HELPER: Para preguntar fácil si es admin
     * Uso: if ($user->isAdmin()) { ... }
     */
    public function isAdmin()
    {
        return $this->rol === 'admin'; // Usa el accesor getRolAttribute
    }

    /**
     * FUNCIÓN HELPER: Para preguntar si es Staff (opcional)
     */
    public function isStaff()
    {
        return $this->rol === 'staff';
    }

    /**
     * HELPER: Determina si el usuario tiene un ticket activo ('adentro' o 'afuera')
     * para mostrar el menú "Mi Pase" y gestionar reingresos o invitados.
     */
    public function hasExitTicket()
    {
        return $this->tickets()->whereIn('estado', ['adentro', 'afuera'])->exists();
    }
}
