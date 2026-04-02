# HelpDesk API / Ticket Support API

API REST profesional para gestión de tickets de soporte técnico, construida con **Laravel 13**, **PHP 8.4** y **Laravel Passport**, siguiendo una arquitectura limpia, escalable y mantenible.

---

## Tabla de contenido

- [HelpDesk API / Ticket Support API](#helpdesk-api--ticket-support-api)
  - [Tabla de contenido](#tabla-de-contenido)
  - [Descripción](#descripción)
  - [Propósito del proyecto](#propósito-del-proyecto)
  - [Por qué este proyecto](#por-qué-este-proyecto)
  - [Características principales](#características-principales)
  - [Stack tecnológico](#stack-tecnológico)
  - [Arquitectura del proyecto](#arquitectura-del-proyecto)
    - [Controllers](#controllers)
    - [Form Requests](#form-requests)
    - [Repositories](#repositories)
    - [Services](#services)
    - [Resources](#resources)
    - [Exceptions](#exceptions)
  - [Módulos implementados](#módulos-implementados)
    - [1. Auth](#1-auth)
    - [2. Users](#2-users)
    - [3. Catalogs](#3-catalogs)
    - [4. Tickets](#4-tickets)
    - [5. Ticket Messages](#5-ticket-messages)
    - [6. Ticket Attachments](#6-ticket-attachments)
    - [7. Ticket Activities](#7-ticket-activities)
  - [Entidades principales](#entidades-principales)
  - [Autenticación](#autenticación)
  - [Autorización y permisos](#autorización-y-permisos)
    - [Roles base](#roles-base)
    - [Ejemplos de permisos](#ejemplos-de-permisos)
  - [Manejo de errores y excepciones](#manejo-de-errores-y-excepciones)
    - [Ejemplo de error de validación](#ejemplo-de-error-de-validación)
    - [Casos cubiertos](#casos-cubiertos)
  - [Estructura del proyecto](#estructura-del-proyecto)
  - [Instalación](#instalación)
    - [1. Clonar el proyecto](#1-clonar-el-proyecto)
    - [2. Instalar dependencias](#2-instalar-dependencias)
    - [3. Crear archivo `.env`](#3-crear-archivo-env)
    - [4. Generar clave de aplicación](#4-generar-clave-de-aplicación)
  - [Configuración del entorno](#configuración-del-entorno)
  - [Migraciones y seeders](#migraciones-y-seeders)
  - [Creación del cliente personal de Passport](#creación-del-cliente-personal-de-passport)
  - [Cómo ejecutar el proyecto](#cómo-ejecutar-el-proyecto)
    - [Servidor local](#servidor-local)
    - [Endpoint de salud](#endpoint-de-salud)
  - [Usuario administrador por defecto](#usuario-administrador-por-defecto)
    - [Credenciales base](#credenciales-base)
  - [Colección Postman](#colección-postman)
    - [Variables principales](#variables-principales)
    - [Recomendación de uso](#recomendación-de-uso)
  - [Resumen de endpoints](#resumen-de-endpoints)
  - [Auth](#auth)
  - [Users](#users)
  - [Categories](#categories)
  - [Priorities](#priorities)
  - [Statuses](#statuses)
  - [Tickets](#tickets)
  - [Ejemplo de flujo de uso](#ejemplo-de-flujo-de-uso)
    - [1. Login](#1-login)
    - [2. Crear una categoría](#2-crear-una-categoría)
    - [3. Crear un usuario customer](#3-crear-un-usuario-customer)
    - [4. Crear un ticket](#4-crear-un-ticket)
    - [5. Listar tickets](#5-listar-tickets)
    - [6. Asignar ticket a un agente](#6-asignar-ticket-a-un-agente)
    - [7. Cambiar estado](#7-cambiar-estado)
    - [8. Agregar mensaje](#8-agregar-mensaje)
  - [Respuesta estándar de la API](#respuesta-estándar-de-la-api)
    - [Respuesta exitosa](#respuesta-exitosa)
    - [Respuesta con error](#respuesta-con-error)
  - [Pruebas](#pruebas)
  - [vendor/bin/phpunit](#vendorbinphpunit)
  - [Autor](#autor)

---

## Descripción

**HelpDesk API / Ticket Support API** es una API REST diseñada para gestionar un sistema de soporte técnico o mesa de ayuda.

Permite administrar usuarios, roles, tickets, mensajes, adjuntos, prioridades, categorías y estados, manteniendo trazabilidad de cambios importantes y aplicando reglas de negocio reales como:

- creación de tickets por clientes
- asignación de tickets a agentes
- cambio controlado de estados
- historial de actividad
- permisos por rol
- filtros y búsqueda avanzada

---

## Propósito del proyecto

Este proyecto fue construido como una base realista de portafolio para demostrar experiencia en desarrollo backend profesional con Laravel, aplicando:

- buenas prácticas de código limpio
- separación de responsabilidades
- validaciones robustas
- estructura preparada para escalar
- manejo consistente de errores
- diseño orientado a una API productiva

No es un CRUD simple.  
Está planteado como una base seria para evolucionar a un sistema real de soporte.

---

## Por qué este proyecto

La mayoría de APIs de portafolio muestran solo operaciones básicas.  
Este proyecto busca mostrar una arquitectura más madura y cercana a un entorno real, incluyendo:

- autenticación OAuth2 con Passport
- control de acceso por roles y permisos
- servicios para lógica de negocio
- repositorios para acceso a datos
- requests para validaciones
- resources para serialización consistente
- excepciones centralizadas
- trazabilidad de acciones sobre tickets

El objetivo es demostrar criterio técnico, orden estructural y capacidad para diseñar una API mantenible a mediano y largo plazo.

---

## Características principales

- API REST versionada (`/api/v1`)
- Autenticación con **Laravel Passport**
- Gestión de usuarios y roles
- Catálogos de:
  - categorías
  - prioridades
  - estados
- Creación y gestión de tickets
- Asignación de tickets a agentes
- Respuestas y notas internas
- Adjuntos asociados a tickets y mensajes
- Historial de actividad del ticket
- Filtros, búsqueda y paginación
- Soft deletes en entidades que lo requieren
- Validaciones centralizadas con Form Requests
- Uso de enums nativos de PHP
- Manejo de errores y excepciones consistente
- Preparado para crecer con Policies, tests y eventos

---

## Stack tecnológico

- **PHP 8.4**
- **Laravel 13**
- **Laravel Passport**
- **MySQL**
- **Postman** para pruebas manuales
- **PSR-4 Autoload**
- **JSON API Resources**
- **Form Requests**
- **Repositories + Services**
- **Native PHP Enums**

---

## Arquitectura del proyecto

La solución sigue una arquitectura basada en capas:

### Controllers
Responsables únicamente de la capa HTTP:
- reciben la request
- delegan validación a Form Requests
- llaman a Services o Repositories
- retornan Resources o respuestas estructuradas

### Form Requests
Centralizan la validación de entrada y autorización básica por request.

### Repositories
Encapsulan el acceso a datos:
- consultas
- filtros
- paginación
- búsquedas
- persistencia

### Services
Contienen la lógica de negocio:
- creación de tickets
- asignación de agentes
- transiciones de estado
- carga de adjuntos
- historial de actividad

### Resources
Definen la forma exacta en la que la API responde al cliente.

### Exceptions
Centralizan errores de:
- validación
- autenticación
- autorización
- reglas de negocio
- errores inesperados

---

## Módulos implementados

### 1. Auth
- login
- logout
- perfil autenticado
- actualización de perfil

### 2. Users
- listado de usuarios
- creación
- actualización
- eliminación lógica
- consulta de agentes asignables

### 3. Catalogs
- categorías
- prioridades
- estados

### 4. Tickets
- listado con filtros
- creación
- edición
- asignación
- cambio de estado
- cambio de prioridad
- cambio de categoría

### 5. Ticket Messages
- listado de mensajes por ticket
- creación de respuestas
- notas internas

### 6. Ticket Attachments
- carga de archivos en ticket o mensaje

### 7. Ticket Activities
- trazabilidad de cambios importantes

---

## Entidades principales

- `User`
- `Role`
- `Permission`
- `Category`
- `Priority`
- `Status`
- `Ticket`
- `TicketMessage`
- `TicketAttachment`
- `TicketActivity`

---

## Autenticación

La autenticación se implementa con **Laravel Passport**.

Cada usuario autenticado obtiene un token Bearer que debe enviarse en el header:

```http
Authorization: Bearer {token}
```

---

## Autorización y permisos

El proyecto incluye una base de **roles y permisos** para controlar el acceso a funcionalidades.

### Roles base
- `admin`
- `agent`
- `customer`

### Ejemplos de permisos
- `user.view.any`
- `category.create`
- `ticket.create`
- `ticket.assign`
- `ticket.change.status`
- `ticket.add.message`

---

## Manejo de errores y excepciones

La API responde de forma consistente para todos los errores.

### Ejemplo de error de validación

```json
{
  "success": false,
  "title": "Validation Failed",
  "message": "The given data was invalid.",
  "errors": {
    "email": [
      "The email field is required."
    ]
  },
  "meta": {
    "request_id": "uuid",
    "timestamp": "2026-04-01T10:40:00Z"
  }
}
```

### Casos cubiertos
- validación inválida
- no autenticado
- acción no permitida
- recurso no encontrado
- método HTTP inválido
- error de negocio
- error interno inesperado

---

## Estructura del proyecto

```bash
app
├── Enums
├── Exceptions
├── Http
│   ├── Controllers
│   ├── Requests
│   └── Resources
├── Models
├── Repositories
│   ├── Contracts
│   └── Eloquent
├── Services
├── Support
│   ├── Filters
│   └── Responses
└── Providers

database
├── factories
├── migrations
└── seeders

routes
├── api.php
└── api
    └── v1
        ├── auth.php
        ├── admin.php
        └── tickets.php
```

---

## Instalación

### 1. Clonar el proyecto

```bash
git clone https://github.com/tu-usuario/helpdesk-ticket-support-api.git
cd helpdesk-ticket-support-api
```

### 2. Instalar dependencias

```bash
composer install
```

### 3. Crear archivo `.env`

```bash
cp .env.example .env
```

### 4. Generar clave de aplicación

```bash
php artisan key:generate
```

---

## Configuración del entorno

Ejemplo mínimo de `.env`:

```env
APP_NAME="HelpDesk API"
APP_ENV=local
APP_KEY=
APP_DEBUG=true
APP_URL=http://127.0.0.1:8000

APP_LOCALE=en
APP_FALLBACK_LOCALE=en
APP_TIMEZONE=UTC

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=ticket_db
DB_USERNAME=root
DB_PASSWORD=

LOG_CHANNEL=stack
LOG_LEVEL=debug

ADMIN_DEFAULT_NAME="System Administrator"
ADMIN_DEFAULT_EMAIL="admin@helpdesk.local"
ADMIN_DEFAULT_PASSWORD="Admin12345*"
```

---

## Migraciones y seeders

Ejecuta:

```bash
php artisan migrate --seed
```

Esto crea la estructura base del sistema y carga:

- roles
- permisos
- prioridades
- estados
- usuario administrador inicial

---

## Creación del cliente personal de Passport

Después de migrar y sembrar, debes crear las llaves y el personal access client:

```bash
php artisan passport:keys
php artisan passport:client --personal --provider=users
```

> Este paso es obligatorio para que el login con `createToken()` funcione correctamente.

---

## Cómo ejecutar el proyecto

### Servidor local

```bash
php artisan serve
```

La API quedará disponible en:

```text
http://127.0.0.1:8000/api/v1
```

### Endpoint de salud

```http
GET /api/v1/health
```

---

## Usuario administrador por defecto

Después de correr los seeders, se crea un usuario administrador.

### Credenciales base

- **Email:** `admin@helpdesk.local`
- **Password:** `Admin12345*`

> Puedes cambiar estas credenciales desde el `.env`.

---

## Colección Postman

El proyecto incluye archivos listos para importar en Postman:

- `collection.postman.json`
- `environment.postman.json`

### Variables principales
- `base_url`
- `admin_email`
- `admin_password`
- `access_token`
- `current_user_uuid`
- `category_uuid`
- `priority_uuid`
- `status_uuid`
- `user_uuid`
- `assignable_agent_uuid`
- `ticket_uuid`

### Recomendación de uso
Configura la colección en Postman con:

- **Authorization Type:** `Bearer Token`
- **Token:** `{{access_token}}`

Y en cada request:
- **Inherit auth from parent**

---

## Resumen de endpoints

## Auth
- `POST /api/v1/auth/login`
- `GET /api/v1/auth/me`
- `POST /api/v1/auth/logout`
- `GET /api/v1/auth/profile`
- `PUT /api/v1/auth/profile`
- `PATCH /api/v1/auth/profile`

## Users
- `GET /api/v1/admin/users`
- `POST /api/v1/admin/users`
- `GET /api/v1/admin/users/assignable-agents`
- `GET /api/v1/admin/users/{uuid}`
- `PUT /api/v1/admin/users/{uuid}`
- `PATCH /api/v1/admin/users/{uuid}`
- `DELETE /api/v1/admin/users/{uuid}`

## Categories
- `GET /api/v1/admin/categories`
- `POST /api/v1/admin/categories`
- `GET /api/v1/admin/categories/{uuid}`
- `PUT /api/v1/admin/categories/{uuid}`
- `PATCH /api/v1/admin/categories/{uuid}`
- `DELETE /api/v1/admin/categories/{uuid}`

## Priorities
- `GET /api/v1/admin/priorities`
- `POST /api/v1/admin/priorities`
- `GET /api/v1/admin/priorities/{uuid}`
- `PUT /api/v1/admin/priorities/{uuid}`
- `PATCH /api/v1/admin/priorities/{uuid}`
- `DELETE /api/v1/admin/priorities/{uuid}`

## Statuses
- `GET /api/v1/admin/statuses`
- `POST /api/v1/admin/statuses`
- `GET /api/v1/admin/statuses/{uuid}`
- `PUT /api/v1/admin/statuses/{uuid}`
- `PATCH /api/v1/admin/statuses/{uuid}`
- `DELETE /api/v1/admin/statuses/{uuid}`

## Tickets
- `GET /api/v1/tickets`
- `POST /api/v1/tickets`
- `GET /api/v1/tickets/{uuid}`
- `PUT /api/v1/tickets/{uuid}`
- `PATCH /api/v1/tickets/{uuid}`
- `PATCH /api/v1/tickets/{uuid}/assign`
- `PATCH /api/v1/tickets/{uuid}/change-status`
- `PATCH /api/v1/tickets/{uuid}/change-priority`
- `PATCH /api/v1/tickets/{uuid}/change-category`
- `GET /api/v1/tickets/{uuid}/messages`
- `POST /api/v1/tickets/{uuid}/messages`

---

## Ejemplo de flujo de uso

### 1. Login
```http
POST /api/v1/auth/login
```

Body:

```json
{
  "email": "admin@helpdesk.local",
  "password": "Admin12345*",
  "token_name": "postman-local"
}
```

### 2. Crear una categoría
```http
POST /api/v1/admin/categories
```

### 3. Crear un usuario customer
```http
POST /api/v1/admin/users
```

### 4. Crear un ticket
```http
POST /api/v1/tickets
```

### 5. Listar tickets
```http
GET /api/v1/tickets
```

### 6. Asignar ticket a un agente
```http
PATCH /api/v1/tickets/{uuid}/assign
```

### 7. Cambiar estado
```http
PATCH /api/v1/tickets/{uuid}/change-status
```

### 8. Agregar mensaje
```http
POST /api/v1/tickets/{uuid}/messages
```

---

## Respuesta estándar de la API

### Respuesta exitosa

```json
{
  "success": true,
  "message": "Categories retrieved successfully.",
  "data": {},
  "meta": {}
}
```

### Respuesta con error

```json
{
  "success": false,
  "title": "Validation Failed",
  "message": "The given data was invalid.",
  "errors": {},
  "meta": {
    "request_id": "uuid",
    "timestamp": "2026-04-01T10:40:00Z"
  }
}
```

---

## Pruebas

Puedes ejecutar las pruebas con:

```bash
php artisan test
```

O si usas PHPUnit directamente:

```bash
vendor/bin/phpunit
---

## Autor

Proyecto desarrollado como portafolio backend para demostrar arquitectura limpia, buenas prácticas y experiencia real en Laravel API.

**Tecnologías principales:** Laravel 13, PHP 8.4, Passport, MySQL.
