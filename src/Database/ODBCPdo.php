<?php

namespace DreamFactory\Core\Hadoop\Database;

use Exception;
use DreamFactory\Core\Exceptions\InternalServerErrorException;
use PDO;

class ODBCPdo extends PDO
{
    protected $connection;

    public function __construct($dsn, $username, $passwd, $options = [])
    {
        if (!function_exists('odbc_connect')){
            throw new InternalServerErrorException('could not find driver');
        } else {
            $this->setConnection(odbc_connect($dsn, $username, $passwd));
        }
    }

    public function exec($query): int|false
    {
        return $this->prepare($query)->execute();
    }

    public function prepare($statement, $driver_options = null): ODBCPdoStatement
    {
        return new ODBCPdoStatement($this->getConnection(), $statement);
    }

    /**
     * @return mixed
     */
    public function getConnection()
    {
        return $this->connection;
    }

    /**
     * @param mixed $connection
     */
    public function setConnection($connection): void
    {
        $this->connection = $connection;
    }

    /**
     * @param null $name
     * @return string|void
     * @throws Exception
     */
    public function lastInsertId($name = null): string|false
    {
        throw new Exception("Error, you must override this method!");
    }

    public function commit(): bool
    {
        return odbc_commit($this->getConnection());
    }

    public function rollBack(): bool
    {
        $rollback = odbc_rollback($this->getConnection());
        odbc_autocommit($this->getConnection(), true);
        return $rollback;
    }

    public function beginTransaction(): bool
    {
        return odbc_autocommit($this->getConnection(), false);
    }
}
