<?php namespace DreamFactory\Core\Hadoop\Services;

use DreamFactory\Core\File\Services\RemoteFileService;
use DreamFactory\Core\Hadoop\Components\HDFileSystem;

class HDFSService extends RemoteFileService
{

    /**
     * {@inheritDoc}
     */
    protected function setDriver($config)
    {
        $this->driver = new HDFileSystem($config);
    }
}
