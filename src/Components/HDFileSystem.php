<?php

namespace DreamFactory\Core\Hadoop\Components;


use DreamFactory\Core\Exceptions\DfException;
use DreamFactory\Core\Exceptions\NotFoundException;
use DreamFactory\Core\File\Components\RemoteFileSystem;
use DreamFactory\Core\Hadoop\Utility\HDFSFileBlobTools;
use Exception;
use Illuminate\Support\Facades\Log;
use org\apache\hadoop\WebHDFS;
use org\apache\hadoop\WebHDFS_Exception;

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
     * @throws Exception
     */
    public function listContainers($include_properties = false)
    {
        $this->webHDFSClient->getHomeDirectory();
        return $this->listBlobs('/', '', '/');
    }

    /**
     * {@inheritDoc}
     */
    public function containerExists($container)
    {
        $response = json_decode($this->webHDFSClient->getFileStatus($container));
        return !isset($response->RemoteException);
    }

    /**
     * {@inheritDoc}
     * @throws NotFoundException
     */
    public function getContainer($container, $include_files = true, $include_folders = true, $full_tree = false)
    {
        if ($this->containerExists($container)) {
            return $this->getFolder($container, '', $include_files, $include_folders, $full_tree);
        }
        return [];
    }

    /**
     * {@inheritDoc}
     * @throws DfException
     */
    public function getContainerProperties($container)
    {
        $path = HDFSFileBlobTools::getPath($container);
        $fileStatus = json_decode($this->webHDFSClient->getFileStatus($path));

        if (isset($fileStatus->FileStatus)) {
            return [
                'name'           => $container,
                'content_type'   => null,
                'content_length' => null,
                'last_modified'  => $fileStatus->FileStatus->modificationTime,
                'hdfs'           => $fileStatus->FileStatus,
            ];
        } else {
            throw new DfException('Failed to list container metadata: ' . json_encode($fileStatus->RemoteException));
        }
    }

    /**
     * {@inheritDoc}
     * @param array|string $container
     * @throws DfException
     */
    public function createContainer($container, $check_exist = false)
    {
        if (gettype($container) === 'string') {
            $path = $container;
        } else {
            $path = $container['name'];
        }

        if ($check_exist) {
            $fileStatus = json_decode($this->webHDFSClient->getFileStatus($path));
            if (property_exists($fileStatus, 'FileStatus')) {
                throw new DfException('Fail to create container. Container already exists', 400);
            }
        }
        return json_decode($this->webHDFSClient->mkdirs($container));
    }

    /**
     * {@inheritDoc}
     * @throws DfException
     */
    public function updateContainerProperties($container, $properties = [])
    {
        $container = HDFSFileBlobTools::resolvePathFromUrl($container);

        $paths = explode('/', $container);
        $name = array_pop($paths);
        $path = preg_replace("/${name}$/", '', $container);

        foreach ($properties as $key => $value) {
            switch ($key) {
                case 'name':
                    $this->move($path . $name, $path . $value); break;
                case 'path':
                    $this->move($container, $value); break;
                case 'owner':
                    $this->webHDFSClient->setOwner($container, $value); break;
                case 'group':
                    $this->webHDFSClient->setOwner($container, '', $value); break;
                case 'acl':
                    $this->webHDFSClient->setAcl($container, $value); break;
                case 'permission':
                    $this->webHDFSClient->setPermission($container, $value); break;
                case 'replication':
                    $this->webHDFSClient->setReplication($container, $value); break;
                case 'modificationTime':
                    $this->webHDFSClient->setTimes($container, $value); break;
                case 'accessTime':
                    $this->webHDFSClient->setTimes($container, '', $value); break;
                case 'content':
                    try {
                        $content = $properties['is_base64'] ? base64_decode($value) : $value;
                        $this->webHDFSClient->createWithData($container, $content, true);
                    } catch (WebHDFS_Exception $e) {
                        throw new DfException($e->getMessage(), 400);
                    }
                    break;
                default: {
                    Log::warning("Unhandled container property [$key] with value [$value]");
                }
            }
        }
    }

    /**
     * {@inheritDoc}
     * @throws Exception
     */
    public function deleteContainer($container, $force = false)
    {
        $this->deleteBlob($container, null, $force);
    }

    /**
     * {@inheritDoc}
     */
    public function blobExists($container, $name)
    {
        $path = HDFSFileBlobTools::resolvePathFromUrl(HDFSFileBlobTools::getPath($container, $name));

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
    public function putBlobData($container, $name, $data = null, $type = null)
    {
        $path = HDFSFileBlobTools::getPath($container, $name);
        if (($data === null || $data === '') && $type === null) {
            $this->webHDFSClient->mkdirs($path);
        } elseif ($type === null && gettype(json_decode($data, true)) === 'array') {
            $this->updateContainerProperties(HDFSFileBlobTools::getPath($container, $name), json_decode($data, true));
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
        $path = HDFSFileBlobTools::getPath($container, $name);
        $this->webHDFSClient->create($path, $localFileName);
    }

    /**
     * {@inheritDoc}
     */
    public function copyBlob($container, $name, $src_container, $src_name, $properties = [])
    {
        $content = $this->getBlobData($container, $name);
        $this->putBlobData($container, $name, $content, 'text/plain');
    }

    /**
     * {@inheritDoc}
     */
    public function listBlobs($container, $prefix = '', $delimiter = '')
    {
        if ($prefix) {
            $path = HDFSFileBlobTools::getPath($container, $prefix);
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
            $encapsulatedContainerPath = preg_quote($container, '/');
            $responseBlob['name'] = preg_replace("/^${encapsulatedContainerPath}\/?/", '', $blob->path . $suffix, 1);
            $responseBlob['content_length'] = $blob->length;
            $responseBlob['last_modified'] = $blob->modificationTime;
            $responseBlob['content_type'] = null;
            // Save original HDFS responce
            $responseBlob['hdfs'] = $blob;

            $result[] = $responseBlob;
        }
        $sortedBlobList = HDFSFileBlobTools::sortBlobListByDepth($result, false);
        return json_decode(json_encode($sortedBlobList), true);
    }

    /**
     * {@inheritDoc}
     */
    public function getBlobAsFile($container, $name, $localFileName = null)
    {
        $content = $this->getBlobData($container, $name);
        file_put_contents($localFileName, $content);
    }

    /**
     * {@inheritDoc}
     */
    public function getBlobData($container, $name)
    {
        $path = HDFSFileBlobTools::getPath($container, $name);
        try {
            return $this->webHDFSClient->open($path);
        } catch (Exception $ex) {
            throw new DfException('Failed to retrieve blob "' . $name . '": ' . $ex->getMessage());
        }
    }

    /**
     * {@inheritDoc}
     */
    public function getBlobProperties($container, $name)
    {
        $path = HDFSFileBlobTools::getPath($container, $name);
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
        $path = HDFSFileBlobTools::getPath($container, $name);
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
    public function deleteBlob($container, $name = '', $noCheck = false)
    {
        $path = HDFSFileBlobTools::getPath($container, $name);
        $this->webHDFSClient->delete($path, $noCheck);
    }

    /**
     * @param $fromPath
     * @param $toPath
     * @throws DfException
     */
    protected function move($fromPath, $toPath) {
        $rs = json_decode($this->webHDFSClient->rename($fromPath, $toPath));
        if (property_exists($rs, 'RemoteException')) {
            throw new DfException(
                "Failed to move ${fromPath} to ${toPath}. Type: " . $rs->RemoteException->exception .
                ". Message: " . $rs->RemoteException->message,
                400
            );
        } elseif (property_exists($rs, 'boolean') && $rs->boolean === false) {
            throw new DfException(
                "Failed to move ${fromPath} to ${toPath}. Container with name ${fromPath} not exists",
                404
            );
        }
    }

}
