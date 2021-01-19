<?php
namespace DreamFactory\Core\Hadoop;

use DreamFactory\Core\Hadoop\Database\ODBCConnection;
use DreamFactory\Core\Hadoop\Database\ODBCConnector;
use DreamFactory\Core\Hadoop\Database\Schema\HiveSchema;
use DreamFactory\Core\Hadoop\Models\HDFSConfig;
use DreamFactory\Core\Hadoop\Models\HiveConfig;
use DreamFactory\Core\Hadoop\Services\HiveService;
use DreamFactory\Core\Services\ServiceManager;
use DreamFactory\Core\Services\ServiceType;
use DreamFactory\Core\Enums\ServiceTypeGroups;
use DreamFactory\Core\Enums\LicenseLevel;
use DreamFactory\Core\Hadoop\Services\HDFSService;
use Illuminate\Database\DatabaseManager;
use Illuminate\Database\Eloquent\Model;

class ServiceProvider extends \Illuminate\Support\ServiceProvider
{
    public function boot()
    {
        // add migrations
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');

        Model::setConnectionResolver($this->app['db']);
        Model::setEventDispatcher($this->app['events']);
    }

    public function register()
    {
        $this->app->resolving('db', function ($db) {
            /** @var DatabaseManager $db */
            $db->extend('odbc', function ($config, $name) {
                $pdoConnection = (new ODBCConnector())->connect($config);
                return new ODBCConnection($pdoConnection, $config['database'], isset($config['prefix']) ? $config['prefix'] : '', $config);
            });
        });
        $this->app->resolving('db.schema', function ($db) {
            /** @var DatabaseManager $db */
            $db->extend('odbc', function ($connection) {
                return new HiveSchema($connection);
            });
        });

        // Add our service types.
        $this->app->resolving('df.service', function (ServiceManager $df) {
            $df->addType(
                new ServiceType([
                    'name' => 'hadoop_hdfs',
                    'label' => 'Hadoop HDFS',
                    'description' => 'Hadoop Distributed File System',
                    'group' => ServiceTypeGroups::FILE,
                    'subscription_required' => LicenseLevel::GOLD,
                    'config_handler' => HDFSConfig::class,
                    'factory' => function ($config) {
                        return new HDFSService($config);
                    },
                ])
            );
            $df->addType(
                new ServiceType([
                    'name' => 'apache_hive',
                    'label' => 'Apache Hive',
                    'description' => 'The Apache Hive data warehouse software facilitates reading, writing, and managing large datasets residing in distributed storage using SQL',
                    'group' => 'Big Data',
                    'subscription_required' => LicenseLevel::GOLD,
                    'config_handler' => HiveConfig::class,
                    'factory' => function ($config) {
                        return new HiveService($config);
                    },
                ])
            );
        });
    }
}
