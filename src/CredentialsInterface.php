<?php

namespace PFlorek\BasicAuth;

interface CredentialsInterface
{
    /**
     * @return string
     */
    function getUsername();

    /**
     * @return string
     */
    function getPassword();
}