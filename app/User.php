<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Passport\HasApiTokens;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    use Notifiable, HasApiTokens;

    const ROLE_ADMIN = "admin";
    const ROLE_CUSTOMER = "customer";
    const ROLE_SHOP = "shop";

    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'id' => 'integer',
    ];

    public function wallet()
    {
        return $this->hasOne(Wallet::class, 'owner_id');
    }

    public function shop()
    {
        return $this->hasOne(Shop::class, 'user_id');
    }

    /**
     * @return mixed
     * @throws \Exception
     */
    public function getRealWalletOwnerName()
    {
        if($this->role === User::ROLE_CUSTOMER)
            return $this->name;
        if($this->role === User::ROLE_SHOP)
            return $this->shop->name;
        else throw new \Exception('not supported wallet owner for name');
    }

    /**
     * @return mixed
     * @throws \Exception
     */
    public function getCashBackPercent()
    {
        if($this->role === User::ROLE_SHOP)
            return $this->shop->discount2;
        return null;
    }

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }
}
