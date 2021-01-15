<?php

namespace DreamFactory\Core\Hadoop\Database;

use PDOStatement;

class ODBCPdoStatement extends PDOStatement
{
    protected $query;
    protected $params = [];
    protected $statement;

    public function __construct($conn, $query)
    {
        // Replace named parameters with ?. For example, for query SELECT * FROM t WHERE id = :id, result will be
        // SELECT * FROM t WHERE id = ?. ":id" replaced with "?".
        $this->query = preg_replace('/(?<=\s|^):[^\s:]++/um', '?', $query);

        $this->params = $this->getParamsFromQuery($query);

        $this->statement = odbc_prepare($conn, $this->query);
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

    public function rowCount()
    {
        return odbc_num_rows($this->statement);
    }

    public function bindValue($param, $val, $ignore = null)
    {
        $this->params[$param] = $val;
    }

    public function execute($ignore = null)
    {
        odbc_execute($this->statement, $this->params);
        $this->params = [];
    }

    public function fetchAll($how = NULL, $class_name = NULL, $ctor_args = NULL)
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
}
