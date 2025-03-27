# TENIS TOURNAMENT

### Se desarrollaron todos los puntos, aplicando buenas practicas y principios de clean architecture.

### API
- Endpoints REST.
- Validacion de parametros de entrada (tipos, rangos, valores validos).
- Control de errores y respuestas claras con codigos HTTP adecuados.
- Documentacion Swagger disponible en `/api/docs`.

### Tests
- Tests funcionales para los endpoints.
- Tests unitarios para clases de dominio y casos de uso.
- Tests de integracion para verificar la conexion entre capas y servicios reales.

### Generacion de jugadores
- Se desarrollo un servicio para generar nombres aleatorios de jugadores.
- Si hay nombres suficientes en cache, se usan directamente.
- Si faltan nombres, se consultan a una API externa (randomuser.me) solo la cantidad faltante, se almacenan en Redis y se combinan con los existentes.
- Redis actua solo como cache (los nombres no se eliminan al usarlos, se reutilizan).

### Redis
- Usado como cache para almacenar nombres por genero (first y last).
- Consulta eficiente sin pop: se leen posiciones fijas sin eliminar.

### Posibles mejoras
- Separar la logica de obtencion de nombres en otro Bundle o incluso en un microservicio.
