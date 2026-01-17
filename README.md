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

The library provides a straightforward API for connecting to a DuckDB database, executing queries, and fetching results.

### Connecting to the Database

First, instantiate the `CliConnector` and connect to your database file. If the file does not exist, DuckDB will create it.

```php
use Lucadev\DuckdbPure\Connectors\CliConnector;

$connector = new CliConnector();
$connector->connect('/path/to/your/database.duckdb');
```

### Running Queries

Use the `query()` method for `SELECT` statements that return results. The method returns a `DuckDBResult` object.

```php
// Assuming $connector is your connected instance
$result = $connector->query('SELECT * FROM users WHERE name = :name', [
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
$connector->execute(
    "CREATE TABLE users (id INTEGER, name VARCHAR);"
);

// Insert data
$connector->execute(
    "INSERT INTO users VALUES (1, 'Luca'), (2, 'John');"
);
```

### Example

Here is a complete example of how to use the library:

```php
<?php

require 'vendor/autoload.php';

use Lucadev\DuckdbPure\Connectors\CliConnector;
use Lucadev\DuckdbPure\Exceptions\DuckDBException;

try {
    // 1. Connect to the database
    $dbFile = 'mydatabase.duckdb';
    $connector = new CliConnector();
    $connector->connect($dbFile);

    echo "Connection successful.\n";

    // 2. Create a table (or ensure it exists)
    $connector->execute(
        "CREATE TABLE IF NOT EXISTS cities (name VARCHAR, country VARCHAR);"
    );

    // 3. Insert some data (idempotent)
    $connector->execute("DELETE FROM cities;"); // Clear previous data for this example
    $connector->execute(
        "INSERT INTO cities VALUES ('Amsterdam', 'Netherlands'), ('Prague', 'Czech Republic');"
    );

    echo "Data inserted.\n";

    // 4. Query the data
    $result = $connector->query('SELECT * FROM cities WHERE country = :country', [
        'country' => 'Netherlands'
    ]);

    $data = $result->toArray();

    echo "Query results:\n";
    print_r($data);

    // 5. Clean up the database file
    unlink($dbFile);


} catch (DuckDBException $e) {
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