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

[API Doc](API_DOC.md)
