<?php

namespace PFlorek\BasicAuth;

use Psr\Http\Message\MessageInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class BasicAuth
{
    /**
     * Obtain username and password
     *
     * @param RequestInterface $request
     * @return CredentialsInterface|null
     */
    public function obtainCredentials($request)
    {
        $header = $request->getHeader('Authorization');

        if (!$header) {
            return null;
        }

        $header = array_shift($header);

        $matches = [];
        if (!preg_match('/Basic (.*)$/', $header, $matches)) {
            return null;
        }

        list($username, $password) = \PFlorek\BasicAuth\retrieve_username_and_password($matches[1]);

        return new Credentials($username, $password);
    }

    /**
     * Add WWW-Authenticate header field to receive authorization.
     *
     * @param RequestInterface $request
     * @param CredentialsInterface $credentials
     * @return ResponseInterface|MessageInterface
     */
    public function addCredentials($request, $credentials)
    {
        $basicCredentials = \PFlorek\BasicAuth\create_basic_credentials($credentials->getUsername(), $credentials->getPassword());

        return $request->withHeader('WWW-Authenticate', "Basic {$basicCredentials}");
    }

    /**
     * Add 401 (Unauthorized) status code and WWW-Authenticate header field to reply with a challenge.
     *
     * @param ResponseInterface $response
     * @param string $realm
     * @return ResponseInterface|MessageInterface
     */
    public function addChallenge($response, $realm) {
        return $response
            ->withStatus(401)
            ->withHeader('WWW-Authenticate', "Basic realm=\"{$realm}\"");
    }
}