<?php

namespace DreamFactory\Core\Hadoop\Database;

use PDOStatement;

class ODBCPdoStatement extends PDOStatement
{
    protected $query;
    protected $params = [];
    protected $statement;
    protected $driverIssue = false;
    protected $connection;

    public function __construct($conn, $query)
    {
        // Replace named parameters with ?. For example, for query SELECT * FROM t WHERE id = :id, result will be
        // SELECT * FROM t WHERE id = ?. ":id" replaced with "?".
        $this->query = preg_replace('/(?<=\s|^):[^\s:]++/um', '?', $query);

        $this->params = $this->getParamsFromQuery($query);
        if (preg_match('/\?[^;]+?OFFSET/im', $query)) {
            $this->driverIssue = true;
            $this->connection = $conn;
        } else {
            $this->statement = odbc_prepare($conn, $this->query);
        }
    }

    protected function getParamsFromQuery($qry)
    {
        $params = [];
        $qryArray = explode(" ", $qry);
        $i = 0;

        while (isset($qryArray[$i])) {
            if (preg_match("/^:/", $qryArray[$i])) {
                $params[$qryArray[$i]] = null;
            }
            $i++;
        }

        return $params;
    }

    public function rowCount(): int
    {
        return odbc_num_rows($this->statement);
    }

    public function bindValue($param, $value, $ignore = null): bool
    {
        $this->params[$param] = $value;
        return true;
    }

    public function execute($ignore = null): bool
    {
        if ($this->driverIssue) {
            $q = $this->query;
            foreach ($this->params as $param) {
                $q = preg_replace('/\?/', $this->quote($param), $q);
            }
            $this->statement = odbc_prepare($this->connection, $q);
            return odbc_execute($this->statement, []);
        } else {
            $params = $this->params;
            $this->params = [];
            return odbc_execute($this->statement, $params);
        }
    }

    public function fetchAll(int $mode = 0, ...$args): array
    {
        $records = [];
        while ($record = $this->fetch()) {
            $records[] = $record;
        }
        return $records;
    }

    public function fetch($option = null, $ignore = null, $ignore2 = null)
    {
        return odbc_fetch_array($this->statement);
    }

    private function quote($param)
    {
        if (gettype($param) === 'string') {
            $r = preg_replace('/(\\\)/', "\\\\$1", $param);
            $r = preg_replace('/(")/', "\\\\$1", $r);
            return "\"${r}\"";
        }
        return $param;
    }

    /**
     * We can do nothing with fetch mode using odbc_ functions. So
     * we have to mock this method as Laravel uses it, and it should
     * be defined as we do not using PDO `__construct`.
     */
    public function setFetchMode($mode, $className = null, ...$params)
    {
     return true;
    }
}
