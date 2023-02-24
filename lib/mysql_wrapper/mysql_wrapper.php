<?php

use Barryvdh\Debugbar\Facades\Debugbar;
use Illuminate\Database\Connection;
use Illuminate\Support\Str;

// Make sure the MySQL extension is not loaded and there is no other drop in replacement active.
if(function_exists('mysql_query') || extension_loaded('mysql')) {
    return;
}

// The function name "getDatabaseLegacyConnection" will be used to return the database connection,
// make sure it is available.
if(function_exists('getDatabaseLegacyConnection')) {
    trigger_error('The function name "getDatabaseLegacyConnection" is already defined, please change '
        . 'the function name', E_USER_ERROR);
}

/**
 * Last executed statement.
 *
 * @var PDOStatement|null $_DB_LEGACY_LAST_STMT
 */
$_DB_LEGACY_LAST_STMT = null;

/**
 * Get the database legacy connection.
 *
 * @return Connection
 */
function getDatabaseLegacyConnection(): Connection
{
    return DB::connection('mysql_legacy');
}

/**
 * @param string $query
 * @param mixed $resource Unused and kept for compatibility purposes.
 * @return bool|PDOStatement
 */
function mysql_query(string $query, mixed $resource = null): PDOStatement|bool
{
    global $_DB_LEGACY_LAST_STMT, $_DEBUGBAR_ENABLED;

    $query = trim($query);

    if($_DEBUGBAR_ENABLED) {
        $start = hrtime(true);
    }

    $_DB_LEGACY_LAST_STMT = getDatabaseLegacyConnection()->getPdo()->query($query);

    if($_DEBUGBAR_ENABLED) {
        $end = hrtime(true);
        $eta = round(($end - $start) / 1e+6, 2);
        Debugbar::debug("Running legacy query (in {$eta}ms): $query");
    }

    return $_DB_LEGACY_LAST_STMT;
}

/**
 * @param string|null $string
 * @param mixed|null $resource Unused and kept for compatibility purposes.
 * @return string|false|null
 */
function mysql_real_escape_string(?string $string, mixed $resource = null): null|string|false
{
    if($string === null) {
        return null;
    }

    $string = getDatabaseLegacyConnection()->getPdo()->quote($string);
    if(Str::startsWith($string, ["'", '"'])) {
        $string = substr($string, 1);
    }
    if(Str::endsWith($string, ["'", '"'])) {
        $string = substr($string, 0, -1);
    }

    return $string;
}

/**
 * @param string|null $string
 * @return string
 */
function mysql_escape_string(?string $string): string
{
    return mysql_real_escape_string($string);
}

/**
 * @param PDOStatement $result
 * @return mixed
 */
function mysql_fetch_assoc(PDOStatement $result): mixed
{
    return $result->fetch(PDO::FETCH_ASSOC);
}

/**
 * @param PDOStatement $statement
 * @return int
 */
function mysql_num_rows(PDOStatement $statement): int
{
    return $statement->rowCount();
}

/**
 * @param mixed|null $resource Unused and kept for compatibility purposes.
 * @return string
 */
function mysql_error(mixed $resource = null): string
{
    throw new Exception("A database exception occurred.");
}

/**
 * @param PDOStatement $statement
 */
function mysql_free_result(PDOStatement $statement): void
{
    $statement->closeCursor();
}

/**
 * Get the ID generated in the last query.
 *
 * @param mixed|null $resource Unused and kept for compatibility purposes.
 * @return false|string
 */
function mysql_insert_id(mixed $resource = null): false|string
{
    return getDatabaseLegacyConnection()->getPdo()->lastInsertId();
}
