<?php


namespace DreamFactory\Core\Hadoop\Database\Schema;


use DreamFactory\Core\Database\Schema\ColumnSchema;
use DreamFactory\Core\Database\Schema\TableSchema;
use DreamFactory\Core\Enums\DbResourceTypes;
use DreamFactory\Core\SqlDb\Database\Schema\MySqlSchema;
use DreamFactory\Core\SqlDb\Database\Schema\SqlSchema;
use Illuminate\Support\Facades\DB;

class HiveSchema extends MySqlSchema
{
    const DEFAULT_SCHEMA = 'default';

    /**
     * @inheritdoc
     */
    public function getDefaultSchema()
    {
        return static::DEFAULT_SCHEMA;
    }

    public function getSupportedResourceTypes()
    {
        return [
            DbResourceTypes::TYPE_TABLE,
            DbResourceTypes::TYPE_TABLE_FIELD,
        ];
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
            'table_name2',
            'table_name3',
            'table_name4',
            'table_name5',
            'table_name6',
            'table_name7',
            'table_name8',
        ];
    }

    protected function loadTableColumns(TableSchema $table)
    {
        $result = $this->connection->select("DESCRIBE {$table->getName()}");
        $extendedDescribe = $this->connection->select("DESCRIBE EXTENDED {$table->getName()}");

        $constraints = array_values(array_filter($extendedDescribe, function ($value) {
            return $value['col_name'] === 'Constraints';
        }));
        $primaryKeys = [];
        if (!empty($constraints)) {
            $description = $constraints[0]['data_type'];
            preg_match('/Primary Key .+?:\[(.+?)]/', $description, $matches);
            $primaryKeys = explode(',', $matches[1]) ?? [];
        }

        foreach ($result as $columnMetadata) {
            $columnSchema = new ColumnSchema([]);
            $name = $columnMetadata['col_name'];
            $columnSchema->setName($name);
            if (in_array($name, $primaryKeys)) {
                $columnSchema->isPrimaryKey = true;
                $table->addPrimaryKey($name);
            }
            if (isset($columnMetadata['comment']) || !empty($columnMetadata['comment'])) {
                $columnSchema->comment = $columnMetadata['comment'];
            }
            $this->extractType($columnSchema, $columnMetadata['data_type']);
            $table->addColumn($columnSchema);
        }
    }

}
