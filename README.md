# InvesWes
  
![alt text](https://github.com/fqlenos/InvesWes/blob/main/imgs/inveswes.png "InvesWes Logo")  
  
InvesWes se trata de una página web que recoje diferentes *canales de inversión*. Estos son espacios privados gestionados por una persona experta en compra/venta de criptomonedas. En él se muestran ideas activas de diferentes tipos de monedas digitales. El usuario puede suscribirse a tantos canales como quiera. Del mismo modo, un usuario puede convertirse en administrador de un canal cuando lo desee.  
  
Las suscripciones son de *pago*, en este caso solo son aceptados los pagos mediante la última version de TLMCoins hasta la fecha (TLMCoins v2).  

## PUBLIC API CALLS  

InvesWes hace uso de una API propietaria de [cex.io](https://cex.io/rest-api#public-api-calls) para automatizar los accesos a un *data market* y tener así los precios de cada moneda actualizados al momento.
  
## Desarrollo  

Es una página web diseñada y creada para las asignaturas de __Servicios Web: Cliente__ y __Servicios Web: Servidor__ de la Universidad Pública de Navarra por __@fqlenos__.  

## Usage  

Por seguridad se han retirado los credenciales de PHPMailer. Pero es necesaria dicha configuración para la verificación de los usuarios recién registrados. Por lo que para su uso se han de establecer credenciales válidos. (PHPMailer está configurado para el servidor SMTP de Gmail).
