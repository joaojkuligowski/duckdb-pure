<?php

namespace Tests\Facades;

use JoaoJ\DuckdbPure\Facades\DuckDB;
use PHPUnit\Framework\TestCase;
use RuntimeException;

class DuckDBTest extends TestCase
{
    private string $dbFile;

    protected function setUp(): void
    {
        parent::setUp();
        $this->dbFile = tempnam(sys_get_temp_dir(), 'duckdb_test') . '.duckdb';
    }

    protected function tearDown(): void
    {
        DuckDB::disconnect();
        if (file_exists($this->dbFile)) {
            unlink($this->dbFile);
        }
        parent::tearDown();
    }

    public function test_facade_can_connect_execute_and_query()
    {
        DuckDB::connect($this->dbFile);

        DuckDB::execute('CREATE TABLE test (id INTEGER, name VARCHAR)');
        DuckDB::execute("INSERT INTO test VALUES (1, 'default')");

        $result = DuckDB::query('SELECT * FROM test WHERE id = :id', ['id' => 1]);

        $this->assertSame([['id' => 1, 'name' => 'default']], $result->toArray());
    }

    public function test_calling_query_before_connecting_throws_exception()
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Not connected. Please call DuckDB::connect() first.');

        DuckDB::query('SELECT 1');
    }

    public function test_disconnect_clears_the_connection()
    {
        DuckDB::connect($this->dbFile);
        DuckDB::disconnect();

        $this->expectException(RuntimeException::class);
        DuckDB::query('SELECT 1');
    }
}
