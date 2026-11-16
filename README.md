# apsy-gestion
Sistema de gestión para peluquerías
# APSY – Sistema de Gestión para Peluquería

## 1. Descripción del proyecto

APSY es un sistema web de gestión diseñado para facilitar la administración de una peluquería. Su objetivo es centralizar la información de clientes, profesionales y turnos en una plataforma sencilla e intuitiva.

El proyecto fue desarrollado como trabajo académico utilizando tecnologías web y una base de datos relacional.

## 2. Tecnologías utilizadas

- **PHP:** lógica del sistema y procesamiento de formularios.
- **MySQL:** gestión y almacenamiento de datos.
- **HTML5:** estructura de las páginas.
- **CSS3:** diseño y estilos visuales.
- **JavaScript:** interactividad y validaciones del lado del cliente.
- **Bootstrap:** diseño adaptable a diferentes dispositivos.
- **XAMPP:** entorno de desarrollo local.

## 3. Requisitos para instalar el proyecto

Antes de comenzar, es necesario tener instalado:

- XAMPP.
- Un navegador web.
- Un editor de código, como Visual Studio Code.

## 4. Instalación y configuración

### Paso 1. Descargar el proyecto

Clonar el repositorio de GitHub o descargarlo como archivo ZIP.

Colocar la carpeta `apsy` dentro del directorio `htdocs` de XAMPP.

La ruta habitual es:

`C:\xampp\htdocs\apsy`

### Paso 2. Iniciar XAMPP

Abrir el panel de control de XAMPP e iniciar los servicios:

- Apache.
- MySQL.

### Paso 3. Crear e importar la base de datos

1. Abrir el navegador e ingresar a `http://localhost/phpmyadmin/`.
2. Crear una base de datos llamada `apsy`.
3. Seleccionar la base de datos creada.
4. Entrar en la pestaña **Importar**.
5. Seleccionar el archivo `sql/apsy.sql`.
6. Ejecutar la importación.

El archivo contiene la estructura de las tablas, sus relaciones y los datos iniciales necesarios para probar el sistema.

**Importante:** realizar la importación inicial sobre una base de datos nueva. El script contiene instrucciones que eliminan tablas existentes antes de crearlas nuevamente.

### Paso 4. Configurar la conexión

Abrir el archivo:

`includes/conexion.php`

Verificar que los datos de conexión coincidan con la configuración local de MySQL.

Configuración predeterminada para XAMPP:

- Servidor: `localhost`
- Usuario: `root`
- Contraseña: vacía
- Base de datos: `apsy`

Si la instalación local utiliza otros datos, modificarlos en el archivo de conexión.

### Paso 5. Acceder al sistema

Abrir el navegador e ingresar a:

`http://localhost/apsy/`

## 5. Credenciales de prueba

Para acceder al sistema se proporcionan las siguientes credenciales iniciales:

- **Usuario:** `mariana.lopez`
- **Contraseña:** `apsy2026`

Estas credenciales corresponden al usuario de prueba incluido en la base de datos.

## 6. Estructura del proyecto

```text
apsy/
├── index.php
├── login.php
├── logout.php
├── dashboard.php
├── clientes.php
├── profesionales.php
├── turnos.php
│
├── includes/
│   ├── conexion.php
│   ├── auth.php
│   ├── funciones.php
│   ├── header.php
│   ├── sidebar.php
│   ├── topbar.php
│   └── footer.php
│
├── parciales/
│   ├── modal_cliente.php
│   ├── modal_profesional.php
│   ├── modal_turno.php
│   ├── modal_eliminar.php
│   └── paginacion.php
│
├── assets/
│   ├── css/
│   ├── js/
│   └── img/
│
└── sql/
    └── apsy.sql
```

## 7. Funcionalidades del sistema

El sistema contempla los siguientes módulos:

- **Inicio de sesión:** acceso mediante usuario y contraseña.
- **Dashboard:** panel principal con información general y próximos turnos.
- **Clientes:** gestión de la información de los clientes.
- **Profesionales:** administración de los profesionales de la peluquería.
- **Turnos:** registro, modificación, cancelación y consulta de turnos.

## 8. Estado del proyecto

Proyecto académico en desarrollo.

Las funcionalidades y características pueden actualizarse durante el proceso de implementación.

---

**APSY – Gestión inteligente para peluquerías.**
