<?php 
namespace Lucadev\DuckdbPure\Exceptions;

class DuckDBException extends \Exception {
  public function __construct(string $message) {
    parent::__construct($message);
  }

  public function __toString() {
    return __CLASS__ . ": [{$this->code}]: {$this->message}\n";
  }
}