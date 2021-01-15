<?php

namespace DreamFactory\Core\Hadoop\Database;

use Illuminate\Database\Query\Builder;
use Illuminate\Support\Arr;

class ODBCBuilder extends Builder
{

    public function __construct($connection,
                                $grammar = null,
                                $processor = null)
    {
        return parent::__construct($connection, $grammar, $processor);
    }

    public function whereIn($column, $values, $boolean = 'and', $not = false)
    {
        return parent::whereIn($column, $values, $boolean, $not);
    }


    public function where($column, $operator = null, $value = null, $boolean = 'and')
    {
        list($value, $operator) = $this->prepareValueAndOperator(
            $value, $operator, func_num_args() == 2
        );
        return parent::where($column, $operator, $value, $boolean);
    }

    public function get($columns = ['*'])
    {
        return collect($this->onceWithColumns(Arr::wrap($columns), function () {
            return $this->processor->processSelect($this, $this->runSelect());
        }));
    }


    public function skip($value)
    {
        return $value == 0 ? $this : $this->offset($value);
    }
}
