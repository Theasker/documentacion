# Configurar autenticación con contraseña en Nginx

Creamos el fichero `.htpasswd` en `/etc/nginx/` y generamos el usuario y contraseña en ese fichero:
```bash
sudo sh -c "echo -n 'sammy:' >> /etc/nginx/.htpasswd"
sudo sh -c "openssl passwd -apr1 >> /etc/nginx/.htpasswd"
cat /etc/nginx/.htpasswd
```
```
Output
sammy:$apr1$wI1/T0nB$jEKuTJHkTOOWkopnXqC1d1
```

## Bibliografía
* https://www.digitalocean.com/community/tutorials/how-to-set-up-password-authentication-with-nginx-on-ubuntu-14-04