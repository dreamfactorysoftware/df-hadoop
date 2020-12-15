<?php
namespace DreamFactory\Core\Hadoop;

use DreamFactory\Core\Hadoop\Models\HDFSConfig;
use DreamFactory\Core\Services\ServiceManager;
use DreamFactory\Core\Services\ServiceType;
use DreamFactory\Core\Enums\ServiceTypeGroups;
use DreamFactory\Core\Enums\LicenseLevel;
use DreamFactory\Core\Hadoop\Services\HDFSService;

class ServiceProvider extends \Illuminate\Support\ServiceProvider
{
    public function boot()
    {
        // add migrations
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');
    }

    public function register()
    {

        // Add our service types.
        $this->app->resolving('df.service', function (ServiceManager $df) {
            $df->addType(
                new ServiceType([
                    'name'            => 'hadoop_hdfs',
                    'label'           => 'Hadoop HDFS',
                    'description'     => 'Hadoop Distributed File System',
                    'group'           => ServiceTypeGroups::FILE,
                    'subscription_required' => LicenseLevel::GOLD,
                    'config_handler'  => HDFSConfig::class,
                    'factory'         => function ($config) {
                        return new HDFSService($config);
                    },
                ])
            );
        });
    }
}
