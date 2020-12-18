<?php


namespace DreamFactory\Core\Hadoop\Utility;


class HDFSFileBlobTools
{

    /**
     * Compare Blob by number of slashes in name
     *
     * @param array $blobs which contains blobs
     * @param boolean $asc define the order (true - asc, first with fewer slashes; false - desc, first with more slashes)
     * @param string $field which use as blob path
     * @return array
     */
    public static function sortBlobListByDepth($blobs = [], $asc = true, $field = 'name')
    {
        usort($blobs, function ($a, $b) use ($asc, $field) {
            $result = preg_match_all('/\/[^\/]+/', $a[$field]) - preg_match_all('/\/[^\/]+/', $b[$field]);
            if ($result === 0) {
                $result = strcmp($a[$field], $b[$field]);
            }
            return $asc ? $result : $result * -1;
        });
        return $blobs;
    }

    /**
     * @param $container
     * @param $name
     * @return string
     */
    public static function getPath($container, $name = '')
    {
        if (empty($name)) {
            return $container;
        }
        if (preg_match('/.*\/$/', $container)) {
            $path = $container . $name;
        } else {
            $path = $container . '/' . $name;
        }
        return $path;
    }

    public static function resolvePathFromUrl($container)
    {
        $path = \Request::decodedPath();
        if ($path[strlen($path) - 1] !== '/' && $container[strlen($container) - 1] === '/') {
            return substr($container, 0, strlen($container) - 1);
        }
        return $container;
    }
}
