<?php
namespace Lucadev\DuckdbPure\Facades;

use Lucadev\DuckdbPure\Contracts\ConnectorInterface;
use Lucadev\DuckdbPure\Factories\DuckDBFactory;
use Lucadev\DuckdbPure\Results\DuckDBResult;
use RuntimeException;

class DuckDB
{
    private static ?ConnectorInterface $connector = null;

    /**
     * @throws \Lucadev\DuckdbPure\Exceptions\DuckDBException
     */
    public static function connect(string $path, array $configs = []): void
    {
        if (self::$connector === null) {
            self::$connector = DuckDBFactory::create($path, $configs);
        }
    }

    /**
     * @throws \Lucadev\DuckdbPure\Exceptions\DuckDBException
     */
    public static function query(string $query, array $params = []): DuckDBResult
    {
        self::ensureConnected();
        return self::$connector->query($query, $params);
    }

    /**
     * @throws \Lucadev\DuckdbPure\Exceptions\DuckDBException
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
