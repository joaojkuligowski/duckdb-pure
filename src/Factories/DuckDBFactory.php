<?php

namespace Lucadev\DuckdbPure\Factories;

use Lucadev\DuckdbPure\Connectors\CliConnector;
use Lucadev\DuckdbPure\Contracts\ConnectorInterface;

class DuckDBFactory
{
    public static function create(string $path, array $configs = []): ConnectorInterface
    {
        $connector = new CliConnector();
        $connector->connect($path, $configs);
        return $connector;
    }
}
