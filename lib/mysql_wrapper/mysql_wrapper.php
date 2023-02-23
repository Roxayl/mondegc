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
if(!extension_loaded('mysqli')) {
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

// Will contain the link identifier
$__MYSQLI_WRAPPER_LINK = null;

/**
 * Get the link identifier
 *
 * @param mysqli|null $mysqli $mysqli
 * @return mysqli|null
 */
function getLinkIdentifier(mysqli $mysqli = null): ?mysqli
{
    if(!$mysqli) {
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
 * Open a persistent connection to a MySQL server
 *
 * @param string $server
 * @param string $username
 * @param string $password
 * @param bool $new_link
 * @param int $client_flags
 * @return mysqli|null
 */
function mysql_pconnect(string $server, string $username, string $password, bool $new_link = false, int $client_flags = 0): ?mysqli
{
    return mysql_connect($server, $username, $password);
}

/**
 * @param string $databaseName
 * @param mysqli|null $mysqli
 * @return bool
 */
function mysql_select_db(string $databaseName, mysqli $mysqli = null)
{
    return getLinkIdentifier($mysqli)->select_db($databaseName);
}

/**
 * @param string $query
 * @param mysqli|null $mysqli $mysqli
 * @return bool|mysqli_result
 */
function mysql_query(string $query, mysqli $mysqli = null)
{
    return getLinkIdentifier($mysqli)->query($query);
}

/**
 * @param string|null $string
 * @param mysqli $mysqli
 * @return string
 */
function mysql_real_escape_string(?string $string, mysqli $mysqli = null)
{
    return getLinkIdentifier($mysqli)->escape_string($string);
}

/**
 * @param string|null $string
 * @return string
 */
function mysql_escape_string(?string $string)
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
    if($result === NULL) {
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
    if($result === NULL) {
        $result = false;
    }

    return $result;
}

/**
 * @param mysqli_result $result
 * @return bool|int
 */
function mysql_num_rows(mysqli_result $result): bool|int
{
    $result = $result->num_rows;
    if($result === NULL) {
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
    if($result === NULL) {
        $result = false;
    }

    return $result;
}

/**
 * @param mysqli|null $mysqli $mysqli
 * @return int
 */
function mysql_affected_rows(mysqli $mysqli = null): int
{
    return mysqli_affected_rows(getLinkIdentifier($mysqli));
}

/**
 * @param mysqli|null $mysqli
 * @return string
 */
function mysql_client_encoding(mysqli $mysqli = null): string
{
    return mysqli_character_set_name(getLinkIdentifier($mysqli));
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
 * @param string $database_name
 * @param mysqli|null $mysqli
 * @return bool
 */
function mysql_create_db(string $database_name, mysqli $mysqli = null): bool
{
    trigger_error('This function was deprecated in PHP 4.3.0 and is therefor not supported', E_USER_DEPRECATED);
    return false;
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
 * Adjusts the result pointer to an arbitrary row in the result
 *
 * @param mysqli_result $result
 * @param int $row
 * @param null $field
 * @return bool
 */
function mysql_db_name(mysqli_result $result, int $row, $field = null): bool
{
    mysqli_data_seek($result, $row);
    $f = mysqli_fetch_row($result);

    if(is_array($f)) {
        return $f[0];
    }
    return false;
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
 * @param int $result_type
 * @return array|false|null
 */
function mysql_fetch_array(mysqli_result $result, int $result_type = MYSQL_BOTH): array|false|null
{
    return mysqli_fetch_array($result, $result_type);
}

/**
 * @param mysqli|null $mysqli
 * @return bool
 */
function mysql_ping(mysqli $mysqli = null): bool
{
    return mysqli_ping(getLinkIdentifier($mysqli));
}

/**
 * @param string $query
 * @param mysqli|null $mysqli $mysqli
 * @return bool|mysqli_result
 */
function mysql_unbuffered_query(string $query, mysqli $mysqli = null): bool|mysqli_result
{
    return mysqli_query(getLinkIdentifier($mysqli), $query, MYSQLI_USE_RESULT);
}

/**
 * @return string
 */
function mysql_get_client_info()
{
    return mysqli_get_client_info();
}

/**
 * @param mysqli_result $result
 */
function mysql_free_result(mysqli_result $result): void
{
    mysqli_free_result($result);
}

/**
 * @param mysqli|null $mysqli $mysqli
 * @return bool|mysqli_result
 */
function mysql_list_dbs(mysqli $mysqli = null): bool|mysqli_result
{
    trigger_error('This function is deprecated. It is preferable to use mysql_query() to issue an SQL Query: SHOW DATABASES statement instead.', E_USER_DEPRECATED);

    return mysqli_query(getLinkIdentifier($mysqli), 'SHOW DATABASES');
}

/**
 * @param string $database_name
 * @param string $table_name
 * @param mysqli|null $mysqli
 * @return bool|mysqli_result
 */
function mysql_list_fields(string $database_name, string $table_name, mysqli $mysqli = null): bool|mysqli_result
{
    trigger_error('This function is deprecated. It is preferable to use mysql_query() to issue an SQL SHOW COLUMNS FROM table [LIKE \'name\'] statement instead.', E_USER_DEPRECATED);

    $mysqli = getLinkIdentifier($mysqli);
    $db = mysqli_escape_string($mysqli, $database_name);
    $table = mysqli_escape_string($mysqli, $table_name);

    return mysqli_query($mysqli, sprintf('SHOW COLUMNS FROM %s.%s', $db, $table));
}

/**
 * @param mysqli|null $mysqli $mysqli
 * @return bool|mysqli_result
 */
function mysql_list_processes(mysqli $mysqli = null): bool|mysqli_result
{
    return mysqli_query(getLinkIdentifier($mysqli), 'SHOW PROCESSLIST');
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
 * @param mysqli|null $mysqli
 * @return bool|string
 */
function mysql_info(mysqli $mysqli = null): bool|string
{
    $result = mysqli_info(getLinkIdentifier($mysqli));
    if($result === NULL) {
        $result = false;
    }

    return $result;
}

/**
 * Get current system status
 *
 * @param mysqli|null $mysqli
 * @return bool|string
 */
function mysql_stat(mysqli $mysqli = null): bool|string
{
    return mysqli_stat(getLinkIdentifier($mysqli));
}

/**
 * Return the current thread ID
 *
 * @param mysqli|null $mysqli
 * @return int
 */
function mysql_thread_id(mysqli $mysqli = null): int
{
    return mysqli_thread_id(getLinkIdentifier($mysqli));
}

/**
 * Get MySQL host info
 *
 * @param mysqli|null $mysqli
 * @return string
 */
function mysql_get_host_info(mysqli $mysqli = null): string
{
    return mysqli_get_host_info(getLinkIdentifier($mysqli));
}

/**
 * Get MySQL protocol info
 *
 * @param mysqli|null $mysqli
 * @return int
 */
function mysql_get_proto_info(mysqli $mysqli = null): int
{
    return mysqli_get_proto_info(getLinkIdentifier($mysqli));
}

/**
 * Get MySQL server info
 *
 * @param mysqli|null $mysqli
 * @return string
 */
function mysql_get_server_info(mysqli $mysqli = null)
{
    return mysqli_get_server_info(getLinkIdentifier($mysqli));
}

/**
 * Get table name of field
 *
 * @param mysqli_result $result
 * @param int $row
 * @return false|mixed|null
 */
function mysql_tablename(mysqli_result $result, int $row)
{
    mysqli_data_seek($result, $row);
    $f = mysqli_fetch_array($result);

    if(is_array($f)) {
        return $f[0];
    }
    return $f;
}

/**
 * Get the ID generated in the last query
 *
 * @param null $mysqli
 * @return int|string
 */
function mysql_insert_id(mysqli $mysqli = null)
{
    return mysqli_insert_id(getLinkIdentifier($mysqli));
}

/**
 * Get result data
 *
 * @param $result
 * @param $row
 * @param int $field
 * @return mixed
 */
function mysql_result($result, $row, $field = 0)
{
    $result->data_seek($row);
    $row = $result->fetch_array();
    if(!isset($row[$field])) {
        return false;
    }

    return $row[$field];
}

/**
 * Get number of fields in result
 *
 * @param mysqli_result $result
 * @return int
 */
function mysql_num_fields(mysqli_result $result): int
{
    return mysqli_num_fields($result);
}

/**
 * List tables in a MySQL database
 *
 * @param string $database_name
 * @param mysqli|null $mysqli
 * @return bool|mysqli_result
 */
function mysql_list_tables(string $database_name, mysqli $mysqli = null)
{
    trigger_error('This function is deprecated. It is preferable to use mysql_query() to issue an SQL SHOW TABLES [FROM db_name] [LIKE \'pattern\'] statement instead.', E_USER_DEPRECATED);

    $mysqli = getLinkIdentifier($mysqli);
    $db = mysqli_escape_string($mysqli, $database_name);

    return mysqli_query($mysqli, sprintf('SHOW TABLES FROM %s', $db));
}

/**
 *  Get column information from a result and return as an object
 *
 * @param mysqli_result $result
 * @param int $field_offset
 * @return bool|object
 */
function mysql_fetch_field(mysqli_result $result, $field_offset = 0)
{
    if($field_offset) {
        mysqli_field_seek($result, $field_offset);
    }

    return mysqli_fetch_field($result);
}

/**
 * Returns the length of the specified field
 *
 * @param mysqli_result $result
 * @param int $field_offset
 * @return bool
 */
function mysql_field_len(mysqli_result $result, $field_offset = 0)
{
    $fieldInfo = mysqli_fetch_field_direct($result, $field_offset);
    return $fieldInfo->length;
}

/**
 * @return bool
 */
function mysql_drop_db()
{
    trigger_error('This function is deprecated since PHP 4.3.0 and therefore not implemented', E_USER_DEPRECATED);
    return false;
}

/**
 * Move internal result pointer
 *
 * @param mysqli_result $result
 * @param int $row_number
 * @return void
 */
function mysql_data_seek(mysqli_result $result, $row_number = 0)
{
    return mysqli_data_seek($result, $row_number);
}

/**
 * Get the name of the specified field in a result
 *
 * @param $result
 * @param $field_offset
 * @return bool
 */
function mysql_field_name($result, $field_offset = 0)
{
    $props = mysqli_fetch_field_direct($result, $field_offset);
    return is_object($props) ? $props->name : false;
}

/**
 * Get the length of each output in a result
 *
 * @param mysqli_result $result
 * @return array|bool
 */
function mysql_fetch_lengths(mysqli_result $result)
{
    return mysqli_fetch_lengths($result);
}

/**
 * Get the type of the specified field in a result
 * @param mysqli_result $result
 * @param $field_offset
 * @return string
 */
function mysql_field_type(mysqli_result $result, $field_offset = 0)
{
    $unknown = 'unknown';
    $info = mysqli_fetch_field_direct($result, $field_offset);
    if(empty($info->type)) {
        return $unknown;
    }

    switch($info->type) {
        case MYSQLI_TYPE_FLOAT:
        case MYSQLI_TYPE_DOUBLE:
        case MYSQLI_TYPE_DECIMAL:
        case MYSQLI_TYPE_NEWDECIMAL:
            return 'real';

        case MYSQLI_TYPE_BIT:
            return 'bit';

        case MYSQLI_TYPE_TINY:
            return 'tinyint';

        case MYSQLI_TYPE_TIME:
            return 'time';

        case MYSQLI_TYPE_DATE:
            return 'date';

        case MYSQLI_TYPE_DATETIME:
            return 'datetime';

        case MYSQLI_TYPE_TIMESTAMP:
            return 'timestamp';

        case MYSQLI_TYPE_YEAR:
            return 'year';

        case MYSQLI_TYPE_STRING:
        case MYSQLI_TYPE_VAR_STRING:
            return 'string';

        case MYSQLI_TYPE_SHORT:
        case MYSQLI_TYPE_LONG:
        case MYSQLI_TYPE_LONGLONG:
        case MYSQLI_TYPE_INT24:
            return 'int';

        case MYSQLI_TYPE_CHAR:
            return 'char';

        case MYSQLI_TYPE_ENUM:
            return 'enum';

        case MYSQLI_TYPE_TINY_BLOB:
        case MYSQLI_TYPE_MEDIUM_BLOB:
        case MYSQLI_TYPE_LONG_BLOB:
        case MYSQLI_TYPE_BLOB:
            return 'blob';

        case MYSQLI_TYPE_NULL:
            return 'null';

        case MYSQLI_TYPE_NEWDATE:
        case MYSQLI_TYPE_INTERVAL:
        case MYSQLI_TYPE_SET:
        case MYSQLI_TYPE_GEOMETRY:
        default:
            return $unknown;
    }
}

/**
 * Get name of the table the specified field is in
 *
 * @param mysqli_result $result
 * @param $field_offset
 * @return bool
 */
function mysql_field_table(mysqli_result $result, $field_offset = 0)
{
    $info = mysqli_fetch_field_direct($result, $field_offset);
    if(empty($info->table)) {
        return false;
    }

    return $info->table;
}

/**
 * Get the flags associated with the specified field in a result
 *
 * credit to Dave Smith from phpclasses.org, andre at koethur dot de from php.net and NinjaKC from stackoverflow.com
 *
 * @param mysqli_result $result
 * @param int $field_offset
 * @return bool
 */
function mysql_field_flags(mysqli_result $result, $field_offset = 0)
{
    $flags_num = mysqli_fetch_field_direct($result, $field_offset)->flags;

    if(!isset($flags)) {
        $flags = array();
        $constants = get_defined_constants(true);
        foreach($constants['mysqli'] as $c => $n) if(preg_match('/MYSQLI_(.*)_FLAG$/', $c, $m)) if(!array_key_exists($n, $flags)) $flags[$n] = $m[1];
    }

    $result = array();
    foreach($flags as $n => $t) if($flags_num & $n) $result[] = $t;

    $return = implode(' ', $result);
    $return = str_replace('PRI_KEY', 'PRIMARY_KEY', $return);
    $return = strtolower($return);

    return $return;
}

/**
 * Set result pointer to a specified field offset
 *
 * @param mysqli_result $result
 * @param int $field_offset
 * @return bool
 */
function mysql_field_seek(mysqli_result $result, $field_offset = 0)
{
    return mysqli_field_seek($result, $field_offset);
}

/**
 * Selects a database and executes a query on it
 *
 * @param $database
 * @param $query
 * @param mysqli $mysqli
 * @return bool
 * @todo implement
 *
 */
function mysql_db_query($database, $query, mysqli $mysqli = null)
{
    trigger_error('This function is deprecated since PHP 5.3.0 and therefore not implemented', E_USER_DEPRECATED);
    return false;
}
