# Asovalvi Backend - API REST para Sistema de Gestión de Asociación de Viveros

**Asovalvi Backend** es la API REST desarrollada en Laravel que proporciona todos los servicios backend para el sistema de gestión integral de la asociación de viveros. Esta API maneja la lógica de negocio, autenticación, autorización y persistencia de datos para los módulos de reuniones, tareas, cartera y usuarios.

<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

## Características del Backend

### 🔐 Autenticación y Autorización
- **Laravel Sanctum**: Sistema de autenticación basado en tokens para API
- **Middleware de autorización**: Control granular por roles (Administrador, Secretario, Cartera, Asociado)
- **Guards personalizados**: Protección de rutas según permisos específicos

### 📊 Módulos Principales

#### 🏢 API de Reuniones
- **CRUD completo**: Crear, leer, actualizar y eliminar reuniones
- **Gestión de asistentes**: Endpoints para manejar participantes de reuniones
- **Orden del día**: API para agregar y categorizar temas de discusión
- **Generación de actas**: Procesamiento de datos para reportes automáticos

#### 📋 API de Tareas
- **Gestión de compromisos**: Endpoints para crear y gestionar tareas
- **Estados dinámicos**: Manejo de diferentes estados de tareas
- **Filtros avanzados**: Búsqueda por múltiples criterios
- **Vinculación con reuniones**: Relación entre tareas y reuniones origen

#### 💰 API de Cartera
- **Obligaciones financieras**: Gestión de compromisos de pago
- **Control de vencimientos**: Sistema de alertas automáticas
- **Reportes financieros**: Cálculos y resúmenes de cartera
- **Historial de pagos**: Trazabilidad completa de transacciones

#### 👥 API de Usuarios
- **Gestión de roles**: Sistema robusto de permisos por roles
- **Registro y validación**: Endpoints para crear y validar usuarios
- **Perfiles de usuario**: Información detallada de cada miembro

### 🗄️ Base de Datos
- **Migraciones estructuradas**: Schema bien definido con relaciones consistentes
- **Seeders incluidos**: Datos de prueba para desarrollo
- **Eloquent ORM**: Modelos con relaciones optimizadas
- **Integridad referencial**: Constraints y validaciones a nivel de BD

### 🛡️ Seguridad y Validación
- **Request validation**: Validaciones robustas en todas las entradas
- **Sanitización de datos**: Limpieza automática de inputs
- **Rate limiting**: Protección contra ataques de fuerza bruta
- **CORS configurado**: Para integración segura con frontend Angular

### 📡 Endpoints Principales

```
Authentication:
POST /api/login
POST /api/register
POST /api/logout

Reuniones:
GET /api/reuniones
POST /api/reuniones
PUT /api/reuniones/{id}
DELETE /api/reuniones/{id}

Tareas:
GET /api/tareas
POST /api/tareas
PUT /api/tareas/{id}
DELETE /api/tareas/{id}

Cartera:
GET /api/cartera
POST /api/cartera
PUT /api/cartera/{id}
DELETE /api/cartera/{id}

Usuarios:
GET /api/usuarios
POST /api/usuarios
PUT /api/usuarios/{id}
DELETE /api/usuarios/{id}
```

## Instalación y Configuración

```bash
# Clonar repositorio
git clone <repository-url>
cd asovalvi

# Instalar dependencias
composer install

# Configurar environment
cp .env.example .env
php artisan key:generate

# Configurar base de datos
php artisan migrate
php artisan db:seed

# Instalar Laravel Sanctum
php artisan vendor:publish --provider="Laravel\Sanctum\SanctumServiceProvider"

# Iniciar servidor de desarrollo
php artisan serve
```

## Tecnologías Utilizadas

- **Laravel 10+**: Framework PHP moderno y robusto
- **MySQL/PostgreSQL**: Base de datos relacional
- **Laravel Sanctum**: Autenticación de API
- **Eloquent ORM**: Mapeo objeto-relacional
- **Laravel Validation**: Validación de formularios server-side
- **Carbon**: Manejo avanzado de fechas y tiempo