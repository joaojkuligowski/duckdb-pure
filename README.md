# DuckDB Pure PHP

[![Latest Version on Packagist](https://img.shields.io/packagist/v/lucadev/duckdb-pure.svg?style=flat-square)](https://packagist.org/packages/lucadev/duckdb-pure)
[![Tests](https://github.com/lucadev/duckdb-pure/actions/workflows/main.yml/badge.svg)](https://github.com/lucadev/duckdb-pure/actions/workflows/main.yml)
[![Total Downloads](https://img.shields.io/packagist/dt/lucadev/duckdb-pure.svg?style=flat-square)](https://packagist.org/packages/lucadev/duckdb-pure)

A pure PHP connector for [DuckDB](https://duckdb.org/), the in-process analytical database. This library interacts with the DuckDB CLI application, offering a simple and dependency-light way to run queries from your PHP projects.

## Requirements

- PHP 8.1+
- [DuckDB CLI](https://duckdb.org/docs/api/cli.html) installed and available in your system's PATH.

## Installation

You can install the package via Composer:

```bash
composer require lucadev/duckdb-pure
```

## Usage

This library can be used via the `DuckDB` facade, which provides a simple, static interface for interacting with the database.

### Connecting to the Database

First, connect to your database file. The connection is managed as a singleton. If the file does not exist, DuckDB will create it.

```php
use Lucadev\DuckdbPure\Facades\DuckDB;

DuckDB::connect('/path/to/your/database.duckdb');
```

### Running Queries

Use the `query()` method for `SELECT` statements. This method returns a `DuckDBResult` object.

```php
$result = DuckDB::query('SELECT * FROM users WHERE name = :name', [
    'name' => 'Luca'
]);

// Get the results as an array
$users = $result->toArray();

print_r($users);
```

### Executing Statements

For statements that do not return a result set (e.g., `CREATE`, `INSERT`, `UPDATE`), use the `execute()` method.

```php
// Create a table
DuckDB::execute(
    "CREATE TABLE users (id INTEGER, name VARCHAR);"
);

// Insert data
DuckDB::execute(
    "INSERT INTO users VALUES (1, 'Luca'), (2, 'John');"
);
```

### Disconnecting

The connection can be manually closed if needed.

```php
DuckDB::disconnect();
```

### Example

Here is a complete example of how to use the facade:

```php
<?php

require 'vendor/autoload.php';

use Lucadev\DuckdbPure\Facades\DuckDB;
use Lucadev\DuckdbPure\Exceptions\DuckDBException;

try {
    // 1. Connect to the database
    $dbFile = 'mydatabase.duckdb';
    DuckDB::connect($dbFile);

    echo "Connection successful.\n";

    // 2. Create a table (or ensure it exists)
    DuckDB::execute(
        "CREATE TABLE IF NOT EXISTS cities (name VARCHAR, country VARCHAR);"
    );

    // 3. Insert some data (idempotent)
    DuckDB::execute("DELETE FROM cities;"); // Clear previous data for this example
    DuckDB::execute(
        "INSERT INTO cities VALUES ('Amsterdam', 'Netherlands'), ('Prague', 'Czech Republic');"
    );

    echo "Data inserted.\n";

    // 4. Query the data
    $result = DuckDB::query('SELECT * FROM cities WHERE country = :country', [
        'country' => 'Netherlands'
    ]);

    $data = $result->toArray();

    echo "Query results:\n";
    print_r($data);

    // 5. Disconnect and clean up
    DuckDB::disconnect();
    unlink($dbFile);


} catch (DuckDBException | RuntimeException $e) {
    echo "An error occurred: " . $e->getMessage() . "\n";
}
```

## Testing

To run the test suite, you will need to have `phpunit` installed as a dev dependency:

```bash
composer install --dev
vendor/bin/phpunit
```

## Contributing

Contributions are welcome! Please feel free to submit a pull request or open an issue for any bugs or feature requests.

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
file_path:
README.md