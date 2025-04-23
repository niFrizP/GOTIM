<p align="center"><a href="https://github.com/niFrizP/GOTIM" target="_blank"><img src="https://raw.githubusercontent.com/niFrizP/GOTIM/refs/heads/main/public/images/logo.png" width="400" alt="GOTIM Logo"></a></p>

**Gestión de Órdenes de Trabajo para la Mecánica Industrial**


![License: MIT](https://img.shields.io/badge/License-MIT-blue.svg)
![Laravel 11](https://img.shields.io/badge/Laravel-11-red.svg)
![PHP 8.2](https://img.shields.io/badge/PHP-8.2-777BB4.svg)


---

## 📖 Descripción
GOTIM es una aplicación web diseñada para gestionar órdenes de trabajo en el área de mecánica industrial. Ofrece un flujo completo para:

- Crear y asignar órdenes de trabajo.
- Registrar tareas, repuestos y tiempos de servicio.
- Llevar el historial de clientes y equipos.
- Controlar inventarios de productos y servicios.
- Generar reportes y métricas de productividad.
- Sistema de autenticación y registro de usuarios.
- Gestión de roles: **Admin**, **Supervisor** y **Técnico**.
- Módulo de gestión de clientes.
- Gestión de inventario de productos.
- Registro y seguimiento de órdenes de trabajo.
- Historial detallado por cliente y equipo.
- Generación de reportes PDF/Excel.

---

## 🚀 Características

| Módulo                 | Estado       |
| ---------------------- | ------------ |
| Autenticación          | ✅ Completado |
| Gestión de Roles       | ✅ Completado |
| Gestión de Clientes    | ✅ Completado |
| Gestión de Inventario  | 🔄 En desarrollo |
| Gestión de Categorias    | 🔄 En desarrollo |
| Gestión de Servicios    | 🔄 En desarrollo |
| Órdenes de Trabajo     | ⏳ Pendiente |
| Historial de Servicios | ⏳ Pendiente |
| Reportes               | ⏳ Pendiente |

---

## 🛠️ Tecnologías

- **Backend:** Laravel 11 (PHP 8.2)
- **Base de datos:** MySQL
- **Frontend:** Blade, Tailwind CSS, Alpine.js
- **Control de versiones:** Git & GitHub
- **Despliegue:** Local o CPanel / Hosting compartido

---

## 📦 Instalación

1. Clona el repositorio:
   ```bash
   git clone https://github.com/niFrizP/GOTIM.git
   cd GOTIM
   ```
2. Instala las dependencias de PHP:
   ```bash
   composer install
   ```
3. Instala las dependencias de Node.js:
   ```bash
   npm install
   npm run dev
   ```
4. Copia el archivo de entorno y configura tus credenciales:
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```
5. Configura la conexión a la base de datos en `.env`:
   ```dotenv
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=gotim
   DB_USERNAME=tu_usuario
   DB_PASSWORD=tu_contraseña
   ```
6. Ejecuta migraciones y seeders:
   ```bash
   php artisan migrate --seed
   ```
7. Inicia el servidor de desarrollo:
   ```bash
   php artisan serve
   ```

Accede a `http://127.0.0.1:8000` en tu navegador.

---

## ⚙️ Uso

1. Regístrate o accede con una cuenta existente.
2. Según tu rol verás distintos menús:
   - **Admin:** Control total de la plataforma.
   - **Supervisor:** Gestión y seguimiento de órdenes.
   - **Técnico:** Registro de servicios realizados.
   - **Cliente:** Visualización de sus órdenes.
3. Navega al módulo de Clientes para agregar o editar datos.
4. Pronto encontrarás nuevos módulos en el menú principal.

---


_¡Gracias por usar GOTIM!_
