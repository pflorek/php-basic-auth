<?php

namespace PFlorek\BasicAuth;

class BasicAuthFactory
{
    public function create()
    {
        return new BasicAuth();
    }
}