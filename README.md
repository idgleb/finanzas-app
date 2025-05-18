# 🚀 Finanzas App

Una aplicación web moderna para la gestión de finanzas personales, construida con Laravel y Vue.js.

## 🛠️ Tecnologías Utilizadas

- **Backend:**
  - PHP 8.2+
  - Laravel 12
  - MercadoPago SDK
  - SQLite (desarrollo)

- **Frontend:**
  - Vue.js
  - TailwindCSS
  - Alpine.js
  - Vite

## 📋 Requisitos Previos

- PHP 8.2 o superior
- Composer
- Node.js y npm
- Git

## 🚀 Instalación

1. Clona el repositorio:
```bash
git clone https://github.com/idgleb/finanzas-app.git
cd finanzas-app
```

2. Instala las dependencias de PHP:
```bash
composer install
```

3. Instala las dependencias de Node:
```bash
npm install
```

4. Configura el entorno:
```bash
cp .env.example .env
php artisan key:generate
```

5. Configura la base de datos:
```bash
php artisan migrate
```

6. Inicia el servidor de desarrollo:
```bash
composer dev
```

## 🎯 Características Principales

- Gestión de ingresos y gastos
- Integración con MercadoPago
- Dashboard interactivo
- Autenticación de usuarios
- Interfaz responsiva

## 🧪 Testing

Para ejecutar las pruebas:
```bash
composer test
```

## 📝 Licencia

Este proyecto está bajo la Licencia MIT. Ver el archivo `LICENSE` para más detalles.

## 👥 Contribución

Las contribuciones son bienvenidas. Por favor, lee las guías de contribución antes de enviar un pull request.

## 📧 Contacto

[@idgleb](https://github.com/idgleb)

## 📊 Estadísticas del Proyecto

- PHP: 62.7%
- Blade: 35.9%
- Otros: 1.4%
