# Minio

## Crea un cluster con 3 servidores

```bash
./bin/minio server --address :9000 \
  http://192.168.122.126/home/theasker/data \
  http://192.168.122.21/home/theasker/data \
  http://192.168.122.18/home/theasker/data
```

## Bibliografía
https://www.youtube.com/watch?v=XQrX-jQk5zM
https://www.albertcoronado.com/2024/11/20/tutorial-minio-persistencia-de-objetos-en-la-nube
https://github.com/minio/minio