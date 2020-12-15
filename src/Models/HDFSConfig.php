<?php

namespace DreamFactory\Core\Hadoop\Models;

use DreamFactory\Core\File\Components\SupportsFiles;
use DreamFactory\Core\Models\BaseServiceConfigModel;

/**
 * Write your model
 *
 * Write your methods, properties or override ones from the parent
 *
 */
class HDFSConfig extends BaseServiceConfigModel
{
    use SupportsFiles;

    /** @var string */
    protected $table = 'hdfs_config';

    /** @var array */
    protected $fillable = [
        'service_id',
        'host',
        'use_ssl',
        'port',
        'user',
        'namenode_rpc_host',
        'namenode_rpc_port',
    ];

    /** @var array */
    protected $casts = [
        'service_id' => 'integer',
        'port' => 'integer',
        'namenode_rpc_port' => 'integer',
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = ['created_date'];

    /**
     * @param array $schema
     */
    protected static function prepareConfigSchemaField(array &$schema)
    {
        parent::prepareConfigSchemaField($schema);

        switch ($schema['name']) {
            case 'host':
                $schema['label'] = 'Hostname';
                $schema['type'] = 'string';
                $schema['required'] = false;
                $schema['description'] = 'Hadoop Distributed File System Server Hostname';
                break;
            case 'port':
                $schema['label'] = 'Port';
                $schema['type'] = 'integer';
                $schema['description'] = 'Hadoop Distributed File System Server Port';
                break;
            case 'user':
                $schema['label'] = 'Username';
                $schema['type'] = 'string';
                $schema['description'] = 'Hadoop Distributed File System Server Username';
                break;
            case 'use_ssl':
                $schema['label'] = 'Use SSL?';
                $schema['type'] = 'boolean';
                $schema['required'] = false;
                $schema['description'] = 'Require SSL connection to communicate with server';
                break;
            case 'namenode_rpc_host':
                $schema['label'] = 'Namenode RPC Host';
                $schema['type'] = 'string';
                $schema['description'] = 'RPC server hostname. Required to perform write operations';
                break;
            case 'namenode_rpc_port':
                $schema['label'] = 'Namenode RPC Port';
                $schema['type'] = 'integer';
                $schema['description'] = 'RPC server port. Required to perform write operations';
                break;
        }
    }


}
