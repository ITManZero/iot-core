<?php

namespace Ite\IotCore\Guards;


use BadMethodCallException;
use Illuminate\Auth\GuardHelpers;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\Auth\UserProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Traits\Macroable;
use Ite\IotCore\Models\PayloadUser;
use JsonMapper;
use JsonMapper_Exception;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\UserNotDefinedException;
use Tymon\JWTAuth\JWT;
use Tymon\JWTAuth\Payload;

class JWTGuard implements Guard
{
    use GuardHelpers, Macroable {
        __call as macroCall;
    }

    /**
     * The user we last attempted to retrieve.
     *
     * @var Authenticatable
     */
    protected Authenticatable $lastAttempted;

    /**
     * The JWT instance.
     *
     * @var JWT
     */
    protected JWT $jwt;

    /**
     * The request instance.
     *
     * @var Request
     */
    protected Request $request;

    /**
     * Instantiate the class.
     *
     * @param JWT $jwt
     * @param Request $request
     * @return void
     */
    public function __construct(JWT $jwt, Request $request)
    {
        $this->jwt = $jwt;
        $this->request = $request;
        $this->provider = null;
    }

    /**
     * Get the currently authenticated user.
     *
     * @return Authenticatable|null
     * @throws JsonMapper_Exception
     */
    public function user(): ?Authenticatable
    {
        if (!is_null($this->user)) {
            return $this->user;
        }
        if ($this->jwt->setRequest($this->request)->getToken())
            dd($this->jwt->getToken());
        if ($this->jwt->setRequest($this->request)->getToken()
            && $payload = $this->jwt->check(true)) {
            dd($payload);
            $mapper = new JsonMapper();
            $mapper->bStrictNullTypes = false;
            return $this->user = $mapper->map((object)$payload->get('extra'), new PayloadUser());
        }
        return null;
    }

    /**
     * Get the currently authenticated user or throws an exception.
     *
     * @return Authenticatable
     *
     * @throws UserNotDefinedException|JsonMapper_Exception
     */
    public function userOrFail(): Authenticatable
    {
        if (!$user = $this->user()) {
            throw new UserNotDefinedException;
        }

        return $user;
    }

    /**
     * Validate a user's credentials.
     *
     * @param array $credentials
     * @return bool
     */
    public function validate(array $credentials = []): bool
    {
        if (empty($credentials['token'])) {
            return false;
        }
        return $this->jwt->setToken($credentials['token'])->check();
    }

    /**
     * Logout the user, thus invalidating the token.
     *
     * @param bool $forceForever
     * @return void
     * @throws JWTException
     */
    public function logout(bool $forceForever = false): void
    {
        $this->requireToken()->invalidate($forceForever);

        $this->user = null;
        $this->jwt->unsetToken();
    }

    /**
     * Invalidate the token.
     *
     * @param bool $forceForever
     * @return JWT
     * @throws JWTException
     */
    public function invalidate(bool $forceForever = false): JWT
    {
        return $this->requireToken()->invalidate($forceForever);
    }

    /**
     * Get the raw Payload instance.
     *
     * @return Payload
     * @throws JWTException
     */
    public function getPayload(): Payload
    {
        return $this->requireToken()->getPayload();
    }

    /**
     * Alias for getPayload().
     *
     * @return Payload
     * @throws JWTException
     */
    public function payload(): Payload
    {
        return $this->getPayload();
    }

    /**
     * Get the user provider used by the guard.
     *
     * @return UserProvider|null
     */
    public function getProvider(): ?UserProvider
    {
        return $this->provider;
    }

    /**
     * Set the user provider used by the guard.
     *
     * @param UserProvider $provider
     * @return $this
     */
    public function setProvider(UserProvider $provider): static
    {
        $this->provider = $provider;

        return $this;
    }

    /**
     * Return the currently cached user.
     *
     * @return Authenticatable|null
     */
    public function getUser(): ?Authenticatable
    {
        return $this->user;
    }

    /**
     * Get the current request instance.
     *
     * @return Request
     */
    public function getRequest(): Request
    {
        return $this->request ?: Request::createFromGlobals();
    }

    /**
     * Set the current request instance.
     *
     * @param Request $request
     * @return $this
     */
    public function setRequest(Request $request): static
    {
        $this->request = $request;

        return $this;
    }

    /**
     * Get the last user we attempted to authenticate.
     *
     * @return Authenticatable
     */
    public function getLastAttempted(): Authenticatable
    {
        return $this->lastAttempted;
    }


    /**
     * Ensure that a token is available in the request.
     *
     * @return JWT
     *
     * @throws JWTException
     */
    protected function requireToken(): JWT
    {
        if (!$this->jwt->setRequest($this->getRequest())->getToken()) {
            throw new JWTException('Token could not be parsed from the request.');
        }

        return $this->jwt;
    }

    /**
     * Magically call the JWT instance.
     *
     * @param string $method
     * @param array $parameters
     * @return mixed
     *
     * @throws \BadMethodCallException
     */
    public function __call($method, $parameters)
    {
        if (method_exists($this->jwt, $method)) {
            return call_user_func_array([$this->jwt, $method], $parameters);
        }

        if (static::hasMacro($method)) {
            return $this->macroCall($method, $parameters);
        }

        throw new BadMethodCallException("Method [$method] does not exist.");
    }
}
