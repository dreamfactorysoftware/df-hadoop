<?php


namespace DreamFactory\Core\Hadoop\Database\Schema;


use DreamFactory\Core\Database\Schema\TableSchema;
use DreamFactory\Core\SqlDb\Database\Schema\SqlSchema;
use Illuminate\Support\Facades\DB;

class HiveSchema extends SqlSchema
{
    const DEFAULT_SCHEMA = 'PUBLIC';

    /**
     * @inheritdoc
     */
    public function getDefaultSchema()
    {
        return static::DEFAULT_SCHEMA;
    }

    /**
     * @inheritdoc
     */
    protected function getTableNames($schema = '')
    {
        $tableSelect = $this->connection->select(DB::raw('SHOW TABLES'));
        $result = [];
        foreach ($tableSelect as $row) {
            $tableSchema = new TableSchema([]);
            $tableSchema->setName($row['tab_name']);
            $result[] = $tableSchema;
        }
        return $result;
    }

    /**
     * @inheritdoc
     */
    protected function getViewNames($schema = '')
    {
        return [];
    }

    public function getSchemas()
    {
        return [
            'default',
            'table_name',
        ];
    }

    /**
     * @inheritdoc
     */
    protected function getTableConstraints($schema = '')
    {
        return [];
    }
}
