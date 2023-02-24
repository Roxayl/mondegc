<?php
/**
 * Procedural drop in replacement for legacy projects using the MySQL function
 *
 * @author Sjoerd Maessen
 * @author Roxayl
 * @version 0.2
 */

// Make sure the MySQL extension is not loaded and there is no other drop in replacement active
if(function_exists('mysql_connect') || extension_loaded('mysql')) {
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

// Define MySQL constants
define('MYSQL_CLIENT_COMPRESS', MYSQLI_CLIENT_COMPRESS);
define('MYSQL_CLIENT_IGNORE_SPACE', MYSQLI_CLIENT_IGNORE_SPACE);
define('MYSQL_CLIENT_INTERACTIVE', MYSQLI_CLIENT_INTERACTIVE);
define('MYSQL_CLIENT_SSL', MYSQLI_CLIENT_SSL);

define('MYSQL_ASSOC', MYSQLI_ASSOC);
define('MYSQL_NUM', MYSQLI_NUM);
define('MYSQL_BOTH', MYSQLI_BOTH);

/**
 * Link identifier.
 *
 * @var mysqli|null $__MYSQLI_WRAPPER_LINK
 */
$__MYSQLI_WRAPPER_LINK = null;

/**
 * Get the link identifier
 *
 * @param mysqli|null $mysqli $mysqli
 * @return mysqli|null
 */
function getLinkIdentifier(mysqli $mysqli = null): ?mysqli
{
    if(! ($mysqli instanceof mysqli)) {
        global $__MYSQLI_WRAPPER_LINK;
        $mysqli = $__MYSQLI_WRAPPER_LINK;
    }

    return $mysqli;
}

/**
 * Open a connection to a MySQL Server
 *
 * @param string $server
 * @param string $username
 * @param string $password
 * @param bool $new_link
 * @param int $client_flags
 * @return mysqli|null
 */
function mysql_connect(string $server, string $username, string $password, bool $new_link = false, int $client_flags = 0): ?mysqli
{
    global $__MYSQLI_WRAPPER_LINK;

    if($__MYSQLI_WRAPPER_LINK instanceof mysqli) {
        mysql_close();
    }

    $__MYSQLI_WRAPPER_LINK = mysqli_connect($server, $username, $password);

    return $__MYSQLI_WRAPPER_LINK;
}

/**
 * @param string $databaseName
 * @param mysqli|null $mysqli
 * @return bool
 */
function mysql_select_db(string $databaseName, mysqli $mysqli = null): bool
{
    return getLinkIdentifier($mysqli)->select_db($databaseName);
}

/**
 * @param string $query
 * @param mysqli|null $mysqli $mysqli
 * @return bool|mysqli_result
 */
function mysql_query(string $query, mysqli $mysqli = null): mysqli_result|bool
{
    return getLinkIdentifier($mysqli)->query($query);
}

/**
 * @param string|null $string
 * @param mysqli|null $mysqli $mysqli
 * @return string
 */
function mysql_real_escape_string(?string $string, mysqli $mysqli = null): string
{
    return getLinkIdentifier($mysqli)->escape_string($string);
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
 * @param mysqli_result $result
 * @return bool|array
 */
function mysql_fetch_assoc(mysqli_result $result): bool|array
{
    $result = $result->fetch_assoc();
    if($result === null) {
        $result = false;
    }

    return $result;
}

/**
 * @param mysqli_result $result
 * @return object
 */
function mysql_fetch_object(mysqli_result $result): object
{
    $result = $result->fetch_object();
    if($result === null) {
        $result = false;
    }

    return $result;
}

/**
 * @param mysqli_result $result
 * @return bool|int|string
 */
function mysql_num_rows(mysqli_result $result): bool|int|string
{
    $result = $result->num_rows;
    if($result === null) {
        $result = false;
    }

    return $result;
}

/**
 * @param mysqli_result $result
 * @return bool|array
 */
function mysql_fetch_row(mysqli_result $result): bool|array
{
    $result = $result->fetch_row();
    if($result === null) {
        $result = false;
    }

    return $result;
}

/**
 * @param mysqli|null $mysqli $mysqli
 * @return int|string
 */
function mysql_affected_rows(mysqli $mysqli = null): int|string
{
    return mysqli_affected_rows(getLinkIdentifier($mysqli));
}

/**
 * @param mysqli|null $mysqli $mysqli
 * @return bool
 */
function mysql_close(mysqli $mysqli = null): bool
{
    return mysqli_close(getLinkIdentifier($mysqli));
}

/**
 * @param mysqli|null $mysqli $mysqli
 * @return int
 */
function mysql_errno(mysqli $mysqli = null): int
{
    return mysqli_errno(getLinkIdentifier($mysqli));
}

/**
 * @param mysqli|bool $mysqli
 * @return string
 */
function mysql_error(mysqli|bool $mysqli = null): string
{
    return mysqli_error(getLinkIdentifier($mysqli));
}


/**
 * @param mysqli_result $result
 */
function mysql_free_result(mysqli_result $result): void
{
    mysqli_free_result($result);
}

/**
 * @param string $charset
 * @param mysqli|null $mysqli
 * @return bool
 */
function mysql_set_charset(string $charset, mysqli $mysqli = null): bool
{
    return mysqli_set_charset(getLinkIdentifier($mysqli), $charset);
}

/**
 * Get the ID generated in the last query
 *
 * @param mysqli|null $mysqli
 * @return int|string
 */
function mysql_insert_id(mysqli $mysqli = null): int|string
{
    return mysqli_insert_id(getLinkIdentifier($mysqli));
}

/**
 * Move internal result pointer
 *
 * @param mysqli_result $result
 * @param int $row_number
 * @return bool
 */
function mysql_data_seek(mysqli_result $result, int $row_number = 0): bool
{
    return mysqli_data_seek($result, $row_number);
}
