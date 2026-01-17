<?php

require 'vendor/autoload.php';

use JoaoJ\DuckdbPure\Facades\DuckDB;
use JoaoJ\DuckdbPure\Exceptions\DuckDBException;

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