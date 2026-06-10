\
# Final Folder Structure — PHP Native MVC

## Purpose

Dokumen ini mengunci struktur folder final untuk sistem konsultasi teknologi versi `v2.0`.

AI agent tidak boleh membuat struktur baru di luar pola ini tanpa instruksi eksplisit.

---

# Final Architecture Decision

Gunakan:

```text
PHP Native MVC Terintegrasi
```

View frontend berada di dalam struktur MVC. Asset frontend tetap dipisahkan pada folder `public/assets/`.

Jangan membuat dua aplikasi terpisah seperti:

```text
frontend/
backend/
```

karena proyek ini menggunakan PHP native MVC satu aplikasi.

---

# Final Project Structure

```text
v2.0/
│
├── ai-context/
│   ├── README.md
│   ├── project-overview.md
│   ├── architecture.md
│   ├── business-rules.md
│   ├── tech-stack.md
│   ├── stakeholders-and-roles.md
│   ├── services-catalog.md
│   ├── feature-scope.md
│   ├── system-flow.md
│   ├── frontend-context.md
│   ├── ui-design-guidelines.md
│   ├── backend-context.md
│   ├── backend-routes-and-api.md
│   ├── backend-clean-code-guidelines.md
│   ├── database-environment-strategy.md
│   ├── payment-gateway-midtrans.md
│   ├── payment-midtrans-routes-and-api.md
│   ├── payment-midtrans-config-and-testing.md
│   ├── payment-midtrans-ai-agent-rules.md
│   ├── payment-midtrans-database-fields.md
│   ├── admin-consultation-pipeline.md
│   ├── final-folder-structure.md
│   ├── final-database-schema.md
│   ├── authentication-and-authorization.md
│   ├── chat-consultation-spec.md
│   ├── page-and-route-mapping.md
│   ├── implementation-roadmap.md
│   ├── testing-checklist.md
│   └── ai-agent-master-instructions.md
│
├── app/
│   ├── Controllers/
│   │   ├── AuthController.php
│   │   ├── PublicController.php
│   │   ├── ServiceController.php
│   │   ├── UserDashboardController.php
│   │   ├── ConsultationController.php
│   │   ├── ChatController.php
│   │   ├── PaymentController.php
│   │   ├── MidtransWebhookController.php
│   │   ├── AdminDashboardController.php
│   │   ├── AdminPipelineController.php
│   │   ├── AdminSubServiceController.php
│   │   ├── AdminFinanceController.php
│   │   ├── SuperadminDashboardController.php
│   │   ├── SuperadminUserApprovalController.php
│   │   ├── SuperadminAdminManagementController.php
│   │   ├── SuperadminServiceController.php
│   │   └── SuperadminFinanceController.php
│   │
│   ├── Models/
│   │   ├── User.php
│   │   ├── ServiceCategory.php
│   │   ├── SubService.php
│   │   ├── AdminServiceAssignment.php
│   │   ├── Consultation.php
│   │   ├── Payment.php
│   │   └── Message.php
│   │
│   ├── Services/
│   │   ├── AuthService.php
│   │   ├── ApprovalService.php
│   │   ├── AdminAssignmentService.php
│   │   ├── ConsultationService.php
│   │   ├── PaymentService.php
│   │   ├── MidtransService.php
│   │   └── AdminPipelineService.php
│   │
│   ├── Middleware/
│   │   ├── AuthMiddleware.php
│   │   ├── GuestMiddleware.php
│   │   ├── ApprovedUserMiddleware.php
│   │   ├── AdminMiddleware.php
│   │   ├── SuperadminMiddleware.php
│   │   └── CsrfMiddleware.php
│   │
│   ├── Core/
│   │   ├── App.php
│   │   ├── Router.php
│   │   ├── Controller.php
│   │   ├── Database.php
│   │   ├── Session.php
│   │   ├── Validator.php
│   │   ├── Response.php
│   │   └── Env.php
│   │
│   ├── Helpers/
│   │   ├── auth_helper.php
│   │   ├── csrf_helper.php
│   │   ├── url_helper.php
│   │   ├── view_helper.php
│   │   └── flash_helper.php
│   │
│   └── Views/
│       ├── layouts/
│       │   ├── public-header.php
│       │   ├── public-footer.php
│       │   ├── dashboard-sidebar.php
│       │   ├── dashboard-topbar.php
│       │   └── dashboard-layout.php
│       │
│       ├── public/
│       │   ├── home.php
│       │   ├── services.php
│       │   ├── service-detail.php
│       │   ├── sub-service-detail.php
│       │   ├── pricing.php
│       │   └── consultants.php
│       │
│       ├── auth/
│       │   ├── login.php
│       │   └── register.php
│       │
│       ├── user/
│       │   ├── dashboard.php
│       │   ├── consultations.php
│       │   ├── consultation-detail.php
│       │   ├── payment.php
│       │   └── chat.php
│       │
│       ├── admin/
│       │   ├── dashboard.php
│       │   ├── pipeline.php
│       │   ├── consultation-detail.php
│       │   ├── chat.php
│       │   ├── sub-services.php
│       │   ├── sub-service-form.php
│       │   └── finance.php
│       │
│       └── superadmin/
│           ├── dashboard.php
│           ├── user-approval.php
│           ├── admins.php
│           ├── admin-form.php
│           ├── admin-assignments.php
│           ├── services.php
│           ├── service-form.php
│           ├── sub-services.php
│           ├── consultations.php
│           └── finance.php
│
├── config/
│   ├── app.php
│   ├── database.php
│   └── payment.php
│
├── database/
│   ├── schema.sql
│   ├── seed.sql
│   ├── README.md
│   └── backups/
│       └── .gitkeep
│
├── public/
│   ├── index.php
│   ├── .htaccess
│   └── assets/
│       ├── css/
│       │   ├── main.css
│       │   ├── theme.css
│       │   ├── landing.css
│       │   ├── dashboard.css
│       │   ├── forms.css
│       │   └── chat.css
│       │
│       ├── js/
│       │   ├── sidebar.js
│       │   ├── modal.js
│       │   ├── chat-polling.js
│       │   └── payment.js
│       │
│       └── images/
│
├── routes/
│   ├── web.php
│   ├── auth.php
│   ├── user.php
│   ├── admin.php
│   ├── superadmin.php
│   └── payment.php
│
├── storage/
│   └── logs/
│       └── .gitkeep
│
├── vendor/
│
├── .env
├── .env.example
├── .gitignore
├── composer.json
├── composer.lock
└── README.md
```

---

# Folder Responsibility Rules

## `app/Controllers`

Hanya menangani request, validasi dasar, pemanggilan service/model, redirect, dan response.

## `app/Models`

Hanya menangani query database dengan prepared statement.

## `app/Services`

Menangani business logic multi-langkah.

## `app/Middleware`

Menangani autentikasi, role, approval, dan CSRF.

## `app/Core`

Menangani fondasi framework mini PHP native.

## `app/Views`

Hanya menampilkan data. Tidak boleh query database langsung.

## `config`

Semua konfigurasi aplikasi, database, dan payment gateway.

## `database`

Schema SQL, seed, dan backup lokal.

## `public`

Satu-satunya folder yang diekspos web server.

## `routes`

Seluruh route aplikasi. Tidak boleh membuat route langsung di controller atau view.

---

# Final Rule

AI agent tidak boleh:

1. Membuat folder frontend terpisah.
2. Menaruh query di view.
3. Menaruh route di `public/index.php`.
4. Menaruh credential di source code.
5. Membuat struktur alternatif tanpa memperbarui dokumentasi ini.
