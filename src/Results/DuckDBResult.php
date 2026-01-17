<?php 
namespace Lucadev\DuckdbPure\Results;

class DuckDBResult {
  public array $result = [];

  public function __construct(array $result) {
    $this->result = $result;
  }

  public function toArray(): array {
    return $this->result;
  }
}