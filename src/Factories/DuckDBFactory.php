<?php

namespace JoaoJ\DuckdbPure\Factories;

use JoaoJ\DuckdbPure\Connectors\CliConnector;
use JoaoJ\DuckdbPure\Contracts\ConnectorInterface;

class DuckDBFactory
{
    public static function create(string $path, array $configs = []): ConnectorInterface
    {
        $connector = new CliConnector();
        $connector->connect($path, $configs);
        return $connector;
    }
}
