<?php

namespace Ite\IotCore\Guards;

use Illuminate\Contracts\Auth\Authenticatable;
use Ite\IotCore\Models\PayloadUser;
use Tymon\JWTAuth\JWTGuard;


class AdminJWTGuard extends JWTGuard
{
    /**
     * Create a token for a user.
     *
     * @param $user
     * @return string
     * @throws \JsonMapper_Exception
     */
    public function login($user): string
    {
        $mapper = new \JsonMapper();
        $mapper->bStrictNullTypes = false;
        $token = $this->jwt->fromUser($mapper->map(json_decode(json_encode($user)), new PayloadUser()));
        $this->setToken($token)->setUser($user);
        return $token;
    }

    public function user(): ?Authenticatable
    {
        if ($this->user !== null) {
            return $this->user;
        }

        if ($this->jwt->setRequest($this->request)->getToken() &&
            ($payload = $this->jwt->check(true))) {
            dd($payload);
            return $this->user = $this->provider->retrieveById($payload['sub']);
        }
        return null;
    }
}
