<?php

namespace DreamFactory\Core\Hadoop\Components;


use DreamFactory\Core\Exceptions\DfException;
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
        $newPath = null;
        if ($properties['path'] && $properties['name']) {
            $newPath = $this->getPath($properties['path'], $properties['name']);
        } elseif ($properties['name']) {
            $newPath = $properties['name'];
        } elseif ($properties['path']) {
            $newPath = $properties['path'];
        }
        if ($newPath) {
            $this->webHDFSClient->rename($container, $properties['name']);
        }

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
     * {@inheritdoc}
     * @param string $container Container
     * @param string $name Path
     * @param string $data Content or Properties
     * @param string $type MimeType
     */
    public function putBlobData($container, $name, $data = null, $type = '')
    {
        Log::error('putBlobData - $container = ' . $container . '; $name = ' . $name . '; $data = ' . $data . '; $type = ' . $type);
        $path = $this->getPath($container, $name);
        if (!$data) {
            $this->webHDFSClient->mkdirs($path);
        } else {
            $this->webHDFSClient->createWithData($path, $data, true);
        }
    }

    /**
     * {@inheritDoc}
     *
     */
    public function putBlobFromFile($container, $name, $localFileName = null, $mime = '')
    {
        Log::error('putBlobFromFile - $container = ' . $container . '; $name = ' . $name . '; $localFileName = ' . $localFileName . '; $mime = ' . $mime);
        $path = $this->getPath($container, $name);
        $this->webHDFSClient->create($path, $localFileName);
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
        if ($prefix) {
            $path = $this->getPath($container, $prefix);
        } else {
            $path = $container;
        }
        $listOfDirectories = $this->webHDFSClient->listDirectories($path, $delimiter === '', true);
        $listOfFiles = $this->webHDFSClient->listFiles($path, $delimiter === '', true);
        $listOfBlobs = array_merge($listOfDirectories, $listOfFiles);
        $result = [];
        foreach ($listOfBlobs as $blob) {
            // Convert to DreamFactory resource type
            $responseBlob = [];
            $suffix = $blob->type === 'DIRECTORY' ? '/' : '';
            $responseBlob['name'] = preg_replace("/^${container}\/?/", '', $blob->path . $suffix, 1);
            $responseBlob['content_length'] = $blob->length;
            $responseBlob['last_modified'] = $blob->modificationTime;
            $responseBlob['content_type'] = null;
            // Save original HDFS responce
            $responseBlob['hdfs'] = $blob;

            $result[] = $responseBlob;
        }
        $sortedBlobList = $this->sortBlobListByDepth($result, false);
        return json_decode(json_encode($sortedBlobList), true);
    }

    /**
     * {@inheritDoc}
     */
    public function getBlobAsFile($container, $name, $localFileName = null)
    {
        // TODO: Implement getBlobAsFile() method.
        // write blob to a local system file
        Log::error('getBlobAsFile');
    }

    /**
     * {@inheritDoc}
     */
    public function getBlobData($container, $name)
    {
        Log::error('getBlobData - $container = ' . $container . '; $name = ' . $name);
        $path = $this->getPath($container, $name);
        try {
            return $this->webHDFSClient->open($path);
        } catch (\Exception $ex) {
            throw new DfException('Failed to retrieve blob "' . $name . '": ' . $ex->getMessage());
        }
    }

    /**
     * {@inheritDoc}
     */
    public function getBlobProperties($container, $name)
    {
        Log::error('getBlobProperties - $container = ' . $container . '; $name = ' . $name);

        $path = $this->getPath($container, $name);
        $fileStatus = json_decode($this->webHDFSClient->getFileStatus($path));

        if (isset($fileStatus->FileStatus)) {
            return [
                'name'           => $name,
                'content_type'   => null,
                'content_length' => $fileStatus->FileStatus->length,
                'last_modified'  => $fileStatus->FileStatus->modificationTime,
            ];
        } else {
            throw new DfException('Failed to list blob metadata: ' . json_encode($fileStatus->RemoteException));
        }
    }

    /**
     * {@inheritDoc}
     */
    public function streamBlob($container, $name, $params = [])
    {
        Log::error('streamBlob - $container = ' . $container . '; $name = ' . $name . '; $params = ' . implode($params));
        $path = $this->getPath($container, $name);
        $fileStatus = json_decode($this->webHDFSClient->getFileStatus($path));
        if (isset($fileStatus->FileStatus)) {
            if ($fileStatus->FileStatus->type === 'DIRECTORY') {
                header("HTTP/1.1 302");
                header("Content-Type: text/html");
                header("Location: $_SERVER[REQUEST_URI]/");
            } else {
                $disposition =
                    (isset($params['disposition']) && !empty($params['disposition'])) ? $params['disposition'] : 'inline';
                header('Content-Disposition: ' . $disposition . '; filename="' . $name . '";');
                header('Last-Modified: ' . gmdate('D, d M Y H:i:s T', $fileStatus->FileStatus->modificationTime));
                header('Content-Type: text/plain');
                header('Content-Length: ' . $fileStatus->FileStatus->length);


                $fileLength = $fileStatus->FileStatus->length;
                $chunk = \Config::get('df.file_chunk_size');
                for ($offset = 0; $offset < $fileLength; $offset += $chunk) {
                    echo $this->webHDFSClient->open($path, $offset, $chunk);
                }
            }
        }

    }

    /**
     * {@inheritDoc}
     */
    public function deleteBlob($container, $name, $noCheck = false)
    {
        Log::error('deleteBlob - $container = ' . $container . '; $name = ' . $name . '; $noCheck = ' . $noCheck);
        $path = $this->getPath($container, $name);
        $this->webHDFSClient->delete($path, $noCheck);
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

    /**
     * Compare Blob by number of slashes in name
     *
     * @param array $blobs which contains blobs
     * @param boolean $asc define the order (true - asc, first with fewer slashes; false - desc, first with more slashes)
     * @param string $field which use as blob path
     * @return array
     */
    protected function sortBlobListByDepth($blobs = [], $asc = true, $field = 'name') {
        usort($blobs, function ($a, $b) use ($asc, $field) {
            $result = preg_match_all('/\/[^\/]+/', $a[$field]) - preg_match_all('/\/[^\/]+/', $b[$field]);
            if ($result === 0) {
                $result = strcmp($a[$field], $b[$field]);
            }
            return $asc ? $result : $result * -1;
        });
        return $blobs;
    }
}
