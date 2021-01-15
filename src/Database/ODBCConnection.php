<?php

namespace DreamFactory\Core\Hadoop\Database;

use Illuminate\Database\Connection;
use Illuminate\Database\Query\Grammars\MySqlGrammar;

class ODBCConnection extends Connection
{
    function getDefaultQueryGrammar()
    {
        return new MySqlGrammar();
    }

    function getDefaultSchemaGrammar()
    {
        $schemaGrammar = $this->getConfig('options.grammar.schema');
        if ($schemaGrammar) {
            return new $schemaGrammar;
        }
        return parent::getDefaultSchemaGrammar();
    }

    public function query()
    {
        return new ODBCBuilder($this, $this->getQueryGrammar(), $this->getPostProcessor());
    }


    /**
     * Get the default post processor instance.
     *
     * @return ODBCProcessor
     */
    protected function getDefaultPostProcessor()
    {
        $processor = $this->getConfig('options.processor');
        if ($processor) {
            return new $processor;
        }
        return new ODBCProcessor;
    }

}
