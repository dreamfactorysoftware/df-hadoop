<?php

namespace DreamFactory\Core\Hadoop\Resources;

use DreamFactory\Core\SqlDb\Resources\Table;

/**
 * Class Table
 *
 * @package DreamFactory\Core\SqlDb\Resources
 */
class HiveTable extends Table
{

    public function getApiDocInfo()
    {
        $base = parent::getApiDocInfo();
        $paths = (array)array_get($base, 'paths');
        foreach ($paths as $pkey=>$path) {
            if (strpos($pkey, '_schema') !== false) {
                unset($paths[$pkey]);
                continue;
            }
            foreach ($path as $rkey=>$resource) {
                if ($rkey === 'post' || $rkey === 'patch' || $rkey === 'put' || $rkey === 'delete') {
                    unset($paths[$pkey][$rkey]);
                }
            }
        }

        $base['paths'] = $paths;
        return $base;
    }
}

