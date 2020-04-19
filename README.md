# php-db
A simple PDO wrapper intended for MySQL interactions

This is a work in progress. The documentation and code comments are sparse.

I started this as a helper for my PHP projects. PDO and MySQLi are too heavy to use directly every time you want to query a MySQL database. I also really wanted something that throws exceptions when there is a database error.

# Get Started
```
<?php

require 'php-db/db.php';

// Establish a new database connection
$db = new \chrismcgahan\Db([
    'host' => 'localhost',
    'name' => 'test',
    'user' => 'root',
    'pass' => 'password'
]);


// Create a new table
$db->query('
    CREATE TABLE foo(
        id INT NOT NULL AUTO_INCREMENT,
        data VARCHAR(100),
        PRIMARY KEY(id)
    )
');


// Insert a new record and return the auto insert id
$insertId = $db->insert('foo', ['data' => 'bar']);


// Update one or more records and return the number of affected rows
// $db->update($tableName, $updateArray, $whereArray)
$affectedRows = $db->update('foo', ['data' => 'foobar'], ['id' => 1]);


// Run a basic query and return a single value
$count = $db->getOne('SELECT COUNT(*) FROM foo');


// Run a query and return a single row as an associative array
$firstRow = $db->getRow('SELECT * FROM foo ORDER BY id ASC LIMIT 1');


// Run a query and return all rows as an array of associative arrays
$allRows = $db->getAll('SELECT * FROM foo');


// Trigger an exception because "everything" is an unkown column
$db->query('SELECT everything FROM foo');
```
