<?php

namespace DreamFactory\Core\Hadoop\Components;


use DreamFactory\Core\File\Components\RemoteFileSystem;
use Illuminate\Support\Facades\Log;
use org\apache\hadoop\WebHDFS;

class HDFileSystem extends RemoteFileSystem
{

    /**
     * @var WebHDFS $webHDFSClient
     */
    protected $webHDFSClient;

    /**
     * @param array $config
     *
     * @throws InternalServerErrorException
     */
    public function __construct($config)
    {
        $this->webHDFSClient = new WebHDFS(
            $config['host'],
            $config['port'],
            $config['user'],
            $config['namenode_rpc_host'],
            $config['namenode_rpc_port'],
            config('app.debug')
        );
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
        Log::error('listContainers - $include_properties = ' . $include_properties);
        return [];
    }

    /**
     * {@inheritDoc}
     */
    public function containerExists($container)
    {
        Log::error('containerExists - $container = ' . $container);
        $response = json_decode($this->webHDFSClient->getFileStatus($container));
        return !isset($response->RemoteException);
    }

    /**
     * {@inheritDoc}
     */
    public function getContainer($container, $include_files = true, $include_folders = true, $full_tree = false)
    {
        // TODO: Implement getContainer() method.
        Log::error('getContainer - $container = ' . $container . '; $include_files = ' . $include_files . '; $include_folders = ' . $include_folders . '; $full_tree = ' . $full_tree);
    }

    /**
     * {@inheritDoc}
     */
    public function getContainerProperties($container)
    {
        // TODO: Implement getContainerProperties() method.
        Log::error('getContainerProperties - $container = ' . $container);
    }

    /**
     * {@inheritDoc}
     */
    public function createContainer($container, $check_exist = false)
    {
        // TODO: Implement createContainer() method.
        Log::error('createContainer - $container = ' . $container . '; $check_exist = ' . $check_exist);
    }

    /**
     * {@inheritDoc}
     */
    public function updateContainerProperties($container, $properties = [])
    {
        // TODO: Implement updateContainerProperties() method.
        Log::error('updateContainerProperties - $container = ' . $container . '; $properties = ' . implode('|', $properties));
    }

    /**
     * {@inheritDoc}
     */
    public function deleteContainer($container, $force = false)
    {
        // TODO: Implement deleteContainer() method.
        Log::error('deleteContainer - $container = ' . $container . '; $force = ' . $force);
    }

    /**
     * {@inheritDoc}
     */
    public function blobExists($container, $name)
    {
        Log::error('blobExists - $container = ' . $container . '; $name = ' . $name);
        $path = $this->getPath($container, $name);
        $response = json_decode($this->webHDFSClient->getFileStatus($path));
        return isset($response->FileStatus);
    }

    /**
     * {@inheritDoc}
     */
    public function putBlobData($container, $name, $data = null, $properties = [])
    {
        // TODO: Implement putBlobData() method.
        Log::error('putBlobData - $container = ' . $container . '; $name = ' . $name . '; $data = ' . $data . '; $properties = ' . implode('|', $properties));
    }

    /**
     * {@inheritDoc}
     */
    public function putBlobFromFile($container, $name, $localFileName = null, $properties = [])
    {
        // TODO: Implement putBlobFromFile() method.
        Log::error('putBlobFromFile - $container = ' . $container . '; $name = ' . $name . '; $localFileName = ' . $localFileName . '; $properties = ' . implode('|', $properties));
    }

    /**
     * {@inheritDoc}
     */
    public function copyBlob($container, $name, $src_container, $src_name, $properties = [])
    {
        // TODO: Implement copyBlob() method.
        Log::error('copyBlob - $container = ' . $container . '; $name = ' . $name . '; $src_container = ' . $src_container . '; $src_name = ' . $src_name . '; $properties = ' . implode('|', $properties));
    }

    /**
     * {@inheritDoc}
     */
    public function listBlobs($container, $prefix = '', $delimiter = '')
    {
        Log::error('listBlobs - $container = ' . $container . '; $prefix = ' . $prefix . '; $delimiter = ' . $delimiter);
        $listOfDirectories = $this->webHDFSClient->listDirectories($container, false, true);
        foreach ($listOfDirectories as $directory) {
            $directory->pathSuffix .= '/';
        }
        $listOfFiles = $this->webHDFSClient->listFiles($container, false, true);
        $listOfBlobs = array_merge($listOfDirectories, $listOfFiles);
        foreach ($listOfBlobs as $blob) {
            $blob->name = $blob->pathSuffix;
            $blob->hadoopType = $blob->type;
        }

        return json_decode(json_encode($listOfBlobs), true);
    }

    /**
     * {@inheritDoc}
     */
    public function getBlobAsFile($container, $name, $localFileName = null)
    {
        // TODO: Implement getBlobAsFile() method.
        Log::error('getBlobAsFile');
    }

    /**
     * {@inheritDoc}
     */
    public function getBlobData($container, $name)
    {
        // TODO: Implement getBlobData() method.
        Log::error('getBlobData');
    }

    /**
     * {@inheritDoc}
     */
    public function getBlobProperties($container, $name)
    {
        // TODO: Implement getBlobProperties() method.
        Log::error('getBlobProperties');
    }

    /**
     * {@inheritDoc}
     */
    public function streamBlob($container, $name, $params = [])
    {
        // TODO: Implement streamBlob() method.
        Log::error('streamBlob - $container = ' . $container . '; $name = ' . $name . '; $params = ' . implode($params));
        $content = $this->webHDFSClient->open($this->getPath($container, $name));
        echo $content;
    }

    /**
     * {@inheritDoc}
     */
    public function deleteBlob($container, $name, $noCheck = false)
    {
        // TODO: Implement deleteBlob() method.
        Log::error('deleteBlob');
    }

    /**
     * @param $container
     * @param $name
     * @return string
     */
    protected function getPath($container, $name)
    {
        if (preg_match('/.*\/$/', $container)) {
            $path = $container . $name;
        } else {
            $path = $container . '/' . $name;
        }
        return $path;
    }
}
