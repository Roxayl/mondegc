<?php
/**
 * Procedural drop in replacement for legacy projects using the MySQL function
 *
 * @author Sjoerd Maessen
 * @author Roxayl
 * @version 0.2
 */

use Illuminate\Database\Connection;
use Illuminate\Support\Str;

// Make sure the MySQL extension is not loaded and there is no other drop in replacement active
if(function_exists('mysql_query') || extension_loaded('mysql')) {
    return;
}

// Validate if the MySQLi extension is present
if(! extension_loaded('mysqli')) {
    trigger_error('The extension "MySQLi" is not available', E_USER_ERROR);
}

// The function name "getLinkIdentifier" will be used to return a valid link_indentifier, make it is available
if(function_exists('getLinkIdentifier')) {
    trigger_error('The function name "getLinkIdentifier" is already defined, please change the function name', E_USER_ERROR);
}

/**
 * Link identifier.
 *
 * @var mixed $__MYSQLI_WRAPPER_LINK
 */
$__MYSQLI_WRAPPER_LINK = null;

/**
 * Last executed statement.
 *
 * @var PDOStatement|null $__LEGACY_LAST_STMT
 */
$__LEGACY_LAST_STMT = null;

/**
 * Get the link identifier
 *
 * @return Connection
 */
function getLinkIdentifier(): Connection
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
    global $__LEGACY_LAST_STMT;
    $query = DB::raw($query);
    $__LEGACY_LAST_STMT = getLinkIdentifier()->getPdo()->query($query);
    return $__LEGACY_LAST_STMT;
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

    $string = getLinkIdentifier()->getPdo()->quote($string);
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
 * @param PDO $pdo
 * @return bool
 */
function mysql_close(PDO $pdo): bool
{
    throw new Exception("Not implemented.");
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
 * @param string $charset
 * @param mixed|null $resource Unused and kept for compatibility purposes.
 * @return bool
 */
function mysql_set_charset(string $charset, mixed $resource = null): bool
{
    return getLinkIdentifier()->getPdo()->exec('set names' . getLinkIdentifier()->getPdo()->quote($charset));
}

/**
 * Get the ID generated in the last query.
 *
 * @param mixed|null $resource Unused and kept for compatibility purposes.
 * @return false|string
 */
function mysql_insert_id(mixed $resource = null): false|string
{
    return getLinkIdentifier()->getPdo()->lastInsertId();
}

/**
 * Move internal result pointer
 *
 * @param PDOStatement $statement Unused and kept for compatibility purposes.
 * @param int $row_number
 * @return bool
 */
function mysql_data_seek(PDOStatement $statement, int $row_number = 0): bool
{
    return false; // TODO.
}
