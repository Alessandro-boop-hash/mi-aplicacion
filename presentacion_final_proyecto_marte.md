# Informe Final: Proyecto MARTE - Sistema de Control de Producción para Taller de Confección Deportiva

---

## Diapositiva 1: Portada
### MARTE: Sistema de Control de Producción y Gestión para Taller de Confección Deportiva
- Metodología: RUP (Rational Unified Process) / UML
- Curso: Ingeniería de Requerimientos y Diseño de Software
- Integrantes: Anders Diaz Uriol - Alessandro Camacho
- Docente: Luis Miguel Arias
- Institución: Tecsup
- Ciclo: III

---

## Diapositiva 2: El Problema del Taller
### Desafíos operativos antes de MARTE
- El taller confecciona uniformes deportivos personalizados con nombres, números y logotipos de clubes.
- Las órdenes de producción se manejaban en papel y se perdían o dañaban en el taller.
- No existía trazabilidad digital en las fases de Corte, Estampado y Costura.
- Los clientes llamaban constantemente para preguntar el estado de sus pedidos.
- No se registraban las mermas de tela, afectando el cálculo real del costo de producción.

---

## Diapositiva 3: Alcance del Sistema
### Módulos que abarca la plataforma MARTE
- Gestión de Ventas y Pedidos: Registro detallado de pedidos por tallas, cantidades y tipo de tela.
- Trazabilidad en Taller: Los operarios registran avances desde tablets en cada estación de trabajo.
- Portal del Cliente: Consulta de estados de pedido, descarga de PDF y registro de reclamos.
- Notificaciones Automáticas: Alertas por correo y WhatsApp cuando el pedido cambia de fase.
- Exclusiones: No incluye pasarela de pago online ni logística de envío externo.

---

## Diapositiva 4: Modelo de Negocio (CUN)
### Casos de Uso del Negocio y Actores Externos
- Actores del Negocio: Cliente (solicita y paga) y Proveedor (abastece materia prima).
- CUN-01 Solicitar y Gestionar Pedido: El cliente solicita la cotización, el vendedor registra el pedido y el diseñador elabora la propuesta gráfica. Se requiere un abono mínimo del 50 por ciento.
- CUN-02 Confeccionar Prendas Deportivas: El pedido pasa por las estaciones secuenciales de Corte, Estampado y Costura hasta llegar a Control de Calidad.
- CUN-03 Abastecer Inventario: El proveedor suministra rollos de tela e insumos al almacén.
- CUN-04 Atender Reclamos Postventa: El cliente registra quejas por fallas en prendas ya entregadas.

---

## Diapositiva 5: Requerimientos del Sistema
### Funcionales y No Funcionales
- RF-01: Gestión de roles (Administrador, Vendedor, Diseñador, Operarios, Cliente).
- RF-02: Registro de pedidos con múltiples prendas, tallas, telas y precios.
- RF-04: Trazabilidad en taller mediante tablets en las estaciones de Corte, Estampado y Costura.
- RF-05: Notificaciones automáticas por correo y WhatsApp al cambiar el estado del pedido.
- RF-07: Registro de reclamos exclusivamente para pedidos en estado Entregado.
- RNF-01 Seguridad IDOR: Validación en middleware para que cada cliente solo vea sus propios pedidos.
- RNF-03 Portabilidad: Interfaz responsiva optimizada para tablets de taller de 8 y 10 pulgadas.

---

## Diapositiva 6: Análisis y Diseño UML
### Diagrama de Secuencia: Registro de Reclamo con Validación IDOR
- El Cliente envía una petición POST con el pedido_id y el motivo del reclamo.
- El AuthMiddleware valida que la sesión del usuario esté activa.
- El ReclamoController consulta el pedido en la base de datos MySQL.
- Validación de seguridad: Se verifica que el pedido pertenezca al cliente autenticado.
- Si es dueño del pedido: Se inserta el reclamo en la base de datos y se confirma el registro.
- Si NO es dueño (intento de hack IDOR): Se retorna HTTP 403 Acceso Denegado.
- Clases principales del sistema: User, Cliente, Pedido, PedidoEstadoHistorial y Reclamo.

---

## Diapositiva 7: Arquitectura y Despliegue
### Patrón MVC en Laravel e Infraestructura en la Nube
- Patrón Arquitectónico: Modelo-Vista-Controlador (MVC) en PHP Laravel 11.
- Modelo: Clases ORM Eloquent que encapsulan las transacciones de base de datos.
- Vista: Plantillas Blade con estilos Tailwind CSS para una identidad minimalista deportiva.
- Controlador: Controladores Laravel que procesan las peticiones HTTP.
- Base de Datos: MySQL 8.0 con integridad referencial y claves foráneas.
- Despliegue: Servidor web Apache con PHP 8.4 alojado en la nube Render.
- Servicios Externos: Twilio API para notificaciones de WhatsApp y servidor SMTP para correos.
- Dispositivos: PC del administrador, tablets en las mesas de taller y teléfonos móviles de clientes.

---

## Diapositiva 8: Conclusiones
### Resultados y Beneficios del Proyecto
- Metodología Sólida: El uso de RUP y UML garantizó una transición ordenada desde los requerimientos de negocio hasta el código fuente en Laravel.
- Trazabilidad Total: Los operarios ahora registran tiempos y mermas en tiempo real desde tablets, eliminando completamente el uso de papel en el taller.
- Seguridad Garantizada: El control de accesos a nivel de controlador previene vulnerabilidades IDOR y protege los datos de los clientes.
- Calidad Validada: MARTE cuenta con una suite de 55 pruebas automatizadas exitosas que validan la estabilidad del sistema en producción.
- Recomendaciones: Capacitar a los operarios en el uso de las tablets, monitorear las cuotas de la API de WhatsApp y ampliar el módulo de inventario para automatizar las compras a proveedores.
