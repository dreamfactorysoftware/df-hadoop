<?php namespace DreamFactory\Core\Hadoop\Services;

use DreamFactory\Core\Exceptions\InternalServerErrorException;
use DreamFactory\Core\File\Services\RemoteFileService;
use DreamFactory\Core\Hadoop\Components\HDFileSystem;

class HDFSService extends RemoteFileService
{

    /**
     * {@inheritDoc}
     * @throws InternalServerErrorException
     */
    protected function setDriver($config)
    {
        $this->container = array_get($config, 'container');

        if (empty($this->container)) {
            throw new InternalServerErrorException('File service container not specified. Please check configuration for file service - ' .
                $this->name);
        }

        $this->driver = new HDFileSystem($config);
    }

    protected function getApiDocSchemas()
    {
        $apiDocSchemas = parent::getApiDocSchemas();
        \Log::error(json_encode($apiDocSchemas));
        return $apiDocSchemas;
    }


}
