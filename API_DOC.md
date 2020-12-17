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

**Endpoint**: `api/v2/<hadoop-service>/`

**Method**: `POST` 
 
**Headers**
- Name: `X-Folder-Name` <br>
  Date type: `string` <br>
  Description: Folder name to create with full path. Parent folder should exists.

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

## Upload file from URL to root container

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

**Output**:
```json
{
  "name": "created file name",
  "path": "created file path",
  "type": "created file type"
}
```
 
## Upload ZIP archive from URL to root container and extract it

**Endpoint**: `api/v2/<hadoop-service>/`

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
