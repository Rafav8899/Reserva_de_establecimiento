# Sistema de Gestión de Reservas - Estudio de Fotografía

Este es un sistema de gestión de reservas desarrollado en **PHP** con integración de **Google Auth API**. Permite a los clientes reservar turnos de manera automática y a los administradores gestionar las reservas desde un panel protegido.

## 🚀 Funcionalidades
- **Login Multimodal:** Acceso con Google (OAuth2) o mediante DNI (para clientes registrados).
- **Panel de Administración:** Visualización y cancelación de reservas en tiempo real.
- **Seguridad:** Protección de rutas mediante sesiones de PHP y validación de roles (Admin/Cliente).
- **Base de Datos:** Estructura relacional en MySQL para clientes, usuarios y reservas.

## 🛠️ Tecnologías utilizadas
- PHP (Lógica de servidor y sesiones)
- MySQL (Base de datos)
- JavaScript / AJAX (Interacciones sin recarga de página)
- Google API PHP Client
- CSS3 (Diseño personalizado)