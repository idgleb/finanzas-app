# Finanzas App 💸
 
**🌐 Una aplicación Laravel para gestionar finanzas con pagos PRO vía tarjeta usando Mercado Pago.**

## ✨ Descripción

**Finanzas App** es una aplicación web Laravel que permite gestionar ingresos, gastos y categorías personalizadas. Los usuarios PRO desbloquean funciones avanzadas pagando con tarjeta a través de Mercado Pago, con un webhook para confirmar pagos. Incluye un panel admin para estadísticas. Usa ngrok para exponer la app localmente con HTTPS, compatible con Mercado Pago.

![Captura de pantalla 2025-06-02 163857](https://github.com/user-attachments/assets/1aef0c5e-0654-46e1-9031-536a2bb1678c)


---

## 🚀 Características

- 💰 **Movimientos**: Registra ingresos y gastos.
- 🛠️ **Categorías personalizadas**: Crea/editar categorías (solo PRO).
- 💳 **Pagos con tarjeta**: Suscríbete al plan PRO con Mercado Pago.
- 📬 **Webhook**: Verifica y procesa pagos aprobados.
- 📊 **Panel admin**: Estadísticas de usuarios y movimientos.
- 🔒 **Seguridad**: Roles de usuario/admin y CSRF.

---

## 🛠️ Requisitos

- 🐳 Docker (Laravel Sail)
- 🐘 PHP 8.1+
- 🗄️ MySQL 8.0
- 🌐 Composer
- 📦 Node.js (Vite)
- 🔑 Token de Mercado Pago (en `.env`)
- 🌍 Ngrok (para URL pública HTTPS)

---

## 📚 Diagrama de entidad-relacion (ER)
![er-diagrama-finansas](https://github.com/user-attachments/assets/233edea6-3e45-46d9-81e4-9236913bc0a1)

**Relaciones principales**:
- Un usuario tiene muchos movimientos y categorías.
- Un movimiento pertenece a un usuario y una categoría.
- Un usuario (admin) crea muchas noticias.
- Un usuario tiene un plan.

---

## ⚙️ Instalación

1. **Clona el repositorio** 📥:
   ```bash
   git clone https://github.com/idgleb/finanzas-app.git
   cd finanzas-app
   ```

2. **Configura el entorno** 🔑:
    - Copia `.env.example` a `.env`:
      ```env
      DB_CONNECTION=mysql
      DB_HOST=mysql
      DB_PORT=3306
      DB_DATABASE=laravel
      DB_USERNAME=sail
      DB_PASSWORD=password
      MERCADO_PAGO_ACCESS_TOKEN=APP_USR-...
      ```

3. **Inicia Docker con Sail** 🐳:
   ```bash
   ./vendor/bin/sail up -d
   ```

4. **Instala dependencias** 📦:
   ```bash
   sail composer install
   sail npm install
   ```

5. **Migra y siembra la base de datos** 🗃️:
   ```bash
   sail artisan migrate --seed
   ```

6. **Configura ngrok** 🌍:
    - Inicia ngrok para obtener una URL pública HTTPS:
      ```bash
      ./start-ngrok.sh
      ```
    - Actualiza las URLs de retorno y webhook en Mercado Pago con la URL de ngrok (ej. `https://abc123.ngrok.io/payments/webhook`).

7. **Inicia Vite** ⚡:
   ```bash
   sail npm run dev
   ```

8. **Accede a la app** 🌐:
    - Usa la URL de ngrok (ej. `https://abc123.ngrok.io`).

---

## 📖 Uso

1. **Inicia sesión** 👤:
    - Usa `admin@example.com` (admin) o `juan@example.com` (usuario).

2. **Gestiona movimientos** 💵:
    - Añade ingresos/gastos en `/movements`.

3. **Crea categorías** 🛠️:
    - Usuarios PRO gestionan categorías en `/categories`.

4. **Suscríbete al plan PRO** 💳:
    - Paga con tarjeta en `/payments`. El webhook confirma el pago.

5. **Panel admin** 📊:
    - Admins ven estadísticas en `/admin/dashboard`.

---

## 🧑‍💻 Tecnologías

- 🐘 PHP 8.1+ / Laravel 10
- 🐳 Laravel Sail (Docker)
- ⚡ Vite (frontend)
- 🗄️ MySQL 8.0
- 💳 Mercado Pago SDK (pagos con tarjeta y webhook)
- 🌍 Ngrok (URL pública HTTPS)

---

## 🤝 Contribuir

1. Fork 🍴.
2. Crea rama (`git checkout -b feature/nueva-funcionalidad`) 🌿.
3. Commitea (`git commit -m "Añadir funcionalidad"`) ✅.
4. Push (`git push origin feature/nueva-funcionalidad`) 🚀.
5. Abre Pull Request 📬.

---

## 📜 Licencia

**[MIT](LICENSE)** 📝.

---

## 📬 Contacto

- 🐞 Issues: [GitHub](https://github.com/idgleb/finanzas-app/issues)
- ✉️ Email: [argentinagleb73@gmail.com](mailto:argentinagleb73@gmail.com)
- 🔗 LinkedIn: [Gleb Ursol](https://www.linkedin.com/in/gleb-ursol-855725326/)

---

🌟 **¡Organiza tus finanzas con Finanzas App!** 🌟



