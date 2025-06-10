# Asociación de Viveros - Sistema de Gestión

## Descripción del Sistema

Sistema de gestión integral para una asociación de viveros que permite administrar:
- 📋 **Tareas** y asignaciones entre miembros
- 🤝 **Reuniones** y control de asistencia  
- 💰 **Finanzas** de la asociación (ingresos, gastos, cuotas)
- 👥 **Usuarios** con roles específicos de la asociación

## Instalación y Configuración

```bash
# 1. Copiar archivo de entorno
cp .env.example .env

# 2. Generar clave de aplicación
php artisan key:generate

# 3. Configurar base de datos en .env
# DB_DATABASE=asociacion_viveros
# DB_USERNAME=tu_usuario
# DB_PASSWORD=tu_password

# 4. Ejecutar migraciones y seeders
php artisan migrate:fresh --seed

# 5. Instalar Laravel Sanctum
php artisan vendor:publish --provider="Laravel\Sanctum\SanctumServiceProvider"
```

## Roles del Sistema

- **Admin**: Acceso total al sistema
- **President**: Gestión de usuarios y finanzas
- **Secretary**: Gestión de reuniones y tareas
- **Treasurer**: Gestión financiera y cuotas
- **Member**: Acceso básico a tareas y reuniones asignadas

## API Endpoints

### 🔐 Autenticación
```
POST   /api/login           # Iniciar sesión
POST   /api/register        # Registrarse
POST   /api/logout          # Cerrar sesión
GET    /api/user            # Perfil del usuario
```

### 📋 Gestión de Tareas
```
GET    /api/tasks           # Listar tareas
POST   /api/tasks           # Crear tarea
PUT    /api/tasks/{id}      # Actualizar tarea
DELETE /api/tasks/{id}      # Eliminar tarea
POST   /api/tasks/{id}/complete   # Marcar completada
POST   /api/tasks/{id}/assign     # Asignar tarea
```

### 🤝 Gestión de Reuniones
```
GET    /api/meetings        # Listar reuniones
POST   /api/meetings        # Crear reunión
PUT    /api/meetings/{id}   # Actualizar reunión
DELETE /api/meetings/{id}   # Eliminar reunión
POST   /api/meetings/{id}/attend  # Marcar asistencia
POST   /api/meetings/{id}/start   # Iniciar reunión
POST   /api/meetings/{id}/end     # Finalizar reunión
GET    /api/meetings/{id}/attendees # Ver asistentes
```

### 💰 Gestión Financiera (Treasurer/President/Admin)
```
GET    /api/financial-transactions      # Listar transacciones
POST   /api/financial-transactions      # Crear transacción
PUT    /api/financial-transactions/{id} # Actualizar transacción
DELETE /api/financial-transactions/{id} # Eliminar transacción
POST   /api/financial-transactions/{id}/approve # Aprobar

GET    /api/member-fees     # Listar cuotas
POST   /api/member-fees/{id}/pay # Pagar cuota
GET    /api/financial-reports    # Reportes financieros
```

### 👥 Gestión de Usuarios (Admin/President)
```
GET    /api/users           # Listar usuarios
POST   /api/users           # Crear usuario
PUT    /api/users/{id}      # Actualizar usuario
DELETE /api/users/{id}      # Eliminar usuario
POST   /api/users/{id}/activate   # Activar usuario
POST   /api/users/{id}/deactivate # Desactivar usuario
```

### 📊 Dashboard
```
GET    /api/dashboard                    # Dashboard personalizado
GET    /api/dashboard/tasks-summary      # Resumen de tareas
GET    /api/dashboard/upcoming-meetings  # Próximas reuniones
GET    /api/dashboard/financial-summary  # Resumen financiero
```

## Estructura de Base de Datos

### Tablas Principales:
- **users** - Usuarios con roles de la asociación
- **tasks** - Tareas y asignaciones
- **meetings** - Reuniones programadas
- **meeting_attendees** - Control de asistencia
- **financial_transactions** - Movimientos financieros
- **member_fees** - Cuotas de socios

### Tipos de Transacciones Financieras:
- **Ingresos**: cuotas de socios, eventos, donaciones, subvenciones
- **Gastos**: oficina, eventos, mantenimiento, seguros, legal, marketing

## Usuarios por Defecto

Los siguientes usuarios se crean automáticamente:
- **Admin**: admin@asociacionviveros.com / password
- **Presidente**: presidente@asociacionviveros.com / password
- **Secretario**: secretario@asociacionviveros.com / password
- **Tesorero**: tesorero@asociacionviveros.com / password

## Características Implementadas

✅ Sistema de autenticación con Laravel Sanctum  
✅ Control de acceso basado en roles  
✅ Gestión completa de tareas con prioridades  
✅ Sistema de reuniones con control de asistencia  
✅ Gestión financiera con aprobaciones  
✅ Cuotas de socios automatizadas  
✅ Dashboard con estadísticas en tiempo real  
✅ API REST documentada  
✅ Validaciones y middleware de seguridad  
✅ Relaciones optimizadas entre modelos