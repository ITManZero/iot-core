<?php

namespace Ite\IotCore\Models;

use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Tymon\JWTAuth\Contracts\JWTSubject;

class PayloadUser implements AuthenticatableContract, JWTSubject
{

    /**
     * Id
     * @var int
     */
    public int $id;

    /**
     * Name
     * @var string
     */
    public string $user_name;

    /**
     * Email
     * @var string
     */
    public string $email;

    public array $all_roles;
    public array $all_permissions;

    /**
     * Phone Number
     * @var string | null
     */
    public string|null $phone;

    protected string $rememberTokenName = 'remember_token';

    /**
     * {@inheritDoc}
     * @see AuthenticatableContract::getAuthIdentifierName
     */
    public function getAuthIdentifierName(): string
    {
        return "email";
    }

    /**
     * {@inheritDoc}
     * @see AuthenticatableContract::getAuthIdentifier
     */
    public function getAuthIdentifier()
    {
        return $this->{$this->getAuthIdentifierName()};
    }

    /**
     * {@inheritDoc}
     * @see AuthenticatableContract::getAuthPassword
     */
    public function getAuthPassword(): string
    {
        return '';
    }

    /**
     * {@inheritDoc}
     * @see AuthenticatableContract::getRememberToken
     */
    public function getRememberToken()
    {
        if (!empty($this->getRememberTokenName())) {
            return $this->{$this->getRememberTokenName()};
        }
    }

    /**
     * {@inheritDoc}
     * @see AuthenticatableContract::setRememberToken
     */
    public function setRememberToken($value)
    {
        if (!empty($this->getRememberTokenName())) {
            $this->{$this->getRememberTokenName()} = $value;
        }
    }

    /**
     * {@inheritDoc}
     * @see AuthenticatableContract::getRememberTokenName
     */
    public function getRememberTokenName(): string
    {
        return $this->rememberTokenName;
    }

    public function getKey(): int
    {
        return $this->id;
    }

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return int
     */
    public function getJWTIdentifier(): int
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims(): array
    {
        return ["extra" => json_decode(json_encode($this), true)];
    }
}