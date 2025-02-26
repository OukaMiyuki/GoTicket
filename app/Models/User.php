<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use App\Models\UserDetail;
use App\Models\Location;
use App\Models\Invoice;
use App\Models\Ticket;
use App\Models\Voucher;

class User extends Authenticatable {
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'phone',
        'email',
        'password',
        'role',
        'status'
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
    protected function casts(): array {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function userDetail(): HasOne {
        return $this->hasOne(UserDetail::class, 'userId');
    }

    public function locations(): HasMany {
        return $this->hasMany(Location::class, 'userId');
    }

    public function invoices(): HasMany {
        return $this->hasMany(Invoice::class, 'userId');
    }

    public function tickets(): HasMany {
        return $this->hasMany(Ticket::class, 'userId');
    }

    public function location(): BelongsToMany {
        return $this->belongsToMany(Location::class, 'locations_users', 'userId', 'locationId');
    }

    public function vouchers(): HasMany {
        return $this->hasMany(Voucher::class, 'userId');
    }

    protected static function booted() {
        static::created(function ($model) {
            $model->createUserDetail($model);
        });
    }

    private function createUserDetail($model){
        UserDetail::create([
            'userId' => $model->id,
            'jenis_kelamin' => request()->jenis_kelamin
        ]);
    }
}
