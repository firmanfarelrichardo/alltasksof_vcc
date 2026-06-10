\
# Page and Route Mapping

## Purpose

Dokumen ini memetakan halaman, route, controller, dan hak akses.

---

# Public Pages

| Page | Method | URI | Controller | Action | Access |
|---|---|---|---|---|---|
| Landing Page | GET | `/` | `PublicController` | `home()` | Public |
| Services | GET | `/services` | `ServiceController` | `index()` | Public |
| Service Detail | GET | `/services/{serviceId}` | `ServiceController` | `showCategory()` | Public |
| Sub Service Detail | GET | `/sub-services/{subServiceId}` | `ServiceController` | `showSubService()` | Public |
| Pricing | GET | `/pricing` | `ServiceController` | `pricing()` | Public |
| Consultants | GET | `/consultants` | `PublicController` | `consultants()` | Public |

---

# Authentication Pages

| Page | Method | URI | Controller | Action | Access |
|---|---|---|---|---|---|
| Register Form | GET | `/register` | `AuthController` | `showRegister()` | Guest |
| Register Process | POST | `/register` | `AuthController` | `register()` | Guest |
| Login Form | GET | `/login` | `AuthController` | `showLogin()` | Guest |
| Login Process | POST | `/login` | `AuthController` | `login()` | Guest |
| Logout | POST | `/logout` | `AuthController` | `logout()` | Auth |

---

# User Pages

| Page | Method | URI | Controller | Action | Access |
|---|---|---|---|---|---|
| Dashboard | GET | `/user/dashboard` | `UserDashboardController` | `index()` | Approved User |
| Consultation History | GET | `/user/consultations` | `ConsultationController` | `userHistory()` | Approved User |
| Consultation Detail | GET | `/user/consultations/{id}` | `ConsultationController` | `showForUser()` | Owner |
| Create Consultation | POST | `/user/consultations` | `ConsultationController` | `create()` | Approved User |
| Payment Page | GET | `/user/consultations/{id}/payment` | `PaymentController` | `show()` | Owner |
| Create Payment | POST | `/user/consultations/{id}/payment` | `PaymentController` | `create()` | Owner |
| Payment Status | GET | `/user/payments/{id}` | `PaymentController` | `showStatus()` | Owner |
| Payment Status API | GET | `/api/user/payments/{id}/status` | `PaymentController` | `statusApi()` | Owner |
| Refresh Payment | POST | `/user/payments/{id}/refresh-status` | `PaymentController` | `refreshStatus()` | Owner |
| Chat Page | GET | `/user/consultations/{id}/chat` | `ChatController` | `showForUser()` | Owner + Active |

---

# User Chat API

| Method | URI | Controller | Action | Access |
|---|---|---|---|---|
| GET | `/api/user/consultations/{id}/messages` | `ChatController` | `messagesForUser()` | Owner + Active |
| POST | `/api/user/consultations/{id}/messages` | `ChatController` | `sendByUserApi()` | Owner + Active |

---

# Admin Pages

| Page | Method | URI | Controller | Action | Access |
|---|---|---|---|---|---|
| Dashboard | GET | `/admin/dashboard` | `AdminDashboardController` | `index()` | Admin |
| Pipeline | GET | `/admin/pipeline` | `AdminPipelineController` | `index()` | Admin |
| Pipeline Registered | GET | `/admin/pipeline/registered` | `AdminPipelineController` | `registeredUsers()` | Admin Summary |
| Payment Pending | GET | `/admin/pipeline/payments/pending` | `AdminPipelineController` | `pendingPayments()` | Assigned Admin |
| Payment Cancelled | GET | `/admin/pipeline/payments/cancelled` | `AdminPipelineController` | `cancelledPayments()` | Assigned Admin |
| Payment Success | GET | `/admin/pipeline/payments/success` | `AdminPipelineController` | `successfulPayments()` | Assigned Admin |
| Active Consultations | GET | `/admin/pipeline/consultations/active` | `AdminPipelineController` | `activeConsultations()` | Assigned Admin |
| Closed Consultations | GET | `/admin/pipeline/consultations/closed` | `AdminPipelineController` | `closedConsultations()` | Assigned Admin |
| Consultation Detail | GET | `/admin/consultations/{id}` | `ConsultationController` | `showForAdmin()` | Assigned Admin |
| Close Consultation | PATCH | `/admin/consultations/{id}/close` | `ConsultationController` | `closeForAdmin()` | Assigned Admin |
| Chat Page | GET | `/admin/consultations/{id}/chat` | `ChatController` | `showForAdmin()` | Assigned Admin + Active |
| Sub Services | GET | `/admin/sub-services` | `AdminSubServiceController` | `index()` | Assigned Admin |
| Add Sub Service | GET | `/admin/sub-services/create` | `AdminSubServiceController` | `create()` | Assigned Admin |
| Store Sub Service | POST | `/admin/sub-services` | `AdminSubServiceController` | `store()` | Assigned Admin |
| Edit Sub Service | GET | `/admin/sub-services/{id}/edit` | `AdminSubServiceController` | `edit()` | Assigned Admin |
| Update Sub Service | PATCH | `/admin/sub-services/{id}` | `AdminSubServiceController` | `update()` | Assigned Admin |
| Delete Sub Service | DELETE | `/admin/sub-services/{id}` | `AdminSubServiceController` | `destroy()` | Assigned Admin |
| Finance | GET | `/admin/finance` | `AdminFinanceController` | `index()` | Assigned Admin |

---

# Admin Chat API

| Method | URI | Controller | Action | Access |
|---|---|---|---|---|
| GET | `/api/admin/consultations/{id}/messages` | `ChatController` | `messagesForAdmin()` | Assigned Admin + Active |
| POST | `/api/admin/consultations/{id}/messages` | `ChatController` | `sendByAdminApi()` | Assigned Admin + Active |

---

# Superadmin Pages

| Page | Method | URI | Controller | Action | Access |
|---|---|---|---|---|---|
| Dashboard | GET | `/superadmin/dashboard` | `SuperadminDashboardController` | `index()` | Superadmin |
| Pending Users | GET | `/superadmin/users/pending` | `SuperadminUserApprovalController` | `pending()` | Superadmin |
| Approve User | PATCH | `/superadmin/users/{id}/approve` | `SuperadminUserApprovalController` | `approve()` | Superadmin |
| Reject User | PATCH | `/superadmin/users/{id}/reject` | `SuperadminUserApprovalController` | `reject()` | Superadmin |
| Admin List | GET | `/superadmin/admins` | `SuperadminAdminManagementController` | `index()` | Superadmin |
| Add Admin | GET | `/superadmin/admins/create` | `SuperadminAdminManagementController` | `create()` | Superadmin |
| Store Admin | POST | `/superadmin/admins` | `SuperadminAdminManagementController` | `store()` | Superadmin |
| Edit Admin | GET | `/superadmin/admins/{id}/edit` | `SuperadminAdminManagementController` | `edit()` | Superadmin |
| Update Admin | PATCH | `/superadmin/admins/{id}` | `SuperadminAdminManagementController` | `update()` | Superadmin |
| Disable Admin | DELETE | `/superadmin/admins/{id}` | `SuperadminAdminManagementController` | `destroy()` | Superadmin |
| Admin Assignment | GET | `/superadmin/admins/{id}/assignments` | `SuperadminAdminManagementController` | `assignments()` | Superadmin |
| Store Assignment | POST | `/superadmin/admins/{id}/assignments` | `SuperadminAdminManagementController` | `storeAssignment()` | Superadmin |
| Delete Assignment | DELETE | `/superadmin/admins/{id}/assignments/{assignmentId}` | `SuperadminAdminManagementController` | `destroyAssignment()` | Superadmin |
| Services | GET | `/superadmin/services` | `SuperadminServiceController` | `index()` | Superadmin |
| Create Service | GET | `/superadmin/services/create` | `SuperadminServiceController` | `create()` | Superadmin |
| Store Service | POST | `/superadmin/services` | `SuperadminServiceController` | `store()` | Superadmin |
| Edit Service | GET | `/superadmin/services/{id}/edit` | `SuperadminServiceController` | `edit()` | Superadmin |
| Update Service | PATCH | `/superadmin/services/{id}` | `SuperadminServiceController` | `update()` | Superadmin |
| Disable Service | DELETE | `/superadmin/services/{id}` | `SuperadminServiceController` | `destroy()` | Superadmin |
| Sub Services | GET | `/superadmin/sub-services` | `SuperadminServiceController` | `subServices()` | Superadmin |
| Create Sub Service | GET | `/superadmin/sub-services/create` | `SuperadminServiceController` | `createSubService()` | Superadmin |
| Store Sub Service | POST | `/superadmin/sub-services` | `SuperadminServiceController` | `storeSubService()` | Superadmin |
| Edit Sub Service | GET | `/superadmin/sub-services/{id}/edit` | `SuperadminServiceController` | `editSubService()` | Superadmin |
| Update Sub Service | PATCH | `/superadmin/sub-services/{id}` | `SuperadminServiceController` | `updateSubService()` | Superadmin |
| Disable Sub Service | DELETE | `/superadmin/sub-services/{id}` | `SuperadminServiceController` | `destroySubService()` | Superadmin |
| Consultations | GET | `/superadmin/consultations` | `ConsultationController` | `superadminIndex()` | Superadmin |
| Finance | GET | `/superadmin/finance` | `SuperadminFinanceController` | `index()` | Superadmin |

---

# Midtrans Routes

| Method | URI | Controller | Action | Access |
|---|---|---|---|---|
| POST | `/payments/midtrans/notification` | `MidtransWebhookController` | `notification()` | Signature Verification |
| GET | `/payments/midtrans/finish` | `PaymentController` | `finish()` | Public Redirect |
| GET | `/payments/midtrans/unfinish` | `PaymentController` | `unfinish()` | Public Redirect |
| GET | `/payments/midtrans/error` | `PaymentController` | `error()` | Public Redirect |

---

# Routing Rules

1. Route didefinisikan di folder `routes/`.
2. Jangan membuat route langsung di controller.
3. Jangan membuat route langsung di view.
4. Gunakan resource-oriented URI.
5. Gunakan middleware sesuai akses.
6. Dokumentasikan setiap route baru di file ini.
