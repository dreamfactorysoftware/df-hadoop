## DreamFactory Hadoop

### How to develop when package not released

1. Run `docker-compose up -d`

2. Login to web container: `docker exec web bash`

3. Add this package as repository
```json
{
  "type": "path",
  "url": "../dev/df-hadoop"
}
```

4. Run composer upgrade: `composer upgrade`

### API Doc

[Link](API_DOC.md)

### Connecting to Hadoop HDFS

To create a service for connecting to Hadoop HDFS storage, follow these steps.

1. Go to the Services tab in the admin console.
2. Click Create.
3. Set the Service Type to File > Hadoop HDFS.
4. Enter a name and label for the service. Name will be part of the URL for accessing the service via the REST API.
5. Go to the Config tab for the new service.
6. Enter your Hadoop Cluster Hostname
7. Check 'Use SSL?', if your cluster use SSL.
8. Enter your WebHDFS Server Port. Usually it is 50070.
9. Enter the username you want to use when connecting to HDFS. DreamFactory does not support Kerberos yet.
10. (Optional) Enter Namenode RPC Host and Port. Allows you to specify servers for write.
11. Enter Container. The container points to the root directory for this service. Can be just '/'.
12. Click Create Service to save your new service.
13. Go to the API Docs tab in the admin console to test your new service.
