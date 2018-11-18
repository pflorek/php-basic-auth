<?php

namespace PFlorek\BasicAuth;

function create_basic_credentials($username, $password)
{
    $userPass = "{$username}:{$password}";
    $basicCredentials = base64_encode($userPass);

    return $basicCredentials;
}

function retrieve_username_and_password($basicCredentials)
{
    $userPass = base64_decode($basicCredentials);

    return explode(':', $userPass) + ['', ''];
}
