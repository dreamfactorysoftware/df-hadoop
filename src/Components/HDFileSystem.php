<?php

namespace DreamFactory\Core\Hadoop\Components;


use DreamFactory\Core\File\Components\RemoteFileSystem;

class HDFileSystem extends RemoteFileSystem
{

    /**
     * @param array $config
     *
     * @throws InternalServerErrorException
     */
    public function __construct($config)
    {
    }

    /**
     * Object destructor
     */
    public function __destruct()
    {
    }

    /**
     * {@inheritDoc}
     */
    public function listContainers($include_properties = false)
    {
        // TODO: Implement listContainers() method.
    }

    /**
     * {@inheritDoc}
     */
    public function containerExists($container)
    {
        // TODO: Implement containerExists() method.
    }

    /**
     * {@inheritDoc}
     */
    public function getContainer($container, $include_files = true, $include_folders = true, $full_tree = false)
    {
        // TODO: Implement getContainer() method.
    }

    /**
     * {@inheritDoc}
     */
    public function getContainerProperties($container)
    {
        // TODO: Implement getContainerProperties() method.
    }

    /**
     * {@inheritDoc}
     */
    public function createContainer($container, $check_exist = false)
    {
        // TODO: Implement createContainer() method.
    }

    /**
     * {@inheritDoc}
     */
    public function updateContainerProperties($container, $properties = [])
    {
        // TODO: Implement updateContainerProperties() method.
    }

    /**
     * {@inheritDoc}
     */
    public function deleteContainer($container, $force = false)
    {
        // TODO: Implement deleteContainer() method.
    }

    /**
     * {@inheritDoc}
     */
    public function blobExists($container, $name)
    {
        // TODO: Implement blobExists() method.
    }

    /**
     * {@inheritDoc}
     */
    public function putBlobData($container, $name, $data = null, $properties = [])
    {
        // TODO: Implement putBlobData() method.
    }

    /**
     * {@inheritDoc}
     */
    public function putBlobFromFile($container, $name, $localFileName = null, $properties = [])
    {
        // TODO: Implement putBlobFromFile() method.
    }

    /**
     * {@inheritDoc}
     */
    public function copyBlob($container, $name, $src_container, $src_name, $properties = [])
    {
        // TODO: Implement copyBlob() method.
    }

    /**
     * {@inheritDoc}
     */
    public function listBlobs($container, $prefix = '', $delimiter = '')
    {
        // TODO: Implement listBlobs() method.
    }

    /**
     * {@inheritDoc}
     */
    public function getBlobAsFile($container, $name, $localFileName = null)
    {
        // TODO: Implement getBlobAsFile() method.
    }

    /**
     * {@inheritDoc}
     */
    public function getBlobData($container, $name)
    {
        // TODO: Implement getBlobData() method.
    }

    /**
     * {@inheritDoc}
     */
    public function getBlobProperties($container, $name)
    {
        // TODO: Implement getBlobProperties() method.
    }

    /**
     * {@inheritDoc}
     */
    public function streamBlob($container, $name, $params = [])
    {
        // TODO: Implement streamBlob() method.
    }

    /**
     * {@inheritDoc}
     */
    public function deleteBlob($container, $name, $noCheck = false)
    {
        // TODO: Implement deleteBlob() method.
    }
}
