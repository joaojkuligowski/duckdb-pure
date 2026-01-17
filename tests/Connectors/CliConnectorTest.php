<?php 
declare(strict_types=1);

use JoaoJ\DuckdbPure\Contracts\ConnectorInterface;
use JoaoJ\DuckdbPure\Connectors\CliConnector;
use PHPUnit\Framework\TestCase;

final class CliConnectorTest extends TestCase {
  public function testConnectorInterface() {
    $connector = new CliConnector();
    $this->assertInstanceOf(ConnectorInterface::class, $connector);
  }

  public function testConnect() {
    $connector = new CliConnector();
    $connector->connect(':memory:');

    $this->assertTrue($connector->connect(':memory:'));
  }

  public function testDisconnect() {
    $connector = new CliConnector();
    $this->assertTrue($connector->disconnect());
  }

  public function testExecute() {
    $connector = new CliConnector();
    $connector->connect(':memory:');
    $this->assertTrue($connector->execute('SELECT 1=1 as "result";'));
  }

  public function testQuery() {
    $connector = new CliConnector();
    $connector->connect(__DIR__ . '/test.db');

    $connector->execute('CREATE TABLE IF NOT EXISTS test (id int);');
    $connector->execute('DELETE FROM test;');
    $connector->execute('INSERT INTO test (id) VALUES (1);');
    $connector->execute('INSERT INTO test (id) VALUES (2);');

    $result = $connector->query('SELECT COUNT(*) as "result" FROM test;');

    $result = $result->toArray()[0];

    $this->assertEquals(['result' => 2], $result);
  }

  public function testQueryWithParams() {
    $connector = new CliConnector();
    $connector->connect(__DIR__ . '/test.db');

    $connector->execute('CREATE TABLE IF NOT EXISTS test (id int);');
    $connector->execute('DELETE FROM test;');
    $connector->execute('INSERT INTO test (id) VALUES (:id);', ['id' => 1]);

    $result = $connector->query('SELECT COUNT(*) as "result" FROM test;');

    $result = $result->toArray()[0];

    $this->assertEquals(['result' => 1], $result);
  }
}