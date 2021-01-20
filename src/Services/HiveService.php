<?php

namespace DreamFactory\Core\Hadoop\Services;

use DreamFactory\Core\Hadoop\Resources\HiveTable;
use DreamFactory\Core\SqlDb\Services\SqlDb;

/**
 * Class PostgreSqlDb
 *
 * @package DreamFactory\Core\SqlDb\Services
 */
class HiveService extends SqlDb
{
    public static function adaptConfig(array &$config)
    {
        $config['driver'] = 'odbc';
        $driverPath = env('HIVE_SERVER_ODBC_DRIVER_PATH', '/opt/mapr/hiveodbc/lib/64/libmaprhiveodbc64.so');
        if (isset($config['options']['driver_path'])) {
            $driverPath = $config['options']['driver_path'];
        }
        $config['dsn'] =
            "Driver={{$driverPath}};"
            . "Host={$config['host']};"
            . "Port={$config['port']};"
            . "Database={$config['database']}";
        parent::adaptConfig($config);
    }

    public function getResourceHandlers()
    {
        $handlers = parent::getResourceHandlers();

        $handlers[HiveTable::RESOURCE_NAME] = [
            'name'       => HiveTable::RESOURCE_NAME,
            'class_name' => HiveTable::class,
            'label'      => 'Table',
        ];

        return $handlers;
    }
}
