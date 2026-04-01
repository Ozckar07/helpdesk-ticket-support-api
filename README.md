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
