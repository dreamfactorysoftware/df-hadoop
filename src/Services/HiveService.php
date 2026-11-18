<?php

namespace DreamFactory\Core\Hadoop\Services;

use DreamFactory\Core\Hadoop\Resources\HiveTable;
use DreamFactory\Core\SqlDb\Services\SqlDb;
use Illuminate\Support\Arr;

/**
 * Class PostgreSqlDb
 *
 * @package DreamFactory\Core\SqlDb\Services
 */
class HiveService extends SqlDb
{
    /**
     * Directories the ODBC driver shared object may live in.
     * Without this allowlist an admin who can create a Hive service can
     * load an arbitrary .so on the host (RCE via dlopen).
     */
    public const ALLOWED_DRIVER_DIRS = [
        '/opt/mapr/',
        '/opt/cloudera/',
        '/opt/simba/',
        '/opt/hortonworks/',
    ];

    public static function adaptConfig(array &$config)
    {
        $config['driver'] = 'odbc';
        $driverPath = env('HIVE_SERVER_ODBC_DRIVER_PATH', '/opt/mapr/hiveodbc/lib/64/libmaprhiveodbc64.so');
        if (isset($config['options']['driver_path'])) {
            $driverPath = $config['options']['driver_path'];
        }

        self::assertSafeDriverPath($driverPath);

        $host = self::assertSafeDsnValue($config['host'] ?? '', 'host');
        $port = self::assertSafePort($config['port'] ?? '');
        $database = self::assertSafeDsnValue($config['database'] ?? '', 'database');

        $config['dsn'] =
            "Driver={{$driverPath}};"
            . "Host={$host};"
            . "Port={$port};"
            . "Database={$database}";
        parent::adaptConfig($config);
    }

    /**
     * Reject DSN component values that would let the caller inject extra
     * ODBC options (the DSN format is "Key=Value;Key=Value").
     */
    public static function assertSafeDsnValue(string $value, string $field): string
    {
        if ($value === '') {
            throw new \InvalidArgumentException("Hive {$field} is required.");
        }
        if (preg_match('/[;{}\r\n\x00]/', $value) === 1) {
            throw new \InvalidArgumentException(
                "Hive {$field} contains characters that are not permitted in an ODBC DSN."
            );
        }
        return $value;
    }

    public static function assertSafePort($port): int
    {
        if (!is_numeric($port)) {
            throw new \InvalidArgumentException('Hive port must be numeric.');
        }
        $port = (int)$port;
        if ($port < 1 || $port > 65535) {
            throw new \InvalidArgumentException('Hive port is out of range.');
        }
        return $port;
    }

    /**
     * Driver path must live under an allowlisted vendor directory and
     * resolve to a regular file. Symlinks resolve via realpath() so the
     * boundary check catches symlink escapes.
     */
    public static function assertSafeDriverPath(string $driverPath): void
    {
        if ($driverPath === '') {
            throw new \InvalidArgumentException('Hive driver_path is required.');
        }
        $real = realpath($driverPath);
        if ($real === false || !is_file($real)) {
            throw new \InvalidArgumentException("Hive driver_path does not resolve to a regular file: {$driverPath}");
        }
        foreach (self::ALLOWED_DRIVER_DIRS as $allowed) {
            if (str_starts_with($real, $allowed)) {
                return;
            }
        }
        throw new \InvalidArgumentException(
            "Hive driver_path must live under one of: " . implode(', ', self::ALLOWED_DRIVER_DIRS)
        );
    }

    // Hide _schema endpoints and related parameter
    public function getApiDocInfo()
    {
        $base = parent::getApiDocInfo();
        $paths = (array)Arr::get($base, 'paths');
        foreach ($paths as $path_key => $path) {
            if (str_contains($path_key, '_schema')) {
                unset($paths[$path_key]);
                continue;
            }

            $paths[$path_key] = $this->removeNotGetPaths($path);
        }
        $base['paths'] = $paths;
        return $base;
    }

    public function getResourceHandlers()
    {
        $handlers = parent::getResourceHandlers();

        $handlers[HiveTable::RESOURCE_NAME] = [
            'name' => HiveTable::RESOURCE_NAME,
            'class_name' => HiveTable::class,
            'label' => 'Table',
        ];

        return $handlers;
    }

    private function removeRelatedParameter($parameters)
    {
        foreach ($parameters as $parameter_key => $parameter) {
            if ($parameter['name'] === 'related') {
                unset($parameters[$parameter_key]);
                continue;
            }
        }
        $parameters = array_values($parameters);
        return $parameters;
    }

    private function removeNotGetPaths($path)
    {
        foreach ($path as $resource_key => $resource) {
            if ($resource_key === 'post' || $resource_key === 'patch' || $resource_key === 'put' || $resource_key === 'delete') {
                unset($path[$resource_key]);
                continue;
            }

            if (isset($resource['parameters'])) {
                $path[$resource_key]['parameters'] = $this->removeRelatedParameter($resource['parameters']);
            }
        }
        return $path;
    }
}
