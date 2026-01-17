<?php
namespace JoaoJ\DuckdbPure\Contracts;

use JoaoJ\DuckdbPure\Results\DuckDBResult;

interface ConnectorInterface
{
  public function connect(string $path, array $configs = []);
  public function disconnect();
  public function execute(string $query): bool;
  public function query(string $query, array $params = []): DuckDBResult;
}