## Table of content

- [List the resources (folders and files) available in this storage](#list-the-resources-folders-and-files-available-in-this-storage)
- [Create folder](#create-folder)
- [Create file](#create-file)
- [Upload file from URL](#upload-file-from-url)
- [Upload ZIP archive from URL and extract it](#upload-zip-archive-from-url-and-extract-it)
- [Update root container properties](#update-root-container-properties)
- [Update file/directory properties](#update-filedirectory-properties)
- [Update file content](#update-file-content)
- [Delete directories/files](#delete-directoriesfiles)
- [Delete file](#delete-file)
- [Delete directory](#delete-directory)
- [Download file content](#download-file-content)
- [Retrieve file parameters](#retrieve-file-parameters)
- [Retrieve directory parameters](#retrieve-directory-parameters)

## List the resources (folders and files) available in this storage

**Endpoint**: `api/v2/<hadoop-service>/`

**Method**: `GET` 

**Query**: 
- Name: `as_list` <br> 
  Data type: `boolean` <br>
  Description: Return only a list of the resource identifiers. Does not work with `as_access_list`, `zip`
  Output if true:
  ```json
  {
    "resource": [
      "folder1/",
      "folder2/",
      "file1.txt",
      "file2.txt"
    ]
  }
  ```
- Name: `as_access_list` <br> 
  Data type: `boolean` <br>
  Description: Returns a list of the resources for role access designation. Does not work with `as_list`, `zip`
  Output if true:
  ```json
  {
    "resource": [
      "",
      "*",
      "folder1/subfolder1/",
      "folder1/subfolder1/*",
      "folder1/",
      "folder1/*",
      "folder2/",
      "folder2/*"
    ]
  }
  ```
- Name: `include_folders` <br> 
  Data type: `boolean` <br>
  Description: Include folders in the returned listing. Default is true.
- Name: `include_files` <br> 
  Data type: `boolean` <br>
  Description: Include files in the returned listing. Default is true.
- Name: `full_tree` <br> 
  Data type: `boolean` <br>
  Description: List the contents of all sub-folders as well.
- Name: `zip` <br> 
  Data type: `boolean` <br>
  Description: Return the content of the path as a zip file. Does not work with `as_list`, `as_access_list`
- Name: `search` <br> 
  Data type: `string` <br>
  Description: Search for file or folder by name.
  
**Output**:
<details>
    <summary>JSON</summary>

```json
{
  "resource": [
    {
      "name": "folder2",
      "content_length": 0,
      "last_modified": 1608209460534,
      "content_type": null,
      "hdfs": {
        "accessTime": 0,
        "blockSize": 0,
        "childrenNum": 0,
        "fileId": 16479,
        "group": "supergroup",
        "length": 0,
        "modificationTime": 1608209460534,
        "owner": "root",
        "pathSuffix": "folder2",
        "permission": "755",
        "replication": 0,
        "storagePolicy": 0,
        "type": "DIRECTORY",
        "path": "a0/folder2"
      },
      "path": "folder2/",
      "type": "folder"
    },
    {
      "name": "folder1",
      "content_length": 0,
      "last_modified": 1608209473148,
      "content_type": null,
      "hdfs": {
        "accessTime": 0,
        "blockSize": 0,
        "childrenNum": 2,
        "fileId": 16478,
        "group": "supergroup",
        "length": 0,
        "modificationTime": 1608209473148,
        "owner": "root",
        "pathSuffix": "folder1",
        "permission": "755",
        "replication": 0,
        "storagePolicy": 0,
        "type": "DIRECTORY",
        "path": "a0/folder1"
      },
      "path": "folder1/",
      "type": "folder"
    },
    {
      "name": "file2.txt",
      "content_length": 2,
      "last_modified": 1608209539960,
      "content_type": null,
      "hdfs": {
        "accessTime": 1608209539950,
        "blockSize": 134217728,
        "childrenNum": 0,
        "fileId": 16484,
        "group": "supergroup",
        "length": 2,
        "modificationTime": 1608209539960,
        "owner": "root",
        "pathSuffix": "file2.txt",
        "permission": "755",
        "replication": 1,
        "storagePolicy": 0,
        "type": "FILE",
        "path": "a0/file2.txt"
      },
      "path": "file2.txt",
      "type": "file"
    },
    {
      "name": "file1.txt",
      "content_length": 2,
      "last_modified": 1608209548287,
      "content_type": null,
      "hdfs": {
        "accessTime": 1608209548269,
        "blockSize": 134217728,
        "childrenNum": 0,
        "fileId": 16485,
        "group": "supergroup",
        "length": 2,
        "modificationTime": 1608209548287,
        "owner": "root",
        "pathSuffix": "file1.txt",
        "permission": "755",
        "replication": 1,
        "storagePolicy": 0,
        "type": "FILE",
        "path": "a0/file1.txt"
      },
      "path": "file1.txt",
      "type": "file"
    }
  ]
}
```

</details>


**Output with full tree**:
<details>
    <summary>JSON</summary>

```json
{
  "resource": [
    {
      "name": "folder1/subfolder2",
      "content_length": 0,
      "last_modified": 1608209473148,
      "content_type": null,
      "hdfs": {
        "accessTime": 0,
        "blockSize": 0,
        "childrenNum": 0,
        "fileId": 16481,
        "group": "supergroup",
        "length": 0,
        "modificationTime": 1608209473148,
        "owner": "root",
        "pathSuffix": "subfolder2",
        "permission": "755",
        "replication": 0,
        "storagePolicy": 0,
        "type": "DIRECTORY",
        "path": "a0/folder1/subfolder2"
      },
      "path": "folder1/subfolder2/",
      "type": "folder"
    },
    {
      "name": "folder1/subfolder1",
      "content_length": 0,
      "last_modified": 1608209467270,
      "content_type": null,
      "hdfs": {
        "accessTime": 0,
        "blockSize": 0,
        "childrenNum": 0,
        "fileId": 16480,
        "group": "supergroup",
        "length": 0,
        "modificationTime": 1608209467270,
        "owner": "root",
        "pathSuffix": "subfolder1",
        "permission": "755",
        "replication": 0,
        "storagePolicy": 0,
        "type": "DIRECTORY",
        "path": "a0/folder1/subfolder1"
      },
      "path": "folder1/subfolder1/",
      "type": "folder"
    },
    {
      "name": "folder2",
      "content_length": 0,
      "last_modified": 1608209460534,
      "content_type": null,
      "hdfs": {
        "accessTime": 0,
        "blockSize": 0,
        "childrenNum": 0,
        "fileId": 16479,
        "group": "supergroup",
        "length": 0,
        "modificationTime": 1608209460534,
        "owner": "root",
        "pathSuffix": "folder2",
        "permission": "755",
        "replication": 0,
        "storagePolicy": 0,
        "type": "DIRECTORY",
        "path": "a0/folder2"
      },
      "path": "folder2/",
      "type": "folder"
    },
    {
      "name": "folder1",
      "content_length": 0,
      "last_modified": 1608209473148,
      "content_type": null,
      "hdfs": {
        "accessTime": 0,
        "blockSize": 0,
        "childrenNum": 2,
        "fileId": 16478,
        "group": "supergroup",
        "length": 0,
        "modificationTime": 1608209473148,
        "owner": "root",
        "pathSuffix": "folder1",
        "permission": "755",
        "replication": 0,
        "storagePolicy": 0,
        "type": "DIRECTORY",
        "path": "a0/folder1"
      },
      "path": "folder1/",
      "type": "folder"
    },
    {
      "name": "file2.txt",
      "content_length": 2,
      "last_modified": 1608209539960,
      "content_type": null,
      "hdfs": {
        "accessTime": 1608209539950,
        "blockSize": 134217728,
        "childrenNum": 0,
        "fileId": 16484,
        "group": "supergroup",
        "length": 2,
        "modificationTime": 1608209539960,
        "owner": "root",
        "pathSuffix": "file2.txt",
        "permission": "755",
        "replication": 1,
        "storagePolicy": 0,
        "type": "FILE",
        "path": "a0/file2.txt"
      },
      "path": "file2.txt",
      "type": "file"
    },
    {
      "name": "file1.txt",
      "content_length": 2,
      "last_modified": 1608209548287,
      "content_type": null,
      "hdfs": {
        "accessTime": 1608209548269,
        "blockSize": 134217728,
        "childrenNum": 0,
        "fileId": 16485,
        "group": "supergroup",
        "length": 2,
        "modificationTime": 1608209548287,
        "owner": "root",
        "pathSuffix": "file1.txt",
        "permission": "755",
        "replication": 1,
        "storagePolicy": 0,
        "type": "FILE",
        "path": "a0/file1.txt"
      },
      "path": "file1.txt",
      "type": "file"
    }
  ]
}
```


</details>

## Create folder

**Endpoint**: `api/v2/<hadoop-service>/` or `api/v2/<hadoop-service>/<folder-path>` 

**Method**: `POST` 
 
**Headers**
- Name: `X-Folder-Name` <br>
  Date type: `string` <br>
  Description: Folder name to create with full path. Parent folder should exists. 
  If a `folder-path` is present, they will be joined in one path. 
  For example, `folder-path` is empty and `X-Folder-Name` is 'folder1'. The result path will be '/folder1'. 
  `folder-path` is 'folder1' and `X-Folder-Name` is 'folder2'. The result path will be '/folder1/folder2'.

**Output**:
```json
{
  "name": "created folder name",
  "path": "created folder path"
}
```

## Create file

**Endpoint**: `api/v2/<hadoop-service>/`

**Method**: `POST` 

**Query**: 
- Name: `check_exist` <br> 
  Data type: `boolean` <br>
  Value: `true` or `false` <br>
  Description: If true, the request fails when the file or folder to create already exists.
 
**Headers**
- Name: `X-File-Name` <br>
  Date type: `string` <br>
  Description: File name to create with full path. Parent folder should exists.

**Payload type**: `binary`

**Payload example**:
```text
File content
``` 

**Output**:
```json
{
  "name": "created file name",
  "path": "created file path",
  "type": "file"
}
``` 

## Upload file from URL

**Endpoint**: `api/v2/<hadoop-service>/`

**Method**: `POST` 

**Query**: 
- Name: `url` <br> 
  Data type: `string` <br>
  Description: The full URL of the file to upload.

**Payload type**: `JSON`

**Payload example:**
```json
{
  "filename": "new file name"
}
``` 

`filename` parameter is optional. If not defined, DreamFactory will use file name from URL.
`filename` can full file path. But all parent directories must exist.

**Output**:
```json
{
  "name": "created file name",
  "path": "created file path",
  "type": "created file type"
}
```

## Upload ZIP archive from URL and extract it

**Endpoints**: `api/v2/<hadoop-service>/` or `api/v2/<hadoop-service>/<file-path>`

**Method**: `POST` 

**Query**: 
- Name: `url` <br> 
  Data type: `string` <br>
  Description: The full URL of the file to upload.
- Name: `extract`<br>
  Data type: `boolean`<br>
  Value: `true`<br>
  Description: Extract an uploaded zip file into the folder.
- Name: `clean`<br>
  Data type: `boolean`<br>
  Value: `true` or `false`<br>
  Description: Option when 'extract' is true, clean the current folder before extracting files and folders.

**Payload type**: `JSON`

**Payload example**:
```json
{
  "filename": "new file name"
}
``` 

_Warning. The payload variable `filename` variable will be replaced by path `file-path` variable is they both exist._ 

## Update root container properties

_Warning. All changes will be applied to the container specified in the service configurations. 
Changes to the container may result in loss of access to it._

**Endpoint**: `api/v2/<hadoop-service>/`

**Method**: `PATCH`

**Payload type**: `JSON`

**Payload parameters**:

- `name` - change directory name. Does not work with the `path` parameter.
- `path` - change directory path. Must be the absolute way. All parent directories must exist. Does not work with the `name` parameter.
- `owner` - set directory owner 
- `group` - set directory group
- `acl` - set directory ACL. [Apache Hadoop HDFS Access Control Lists](https://hadoop.apache.org/docs/r2.7.1/hadoop-project-dist/hadoop-hdfs/HdfsPermissionsGuide.html#ACLs_Access_Control_Lists) 
- `permission` - set directory permission 
- `replication` - set directory replication
- `modificationTime` - set directory modification time
- `accessTime` - set directory access time

**Payload example**:
```json
{
  "name": "root-dir",
  "owner": "root",
  "group": "supergroup"
}
``` 

## Update file/directory properties

**Endpoint**: `api/v2/<hadoop-service>/<file-or-directory-path>`

**Method**: `PATCH`

**Payload type**: `JSON`

**Payload parameters**:

- `name` - change directory name. Does not work with the `path` parameter.
- `path` - change directory path. Must be the absolute way. All parent directories must exist. Does not work with the `name` parameter.
- `owner` - set directory owner 
- `group` - set directory group
- `acl` - set directory ACL. [Apache Hadoop HDFS Access Control Lists](https://hadoop.apache.org/docs/r2.7.1/hadoop-project-dist/hadoop-hdfs/HdfsPermissionsGuide.html#ACLs_Access_Control_Lists) 
- `permission` - set directory permission 
- `replication` - set directory replication
- `modificationTime` - set directory modification time
- `accessTime` - set directory access time
- `content` - new content of file
- `is_base64` - works only with `content`. Automatically decodes content from base64 when writing.

**Payload example**:
```json
{
  "content": "RHJlYW1GYWN0b3J5IGlzIGF3ZXNvbWUK",
  "is_base64": true
}
``` 

## Update file content

**Endpoint**: `api/v2/<hadoop-service>/<file-path>`

**Method**: `PUT`

**Payload type**: `raw`

Payload must contain the new content of the file. If the payload is empty, the file will be cleared.



## Delete directories/files

**Endpoint**: `api/v2/<hadoop-service>/`

**Method**: `DELETE`

**Query**: 
- Name: `force` <br> 
  Data type: `boolean` <br>
  Description: Set to true to force delete on a non-empty folder.

**Payload type**: `JSON`

**Payload parameters**:

- `resource` (array) 
    - `name` - (optional) file/directory name. DreamFactory will use root directory as path. Does not work with `path`
    - `path` - (optional) absolute file/directory path. It will overwrite `name` parameter
    - `type` - (required) resource type. `folder` or `file`
    
**Payload example**:
```json
{
  "resource": [
    {
      "name": "file.txt",
      "type": "file"
    },
    {
      "name": "directory1",
      "type": "folder"
    },
    {
      "path": "/directory1/subdirectory1/directory1",
      "type": "folder"
    },
    {
      "path": "/directory1/subdirectory1/file1.txt",
      "type": "file"
    }
  ]
}
```

## Delete file

**Endpoint**: `api/v2/<hadoop-service>/<file-path>`

**Method**: `DELETE`

## Delete directory

**Endpoint**: `api/v2/<hadoop-service>/<file-path>`

**Method**: `DELETE`

**Query**: 
- Name: `force` <br> 
  Data type: `boolean` <br>
  Description: Set to true to force delete on a non-empty folder.

## Download file content

**Endpoint**: `api/v2/<hadoop-service>/<file-path>`

**Method**: `GET`

**Query**: 
- Name: `download` <br> 
  Data type: `boolean` <br>
  Description: Prompt the user to download the file from the browser.

## Retrieve file parameters

**Endpoint**: `api/v2/<hadoop-service>/<file-path>`

**Method**: `GET`

**Query**: 
- Name: `include_properties` <br> 
  Data type: `boolean` <br>
  Value: `true` <br>
  Description: Only show file properties.
- Name: `content` <br> 
  Data type: `boolean` <br>
  Description: Show content (base64 encoded) when displaying file properties.
- Name: `is_base64` <br> 
  Data type: `boolean` <br>
  Description: Set this to false to show file content in plain text. Otherwise shown in base64 encoded.

## Retrieve directory parameters

**Endpoint**: `api/v2/<hadoop-service>/<directory-path>`

**Method**: `GET`

**Query**: 
- Name: `include_properties` <br> 
  Data type: `boolean` <br>
  Description: Return any properties of the folder in the response.
- Name: `include_folders` <br> 
  Data type: `boolean` <br>
  Description: Include folders in the returned listing. Default is true.
- Name: `include_files` <br> 
  Data type: `boolean` <br>
  Description: Include files in the returned listing. Default is true.
- Name: `full_tree` <br> 
  Data type: `boolean` <br>
  Description: List the contents of all sub-folders as well.
- Name: `zip` <br> 
  Data type: `boolean` <br>
  Description: Return the content of the folder as a zip file.
- Name: `search` <br> 
  Data type: `string` <br>
  Description: Search for file or folder by name.
