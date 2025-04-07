# OpenShift en AWS

## Introducción
Basado en kubernetes, pero abarca más que eso.
El ciclo de vida depende de donde se vaya a desplegar (local, cloud)

# Tipos de ROSA
* ROSA with HCP 
  * **Hosted control plain**
  * beneficios: usa sólo 3 nodos (workers)
  * En un futuro próximi, si no se usa podremos reducir los workers a 0 usando en panel de control
* Classic

## Visibilidad de un servicio
* **público**
  * Los `ingress` (los puertos / servicios que exponemos al público) 
* **privado**: Para acceder a los servicios tendremos que acceder desde el API de AWS ya que se despliega en una red privada

## STS (Security Token Service)
Se usa con un JWT (Jason Web Token) firmado con una clave privada para conectar y asumir un rol que OpenShift permite la acción según los permisos de ese rol.

## COGNITO for IDP

## CloudWatch = Log

rosa-w5wjj
oc login https://api.rosa-w5wjj.mxp8.p1.openshiftapps.com:6443 --username cluster-admin --password T9eYJ-mQB9c-KfYm9-e524B
https://oauth-openshift.apps.rosa-w5wjj.mxp8.p1.openshiftapps.com/oauth2callback/Cognito

"UserPoolId": "us-east-2_p1WkJu6nS",
"ClientName": "rosa-w5wjj",
"ClientId": "27o8r3oiah59lhsm3581na3oeq",
"ClientSecret": "58csr8jfihuoe43e222u93i1fbhuu12nfkvjvgg4vcuiht6cvp9",

Client ID: 27o8r3oiah59lhsm3581na3oeq
Client Secret: 58csr8jfihuoe43e222u93i1fbhuu12nfkvjvgg4vcuiht6cvp9

us-east-2_2mQDANs0v

$ oc whoami --show-console
echo $COGNITO_ADMIN_PASSWORD
https://console-openshift-console.apps.rosa-w5wjj.mxp8.p1.openshiftapps.com
F3PNDHF1qNKt-2@23

rosa describe cluster -c rosa-w5wjj -o json | jq -r '.console.url'

## kubeVir que es una extensión de kubernetes