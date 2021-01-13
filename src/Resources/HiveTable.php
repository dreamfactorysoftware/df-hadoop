<?php

namespace DreamFactory\Core\Hadoop\Resources;

use Illuminate\Support\Facades\DB;
use DreamFactory\Core\Database\Resources\BaseDbTableResource;

/**
 * Class Table
 *
 * @package DreamFactory\Core\SqlDb\Resources
 */
class HiveTable extends BaseDbTableResource
{
    //*************************************************************************
    //	Members
    //*************************************************************************

    /**
     * @var null | bool
     */
    protected $transaction = null;

    //*************************************************************************
    //	Methods
    //*************************************************************************

    /**
     * {@inheritdoc}
     */
    public function updateRecordsByFilter($table, $record, $filter = null, $params = [], $extras = [])
    {

    }

    /**
     * {@inheritdoc}
     */
    public function patchRecordsByFilter($table, $record, $filter = null, $params = [], $extras = [])
    {

    }

    /**
     * {@inheritdoc}
     */
    public function truncateTable($table, $extras = [])
    {

    }

    /**
     * {@inheritdoc}
     */
    public function deleteRecordsByFilter($table, $filter, $params = [], $extras = [])
    {

    }

    /**
     * {@inheritdoc}
     */
    public function retrieveRecordsByFilter($table, $filter = null, $params = [], $extras = [])
    {
        return [$this->parent->getConnection()->table($table)->get()];
    }

    // Helper methods

    protected function restrictFieldsToDefined()
    {
    }

    /**
     * @inheritdoc
     */
    protected function getCurrentTimestamp()
    {
        return $this->parent->getSchema()->getTimestampForSet();
    }

    /**
     * @inheritdoc
     */
    protected function parseValueForSet($value, $field_info, $for_update = false)
    {

    }

    /**
     * @param array $record
     *
     * @return array
     */
    public static function interpretRecordValues($record)
    {

    }


    /**
     * @param      $table
     * @param null $fields_info
     * @param null $requested_fields
     * @param null $requested_types
     *
     * @return array|\DreamFactory\Core\Database\Schema\ColumnSchema[]
     * @throws \DreamFactory\Core\Exceptions\BadRequestException
     */
    protected function getIdsInfo($table, $fields_info = null, &$requested_fields = null, $requested_types = null)
    {

    }

    /**
     * {@inheritdoc}
     */
    protected function addToTransaction(
        $record = null,
        $id = null,
        $extras = null,
        $rollback = false,
        $continue = false,
        $single = false
    ) {

    }

    /**
     * {@inheritdoc}
     */
    protected function commitTransaction($extras = null)
    {

    }

    /**
     * {@inheritdoc}
     */
    protected function rollbackTransaction()
    {

    }


}
