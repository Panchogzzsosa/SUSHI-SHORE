# Sushi Shore - Sistema de Reservas

Este proyecto es un sistema de reservas web para el restaurante Sushi Shore. Permite a los clientes seleccionar fecha, hora, número de personas y elegir visualmente su mesa o asientos en la barra a través de un plano interactivo minimalista.

## Características principales
- Selección de fecha con calendario emergente (Flatpickr)
- Horarios fijos configurables
- Plano de mesas SVG visto desde arriba, con sillas alrededor de cada mesa
- Barra con asientos individuales seleccionables
- Colores visuales para disponibilidad: verde (disponible), rojo (ocupado), azul (seleccionado)
- Interfaz moderna, responsiva y minimalista

## Requisitos
- PHP 7.4+
- Servidor web (Apache, Nginx, XAMPP, etc.)
- Base de datos MySQL (estructura sugerida en `database.sql`)
- Composer (opcional, para dependencias PHP)

## Instalación
1. Clona o descarga este repositorio en tu servidor local o hosting.
2. Configura la base de datos en `config/database.php` según tus credenciales.
3. Importa el archivo `database.sql` en tu base de datos MySQL.
4. Asegúrate de que la carpeta `assets/` y sus subcarpetas sean accesibles.
5. Accede a `index.php` desde tu navegador para ver la página principal.

## Uso
1. El cliente ingresa a la página de reservas.
2. Selecciona la fecha, hora y número de personas.
3. El sistema muestra el plano de mesas y la barra:
   - Solo se pueden seleccionar mesas con capacidad suficiente y disponibles.
   - La barra permite seleccionar asientos individuales.
4. El cliente selecciona su mesa o asientos y completa la reserva.
5. El sistema bloquea la mesa/asientos para ese horario.

## Personalización del plano de mesas
- Puedes modificar la distribución, cantidad y capacidad de las mesas en el archivo `reservas.php`, en la sección de configuración de mesas:
  ```js
  const mesas = [
      { id: 'mesa1', nombre: 'Mesa 1', capacidad: 2 },
      ...
  ];
  ```
- Para cambiar la cantidad de asientos de la barra, ajusta el valor de `barra.capacidad`.
- Para modificar colores o estilos, edita los estilos CSS en el mismo archivo o en `assets/css/style.css`.

## Dependencias externas
- [Bootstrap 5](https://getbootstrap.com/)
- [Flatpickr](https://flatpickr.js.org/)
- [FontAwesome](https://fontawesome.com/)

## Notas
- La lógica de ocupación de mesas está simulada en JS para pruebas. Para producción, conecta la consulta de disponibilidad con tu base de datos.
- Puedes adaptar el diseño del SVG para que se ajuste a la distribución real de tu restaurante.

---

**Desarrollado para Sushi Shore.** 