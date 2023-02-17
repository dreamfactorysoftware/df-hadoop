<?php

namespace DreamFactory\Core\Hadoop\Database;

use Illuminate\Database\Query\Builder;

class ODBCBuilder extends Builder
{

    public function __construct($connection,
                                $grammar = null,
                                $processor = null)
    {
        return parent::__construct($connection, $grammar, $processor);
    }

    public function where($column, $operator = null, $value = null, $boolean = 'and')
    {
        [$value, $operator] = $this->prepareValueAndOperator(
            $value, $operator, func_num_args() == 2
        );
        return parent::where($column, $operator, $value, $boolean);
    }

    public function skip($value)
    {
        return $value == 0 ? $this : $this->offset($value);
    }
}
