# TP WEB 2 - PARTE 2
Segunda entrega para el trabajo practico de Web II. Se generó una API REST de carácter publica  para la empresa de viajes "Travello". La misma se puede consumir por Postman con los siguientes endpoints: 

 **El endpoint de la API es (Trae todos los viajes):** 
 http://localhost/carpetalocal/Travello-rest/api/trips
		  > Method = GET 
			 URL = api/trips 
			 Code = 200 
			 Response = array type json
             
 **Traer viaje por id:** http://localhost/carpetalocal/Travello-rest/api/trips/:ID
 
		   >Method = GET
			URL = api/trips/:ID 
			Code = 200 
			Response = Blog
			      
			
 **Crear un viaje:** http://localhost/carpetalocal/Travello-rest/api/trips

			>Method = POST
			URL = api/trips 
			Code = 201 
			Response = Blog
	...		
	Insertar viaje con el siguiente formato: 
		"date": "25/10/22",
        "passengers": 30,
        "placeOfDeparture": "Doha (QAT)",
        "placeOfDestination": "Buenos Aires(ARG)",
        "price": 203.678,
        "airline": 3	
	...
			
**Editar un viaje:** http://localhost/carpetalocal/Travello-rest/api/trips/:ID

			>Method = PUT
			URL = api/trips/:ID 
			Code = 201
			Response = Blog
	...
	Modificar un viaje con el siguente formato: 
		"date": "23/12/22",
		"passengers": 56,
		"price":89.236
	...
	
**Borrar un viaje:** http://localhost/carpetalocal/Travello-rest/api/trips/:ID

			>Method = DELETE
			URL = api/trips/:ID 
			Code = 200 
			Response = Blog

**Paginación (Agregar parámetros de consulta a las solicitudes GET):** http://localhost/carpetalocal/Travello-rest/api/trips?page=number&limit=number

**Ordenado (Agregar parámetros de consulta a las solicitudes GET):** http://localhost/carpetalocal/Travello-rest/api/trips?sort=field&order=desc
```
El pedido por defecto es descendiente
Se puede aordenar de forma ascendente con = asc 
```
