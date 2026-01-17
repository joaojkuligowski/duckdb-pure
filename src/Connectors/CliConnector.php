<?php 
namespace JoaoJ\DuckdbPure\Connectors;

use JoaoJ\DuckdbPure\Contracts\ConnectorInterface;
use JoaoJ\DuckdbPure\Exceptions\DuckDBException;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;
use JoaoJ\DuckdbPure\Results\DuckDBResult;

class CliConnector implements ConnectorInterface
{
  protected string $path;
  protected array $configs = [];
  protected DuckDBResult $output;

  private string $finalQuery = '';

  public function connect(string $path, array $configs = []): bool {
    $this->path = $path;
    $this->configs = $configs;
    return true;
  }

  private function formatAndEscapeQuery(string $query, array $params = []): string {
    if (empty($params)) {
        return $query;
    }

    $sqliteMemo = new \Sqlite3(':memory:');

    foreach ($params as $key => $value) {
        if (is_string($value)) {
            $escapedValue = $sqliteMemo->escapeString($value);
            $query = str_replace(':' . $key, "'" . $escapedValue . "'", $query);
        } else {
            $query = str_replace(':' . $key, $value, $query);
        }
    }

    return $query;
}

  private function exec(string $query, array $params = []): array {
    $query = $this->formatAndEscapeQuery($query, $params);
    $formatConfigs = [];
    foreach ($this->configs as $key => $value) {
      $formatConfigs['--' . $key] = $value;
    }

    $implodedConfigs = implode(' ', $formatConfigs);

    $duckdbCommand = [
      'duckdb',
      $this->path,
      $query,
      $implodedConfigs,
      '--json',
    ];

    $exec = new Process($duckdbCommand);
    $exec->run();

    if (!$exec->isSuccessful()) {
      $message = $exec->getErrorOutput();
      throw new DuckDBException($message);
    }

    $output = $exec->getOutput();

    return json_decode($output ?? '{}', true) ?? [];
  }

  public function execute(string $query, array $params = []): bool {
    $this->exec($query, $params);
    return true;
  }

  public function query(string $query, array $params = []): DuckDBResult {
    $this->finalQuery = $this->formatAndEscapeQuery($query, $params);
    return new DuckDBResult($this->exec($this->finalQuery));
  }

  public function disconnect() {
    return true;
  }
  
}