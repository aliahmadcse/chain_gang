<?php
function db_connect()
{
    $connection = new mysqli(DB_SERVER, DB_USER, DB_PASS, DB_NAME);
    confirm_db_connect($connection);
    return $connection;
}

function confirm_db_connect($connection)
{
    if ($connection->connect_errno) {
        $msj = "Databased connection failed: ";
        $msj .= $connection->connect_error;
        $msj .= " (" . $connection->connect_errno . ")";
        exit($msj);
    }
}

function db_disconnect($connection)
{
    if (isset($connection)) {
        $connection->close();
    }
}
