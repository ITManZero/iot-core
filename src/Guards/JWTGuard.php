<?php

namespace Ite\IotCore\Guards;


use Illuminate\Auth\GuardHelpers;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Auth\UserProvider;
use Illuminate\Http\Request;
use Ite\IotCore\Models\User;
use Tymon\JWTAuth\JWT;

class JWTGuard extends \Tymon\JWTAuth\JWTGuard
{
    use GuardHelpers;

    public function __construct(JWT $jwt, UserProvider $provider, Request $request)
    {
        parent::__construct($jwt, $provider, $request);
    }

    public function user(): User|Authenticatable|null
    {
        if (!is_null($this->user)) {
            return $this->user;
        }
        if ($this->jwt->setRequest($this->request)->getToken() && $this->jwt->check()) {
            $id = $this->jwt->payload()->get('sub');
            $this->user = new User();
            /** @var User $this user */
            $this->user->id = $id;
            // Set data from custom claims
            return $this->user;
        }
        return null;
    }

    public function validate(array $credentials = [])
    {
    }

}
