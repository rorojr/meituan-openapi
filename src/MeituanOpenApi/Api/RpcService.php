<?php

namespace MeituanOpenApi\Api;

use MeituanOpenApi\Config\Config;
use MeituanOpenApi\Protocol\RpcClient;

class RpcService
{
    protected $client;

    public function __construct($token, Config $config)
    {
        $this->client = new RpcClient($token, $config);
    }
}