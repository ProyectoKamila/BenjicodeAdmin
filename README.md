# BenjicodeAdmin
Api Benjicode sistema de administra


######Vistas###############################

##Colocar las vistas realizadas por los maquetadores. ejemplo
#URL:
#NOMBRE DE LA FUNCION:
#ARCHIVOS DE VIEW:
#RESPONSABLE




######FUNCIONES-API###############################

#Colocar la funcion, los datos y la respuesta. ejemplo:

#todas las funciones responden: success: true/false; error: si el success es false; menssage: si el succes es true; answer: si el success es true, un array, o string dependiendo de la funcion

#NOMBRE DE LA FUNCION:
#DATOS NECESARIOS: 
#RESPUESTA:
#ENCARGADO:

#NOMBRE DE LA FUNCION: RIF
#DATOS NECESARIOS: Numero del rif en formato V-18910941-1
#RESPUESTA: nombre, agenteretencioniva, contriyente, tasa
#ENCARGADO: Juan Figueroa

#NOMBRE DE LA FUNCION: module
#DATOS NECESARIOS: companyid, tokkenuser enviados por GET o POST, 
#RESPUESTA: id, name, de los modulos a los que se tiene permisos
#ENCARGADO: Juan Figueroa, Alberto Toro

#NOMBRE DE LA FUNCION: direccion
#DATOS NECESARIOS: idparent: es el identificador del padre del state, town, parish; country: al mandar country te responde todos los paises, al enviar state mas el idparent responde los estados del pais que valga idparent, al enviar town mas el idparent, responde todos las ciudades del estado que valga idparent; al enviar parish mas el idparent, responde todas las parroquias que pertenezcan al valor de idparent todos estos datos son enviados por post
#RESPUESTA: arrya dependiendo de la consulta, al ser el success true
#ENCARGADO: Alberto Toro

#NOMBRE DE LA FUNCION: direccion_script
#DATOS NECESARIOS: responde un script que autocompleta los input type=select, de los identificadores: country, state, town, parish
#RESPUESTA: string que contiene todo el script, debe ser impreso despues de integrar la libreria jquery
#ENCARGADO: Alberto Toro


######FUNCIONES-INTERNAS-API###############################

#Cabe destacar que al ser de uso interno, no responden json, y son private, por ende solo se usan dentro de otras funciones en le api, para agilizar los codigos

#NOMBRE DE LA FUNCION:tokken_id
#DATOS NECESARIOS: token -> el de la empresa enviado por GET o POST o directo a la funcion tokken_id($token);
#RESPUESTA: un int, identificador de la empresa a la que pertenece el token
#ENCARGADO: Alberto Toro

#NOMBRE DE LA FUNCION:user_id
#DATOS NECESARIOS: tokkenuser -> el que guarda account en la COOKIE enviado por GET o POST o directo a la funcion user_id($tokkenuser);
#RESPUESTA: un int, identificador del user de account al que pertenece el token si esta logueado
#ENCARGADO: Alberto Toro

#NOMBRE DE LA FUNCION:permit_user
#DATOS NECESARIOS: $userid $companyid enviados directo a la funcion user_id($userid,$companyid); en ese orden en espefico
#RESPUESTA: los permisos que tiene el usuario en la empresa
#ENCARGADO: Alberto Toro