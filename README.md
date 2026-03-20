# Attendance & Scheduler System

A web-based attendance management system built with Laravel 12. Designed to digitalize and streamline the process of recording, managing, and validating employee or staff attendance using QR codes and a role-based access control system.

---

## Table of Contents

- [Project Description](#project-description)
- [Features](#features)
- [Tech Stack](#tech-stack)
- [Project Architecture](#project-architecture)
- [Installation Steps](#installation-steps)
- [Usage](#usage)
- [Environment Variables](#environment-variables)
- [API Endpoints](#api-endpoints)
- [Future Improvements](#future-improvements)
- [Author](#author)

---

## Project Description

**Sistem Presensi** is a digital attendance management platform that replaces manual, paper-based attendance processes. It provides a hierarchical, multi-role system where a **Super Admin** oversees the entire platform, **Admins** manage schedules and validate attendance within their respective groups, and **Users** (employees/staff) submit their own attendance records.

The system leverages **QR codes** to simplify the attendance check-in process and includes robust tools for schedule generation, historical attendance tracking, and attendance validation workflows.

---

## Features

- **Role-Based Access Control** — Three distinct roles: Super Admin, Admin, and User, each with appropriate permissions and dashboards.
- **QR Code Attendance** — Generates scannable QR codes that direct users to the attendance submission page.
- **Schedule Management** — Admins can create, edit, delete, and auto-generate weekly schedules.
- **Person/Employee Management** — Full CRUD for managing the people (employees) under each admin's supervision.
- **Attendance Submission** — Users submit attendance with a status (Present / Absent) and an optional description.
- **Photo Upload** — Attach photos to schedules for additional verification.
- **Attendance Validation** — Admins can review and validate submitted attendance records.
- **Attendance History** — A complete historical log of attendance records per schedule, with validation status.
- **Multi-Admin Hierarchy** — Each Admin manages their own set of users, all overseen by the Super Admin.
- **Indonesian Localization** — UI messages, date formatting, and validation messages are localized in Indonesian (Bahasa Indonesia).
- **Responsive UI** — Modern, responsive interface built with Tailwind CSS v4.

---

## Tech Stack

### Backend
| Technology | Version | Purpose |
|---|---|---|
| PHP | ^8.2 | Server-side language |
| Laravel | 12.x | Web application framework |
| SQLite / MySQL | — | Database (SQLite by default) |
| simplesoftwareio/simple-qrcode | ^4.2 | QR code generation |

### Frontend
| Technology | Version | Purpose |
|---|---|---|
| Tailwind CSS | ^4.1 | Utility-first CSS framework |
| Vite | ^6.0 | Asset bundling and hot module replacement |
| Axios | ^1.7 | HTTP client |
| Laravel Blade | — | Server-side templating engine |

### Development & Tooling
| Technology | Purpose |
|---|---|
| Laravel Sail | Docker-based development environment |
| Laravel Pint | PHP code style fixer |
| PHPUnit | Automated testing |
| Laravel Pail | Real-time log viewer |
| concurrently | Run multiple dev processes simultaneously |

---

## Project Architecture

```
Sistem_presensi/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── AdminController.php       # Admin dashboard logic
│   │   │   ├── AttendanceController.php  # Attendance submission & validation
│   │   │   ├── AuthController.php        # Login / Logout
│   │   │   ├── PersonController.php      # Employee CRUD
│   │   │   ├── QrController.php          # QR code display
│   │   │   ├── ScheduleController.php    # Schedule CRUD & weekly generation
│   │   │   ├── SuperAdminController.php  # Super admin dashboard
│   │   │   └── UserController.php        # User dashboard & attendance
│   │   └── Middleware/
│   │       ├── AdminMiddleware.php
│   │       ├── SuperAdminMiddleware.php
│   │       └── UserMiddleware.php
│   └── Models/
│       ├── Attendance.php
│       ├── Person.php
│       ├── Schedule.php
│       └── User.php
├── bootstrap/
│   └── app.php                           # Application bootstrap & middleware config
├── config/                               # Laravel configuration files
├── database/
│   ├── migrations/                       # Database schema migrations
│   ├── factories/                        # Model factories for testing
│   └── seeders/                          # Database seeders
├── lang/
│   └── id/                               # Indonesian (Bahasa Indonesia) translations
├── public/
│   └── index.php                         # HTTP request entry point
├── resources/
│   ├── css/app.css                       # Tailwind CSS entry point
│   ├── js/                               # JavaScript assets
│   └── views/
│       ├── admin/                        # Admin-facing Blade views
│       ├── auth/                         # Login view
│       ├── layouts/                      # Shared layout templates
│       ├── partials/                     # Reusable UI components (navbar, etc.)
│       ├── qr/                           # QR code display view
│       ├── superadmin/                   # Super admin views
│       └── user/                         # User-facing views
├── routes/
│   └── web.php                           # All application web routes
├── tests/
│   ├── Feature/                          # Feature tests
│   └── Unit/                             # Unit tests
├── artisan                               # Laravel CLI entry point
├── composer.json                         # PHP dependencies
├── package.json                          # Node.js dependencies
└── vite.config.js                        # Vite configuration
```

### Database Schema

The application uses five main tables:

- **`users`** — Stores all accounts with a `role` column (`super_admin`, `admin`, `user`) and a self-referencing `admin_id` for the hierarchy.
- **`persons`** — Stores employee profiles linked to a user account.
- **`schedules`** — Stores attendance schedules with date, day, validation status, and an optional photo.
- **`schedule_person`** — Many-to-many join table linking persons to schedules.
- **`attendances`** — Records each attendance submission with status (`hadir`, `alpa`, `tidak_valid`), description, and validation flag.

---

## Installation Steps

### Prerequisites

- PHP >= 8.2
- Composer
- Node.js >= 18 & npm
- Git

### Steps

1. **Clone the repository**

   ```bash
   git clone https://github.com/VindSkiee/Sistem_presensi.git
   cd Sistem_presensi
   ```

2. **Install PHP dependencies**

   ```bash
   composer install
   ```

3. **Install Node.js dependencies**

   ```bash
   npm install
   ```

4. **Set up the environment file**

   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

5. **Configure your database** in `.env` (SQLite is used by default; no extra setup required):

   ```env
   DB_CONNECTION=sqlite
   ```

   Or configure MySQL:

   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=sistem_presensi
   DB_USERNAME=root
   DB_PASSWORD=your_password
   ```

6. **Run database migrations**

   ```bash
   php artisan migrate
   ```

7. **Build frontend assets**

   ```bash
   npm run build
   ```

8. **Start the development server**

   ```bash
   php artisan serve
   ```

   Or run all services concurrently (server, queue, logs, Vite HMR):

   ```bash
   npm run dev
   ```

   The application will be available at `http://localhost:8000`.

---

## Usage

After installation, access the application at `http://localhost:8000`.

### User Roles

| Role | Description | Default Path |
|---|---|---|
| **Super Admin** | Full platform oversight | `/super-admin/dashboard` |
| **Admin** | Manage schedules, persons, and validate attendance | `/admin/dashboard` |
| **User** | Submit and view personal attendance | `/user/dashboard` |

### Typical Workflow

1. A **Super Admin** or **Admin** creates user accounts with appropriate roles.
2. An **Admin** creates **Persons** (employee profiles) linked to user accounts.
3. An **Admin** creates **Schedules** (daily or auto-generated weekly).
4. A **User** logs in, scans the QR code or visits `/user/dashboard`, and submits their attendance.
5. An **Admin** reviews submissions and validates attendance via `/admin/validate-attendance`.
6. Attendance history is available at `/admin/attendances/history`.

---

## Environment Variables

Below are the key environment variables used by the application. Copy `.env.example` to `.env` and update the values accordingly.

| Variable | Description | Default |
|---|---|---|
| `APP_NAME` | Application name | `Laravel` |
| `APP_ENV` | Application environment (`local`, `production`) | `local` |
| `APP_KEY` | Application encryption key (generated by `php artisan key:generate`) | — |
| `APP_DEBUG` | Enable debug mode | `true` |
| `APP_URL` | Application base URL | `http://localhost` |
| `DB_CONNECTION` | Database driver (`sqlite`, `mysql`) | `sqlite` |
| `DB_HOST` | Database host (MySQL only) | `127.0.0.1` |
| `DB_PORT` | Database port (MySQL only) | `3306` |
| `DB_DATABASE` | Database name or SQLite file path | `database/database.sqlite` |
| `DB_USERNAME` | Database username (MySQL only) | `root` |
| `DB_PASSWORD` | Database password (MySQL only) | — |
| `SESSION_DRIVER` | Session storage driver | `database` |
| `CACHE_STORE` | Cache storage driver | `database` |
| `QUEUE_CONNECTION` | Queue driver | `database` |
| `MAIL_MAILER` | Mail driver | `log` |

---

## API Endpoints

This application uses standard web routes (no REST API). All routes are defined in `routes/web.php`.

### Public Routes

| Method | URI | Description |
|---|---|---|
| `GET` | `/` | Redirects to `/login` |
| `GET` | `/login` | Display the login form |
| `POST` | `/login` | Process login credentials |
| `POST` | `/logout` | Log the current user out |
| `GET` | `/qr` | Display the QR code for attendance |

### User Routes

> Requires authentication with `user` role.

| Method | URI | Description |
|---|---|---|
| `GET` | `/user/dashboard` | User's attendance dashboard |
| `POST` | `/user/attendance` | Submit an attendance record |
| `POST` | `/schedules/{id}/upload-photo` | Upload a photo to a schedule |

### Admin Routes

> Requires authentication with `admin` role. All URIs prefixed with `/admin`.

**Dashboard & Validation**

| Method | URI | Description |
|---|---|---|
| `GET` | `/admin/dashboard` | Admin dashboard |
| `POST` | `/admin/validate-attendance` | Validate attendance records |
| `GET` | `/admin/validate-previous` | List unvalidated past schedules |
| `PUT` | `/admin/validate-previous/{schedule}` | Update an unvalidated schedule |

**Schedule Management**

| Method | URI | Description |
|---|---|---|
| `GET` | `/admin/schedules` | List all schedules |
| `GET` | `/admin/schedules/create` | Show create schedule form |
| `POST` | `/admin/schedules` | Store a new schedule |
| `GET` | `/admin/schedules/{schedule}/edit` | Show edit schedule form |
| `PUT` | `/admin/schedules/{schedule}` | Update a schedule |
| `DELETE` | `/admin/schedules/{schedule}` | Delete a schedule |
| `GET` | `/admin/generate-weekly` | Show weekly schedule generation form |
| `POST` | `/admin/generate-weekly` | Auto-generate schedules for the week |

**Person Management**

| Method | URI | Description |
|---|---|---|
| `GET` | `/admin/persons` | List all managed persons |
| `GET` | `/admin/persons/create` | Show create person form |
| `POST` | `/admin/persons` | Store a new person |
| `GET` | `/admin/persons/{person}/edit` | Show edit person form |
| `PUT` | `/admin/persons/{person}` | Update a person |
| `DELETE` | `/admin/persons/{person}` | Delete a person |

**Attendance History**

| Method | URI | Description |
|---|---|---|
| `GET` | `/admin/attendances/history` | View attendance history |

### Super Admin Routes

> Requires authentication with `super_admin` role. All URIs prefixed with `/super-admin`.

| Method | URI | Description |
|---|---|---|
| `GET` | `/super-admin/dashboard` | Super admin overview dashboard |

---

## Future Improvements

- **REST API** — Expose a JSON API layer for integration with mobile applications.
- **Email Notifications** — Send email reminders for upcoming schedules and alerts for unvalidated attendance.
- **Reporting & Exports** — Generate attendance reports in PDF or Excel format.
- **Dashboard Analytics** — Add visual charts for attendance trends, punctuality rates, and absence statistics.
- **Late Arrival Tracking** — Record and report late check-ins against scheduled start times.
- **Two-Factor Authentication (2FA)** — Strengthen account security for all roles.
- **Multi-Tenancy Support** — Allow independent organizations to use the platform without data cross-contamination.
- **Mobile-First PWA** — Progressive Web App support for a native-like mobile experience.
- **Audit Logs** — Track all administrative actions (schedule edits, validation overrides) for accountability.

---

## Author

**VindSkiee** (Arvind Alaric)
- GitHub: [@VindSkiee](https://github.com/VindSkiee)
- Email: arvindalaric100@gmail.com
 
