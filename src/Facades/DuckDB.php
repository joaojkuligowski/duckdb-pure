<?php
namespace JoaoJ\DuckdbPure\Facades;

use JoaoJ\DuckdbPure\Contracts\ConnectorInterface;
use JoaoJ\DuckdbPure\Factories\DuckDBFactory;
use JoaoJ\DuckdbPure\Results\DuckDBResult;
use RuntimeException;

class DuckDB
{
    private static ?ConnectorInterface $connector = null;

    /**
     * @throws \JoaoJ\DuckdbPure\Exceptions\DuckDBException
     */
    public static function connect(string $path, array $configs = []): void
    {
        if (self::$connector === null) {
            self::$connector = DuckDBFactory::create($path, $configs);
        }
    }

    /**
     * @throws \JoaoJ\DuckdbPure\Exceptions\DuckDBException
     */
    public static function query(string $query, array $params = []): DuckDBResult
    {
        self::ensureConnected();
        return self::$connector->query($query, $params);
    }

    /**
     * @throws \JoaoJ\DuckdbPure\Exceptions\DuckDBException
     */
    public static function execute(string $query, array $params = []): bool
    {
        self::ensureConnected();
        return self::$connector->execute($query, $params);
    }

    public static function disconnect(): void
    {
        if (self::$connector !== null) {
            self::$connector->disconnect();
            self::$connector = null;
        }
    }

    private static function ensureConnected(): void
    {
        if (self::$connector === null) {
            throw new RuntimeException('Not connected. Please call DuckDB::connect() first.');
        }
    }
}
