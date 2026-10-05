# Laravel 30-Day Learning Plan
## Tour & Guide Operations Manager — AI-Native, Security-First, AWS Production Deployment

> **Goal akhir:** dalam 30 hari, membangun dan memahami sebuah **Tour & Guide Operations Manager** menggunakan Laravel, MySQL, Blade, Tailwind/Flowbite, Docker, automated tests, security baseline, observability, dan deployment ke AWS.
>
> Codex dipakai sebagai **coding agent**, bukan sebagai tombol "buat aplikasi jadi". Setiap perubahan besar harus dipecah menjadi eksperimen kecil yang bisa kamu inspect, test, debug, dan jelaskan kembali.

---

# 0. Apa yang Kita Bangun?

Aplikasi ini bukan POS dan bukan inventory barang generik.

Ini adalah aplikasi operasional untuk:

- private guide;
- tour operator kecil;
- activity provider;
- excursion operator;
- travel planner kecil;
- bisnis yang perlu mengelola tour, departure, guide, guest, dan kapasitas.

Nama sementara:

```text
TourFlow
```

Core domain:

```text
Tour
  │
  ├── has many Departures
  │
  └── punya price, duration, capacity default
             │
             ▼
        Departure
        ├── date/time
        ├── capacity
        ├── assigned Guide
        └── has many Bookings
                    │
                    ▼
                  Guest
```

Fitur akhir yang ditargetkan:

```text
Authentication
Dashboard

Tours
├── list
├── detail
├── create
├── edit
└── archive / deactivate

Guides
├── list
├── create
├── edit
└── availability status

Departures
├── schedule
├── assign guide
├── capacity
└── conflict protection

Bookings
├── create
├── guest count
├── status
└── capacity validation

Security
├── authentication
├── authorization
├── validation
├── CSRF protection
├── XSS-safe Blade output
├── rate limiting where relevant
├── secret hygiene
├── secure production config
└── dependency / configuration checks

Production
├── Docker image
├── AWS ECR
├── ECS Fargate
├── Application Load Balancer
├── RDS MySQL
├── AWS Secrets Manager
├── HTTPS
├── CloudWatch logs/metrics
├── backup / restore strategy
└── deployment + rollback runbook
```

---

# 1. Apa Arti "Production Ready" di Roadmap Ini?

Untuk project belajar, "production ready" **bukan berarti siap untuk semua perusahaan, semua traffic, semua compliance, atau zero-downtime global scale**.

Target kita adalah **production-ready baseline untuk MVP bisnis kecil**:

```text
[ ] requirement utama bekerja
[ ] auth dan authorization bekerja
[ ] input divalidasi
[ ] secrets tidak berada di Git
[ ] APP_DEBUG=false di production
[ ] HTTPS
[ ] database tidak public
[ ] least-privilege network/IAM baseline
[ ] automated tests untuk flow kritis
[ ] Docker image reproducible
[ ] health check
[ ] centralized logs
[ ] monitoring dasar
[ ] database backup
[ ] restore pernah diuji
[ ] migration punya prosedur aman
[ ] deploy terdokumentasi
[ ] rollback terdokumentasi
[ ] dependency/security review dilakukan
```

Aplikasi untuk data sensitif berat, pembayaran, healthcare, enterprise multi-tenant, atau compliance khusus membutuhkan hardening tambahan di luar 30 hari.

---

# 2. Pola Belajar Utama

Setiap hari gunakan:

```text
UNDERSTAND
    ↓
SPECIFY
    ↓
PLAN
    ↓
DELEGATE ONE SMALL STEP
    ↓
INSPECT DIFF
    ↓
TEST
    ↓
DEBUG
    ↓
EXPLAIN
    ↓
IMPROVE
```

Setiap perubahan penting harus bisa kamu jawab:

### WHAT
Apa yang berubah?

### WHY
Masalah apa yang diselesaikan?

### FLOW
Bagaimana request/data mengalir?

### RISK
Apa yang bisa gagal atau disalahgunakan?

### VERIFY
Evidence apa yang membuktikan perubahan benar?

---

# 3. Aturan Codex Selama 30 Hari

JANGAN:

```text
"Buat seluruh fitur booking."
"Buat CRUD lengkap."
"Buat auth + role + security."
"Deploy semua ke AWS."
```

Gunakan pola kecil:

```text
Prompt 1
Inspect. Jangan ubah file.

Prompt 2
Buat plan minimal. Jangan implement.

Prompt 3
Implement hanya satu step.

Prompt 4
Run verification.

Prompt 5
Review diff dan jelaskan.
```

Jika error:

JANGAN:

```text
"Fix error ini."
```

Gunakan:

```text
Baca error.
Jangan ubah kode.

Identifikasi:
1. symptom
2. layer
3. maksimal 3 hypothesis
4. evidence yang perlu dikumpulkan
```

Setelah evidence cukup:

```text
Tentukan root cause.
Jangan fix dulu.
```

Baru:

```text
Implement fix terkecil untuk root cause tersebut.
```

---

# 4. Security Rules dari Hari Pertama

Selalu berlaku:

```text
.env tidak boleh di-commit
API key/token/password tidak boleh di source code
APP_DEBUG=false di production
input dari client tidak dipercaya
Blade escaped output dipakai untuk untrusted data
POST/PATCH/DELETE web forms memakai CSRF protection
authorization harus dicek server-side
password memakai mekanisme hashing Laravel
query harus memakai Eloquent/query binding dengan benar
database production tidak diekspos ke public internet
AWS IAM menggunakan least privilege
production secrets memakai secrets store
```

---

# WEEK 1
# FOUNDATIONS — ENVIRONMENT, HTTP, ROUTING, BLADE, CONTROLLER

---

# Day 1 — Local Environment & Terminal Mental Model

## Goal

Memahami tool yang akan menjalankan project sebelum membuat Laravel app.

## Understand

Mental model:

```text
Windows
├── PHP       → PHP runtime
├── Composer  → PHP dependency manager
├── Node      → JS runtime untuk tooling
├── npm       → frontend dependency manager
├── MySQL     → database server
├── Git       → version control
└── Codex     → coding agent
```

Pahami juga:

```text
current directory
PATH
executable
filesystem
```

## Manual

```powershell
pwd
ls

php --version
where.exe php

composer --version
where.exe composer

mysql --version
where.exe mysql

node --version
where.exe node

npm --version
where.exe npm

git --version
where.exe git
```

## Codex — Inspect Only

```text
Inspect local development environment.

Jangan install atau modify apa pun.

Check:
PHP
Composer
MySQL
Node
npm
Git

Tampilkan version dan executable path.
Bedakan blocker dengan warning.
```

## Verify

Kamu harus bisa menjawab:

- apa bedanya software tidak terinstall vs tidak ada di PATH?
- executable PHP yang sebenarnya dipakai ada di mana?
- Composer dan npm menangani dependency jenis apa?
- apa fungsi `pwd`, `ls`, dan `cd`?

## Deliverable

Catatan environment lokal.

---

# Day 2 — Laravel Project + HTTP Request Lifecycle

## Goal

Membuat Laravel app minimal dan memahami satu request sampai Blade.

## Understand

```text
Browser
↓
HTTP Request
↓
public/index.php
↓
Laravel boots
↓
Router
↓
Route handler
↓
Blade
↓
HTML
↓
Browser
```

Kenali:

```text
composer.json
composer.lock
vendor/
.env
.env.example
artisan
routes/
resources/views/
public/
storage/
```

## Codex Step 1 — Inspect

```text
Check apakah environment siap untuk Laravel stable.

Jangan install apa pun.
Jangan modify system.

Return blocker jika ada.
```

## Codex Step 2 — Create Project

```text
Create Laravel project di (root repo ini, jangan bikin folder baru):

D:\belajar-pemograman

Keep it minimal.

Jangan tambah:
auth
database customization
Tailwind customization
Flowbite
Docker
CRUD

Setelah selesai:
php artisan about
show root structure
show git status

Jangan commit.
```

## Codex Step 3 — First Request

```text
Create GET /day-two.

Route:
routes/web.php

Response:
Blade view dengan heading "Laravel Day Two".

Jangan buat controller.

Setelah selesai:
php artisan route:list
show git diff
```

## Debug Exercise

Sengaja ubah nama view menjadi nama yang tidak ada.

Pisahkan:

```text
symptom
layer
hypothesis
root cause
```

Lalu kembalikan.

## Verify

```powershell
php artisan about
php artisan route:list
git status
git diff
```

## Explain Back

Jelaskan `/day-two` dari browser sampai HTML.

---

# Day 3 — Routing Fundamentals

## Goal

Memahami bagaimana `HTTP method + path` memilih handler.

## Understand

```text
GET /tours
GET /tours/{id}
POST /tours
PATCH /tours/{id}
DELETE /tours/{id}
```

Hari ini gunakan GET saja.

## Codex Step 1 — Inspect

```text
Inspect routes/web.php dan php artisan route:list.

Jangan modify.

Jelaskan:
method
URI
route name
handler
```

## Codex Step 2 — Static Route

```text
Add:

GET /hello

Return plain text:
Hello Laravel Routing

Jangan controller.
Jangan Blade.
Jangan database.

Run route:list dan show diff.
```

## Codex Step 3 — Dynamic Route

```text
Add:

GET /tours/{id}

Return:
Tour ID: {id}

Name:
tours.show

Jangan query database.
Jangan controller.
```

## Manual Experiment

Coba:

```text
/tours/1
/tours/999
/tours/hello
/tours
```

## Explain Back

Pahami bahwa:

```text
{id}
```

adalah route parameter, **bukan database query dan bukan validation**.

---

# Day 4 — Blade & Server-Side Rendering

## Goal

Memahami Blade sebagai template yang dirender server menjadi HTML.

## Understand

```text
PHP data
↓
Blade template
↓
HTML
↓
Browser
```

Browser tidak menjalankan Blade.

## Codex Step 1 — Inspect

```text
Inspect current views dan hubungan view('...') dengan Blade file.

Jangan modify.
```

## Codex Step 2 — Data to View

```text
Create GET /tours-demo.

Kirim array hardcoded berisi 3 tour dari route ke Blade.

Data:
name
duration
price

Jangan controller.
Jangan database.
```

## Codex Step 3 — Render List

```text
Render tours menggunakan @foreach.

Gunakan escaped Blade output.

Jangan styling.
```

## Security Mini Lesson

Bandingkan:

```text
{{ $value }}
```

dan:

```text
{!! $value !!}
```

Untuk untrusted data, pahami kenapa raw output berbahaya.

## Verify

Masukkan string seperti:

```html
<script>alert('x')</script>
```

sebagai data eksperimen dan lihat perbedaan safe rendering.

Jangan membangun exploit; tujuan hanya memahami escaping.

---

# Day 5 — Controller & Separation of Concerns

## Goal

Memahami kenapa logic tidak terus ditaruh di route closure.

## Before

```text
Route
→ closure
→ Blade
```

## After

```text
Route
→ TourController
→ Blade
```

## Codex Step 1 — Inspect

```text
Inspect /tours-demo.

Jelaskan logic yang saat ini berada di route closure.

Jangan modify.
```

## Codex Step 2 — Generate Controller

```text
Create TourController menggunakan Artisan.

Jangan pindahkan logic dulu.
Show generated file only.
```

## Codex Step 3 — Move Behavior

```text
Move /tours-demo handler ke TourController@index.

Behavior harus tetap sama.

Jangan database.
```

## Verify

```powershell
php artisan route:list
git diff
```

Browser output harus tetap sama.

## Explain Back

Apa problem yang mulai diselesaikan controller?

---

# Day 6 — MySQL Connection + Environment Configuration

## Goal

Menghubungkan Laravel ke MySQL tanpa membocorkan credentials.

## Understand

```text
Laravel
↓
config/database.php
↓
environment values
↓
.env
↓
PDO MySQL
↓
MySQL server
```

## Codex Step 1 — Inspect

```text
Inspect local MySQL dan Laravel database configuration.

Jangan modify.

Find:
MySQL version
port
server status
current datadir
Laravel DB config
```

## Codex Step 2 — Database

```text
Create database:

tourflow

hanya jika belum ada.

Jangan drop/overwrite database existing.
Jangan expose password.
```

## Codex Step 3 — Configure Laravel

```text
Update local .env agar Laravel memakai MySQL tourflow.

Jangan commit .env.
Jangan hardcode credentials ke source.
```

## Verify

```powershell
php artisan migrate:status
```

Lalu migration bawaan jika sesuai.

## Optional System Task — MySQL Datadir di D:

Jika kamu masih ingin MySQL data di disk D:

```text
Inspect dahulu:
service name
current datadir
config file
existing databases
service permissions

Jangan memindahkan data sebelum backup dan rollback plan jelas.
Gunakan copy sebelum cut/delete.
```

Task ini optional dan tidak boleh menghambat belajar Laravel.

---

# Day 7 — Migrations + First Domain Model

## Goal

Memahami database schema sebagai versioned code.

## Domain Pertama

```text
Tour
├── id
├── name
├── description
├── duration_minutes
├── base_price
├── default_capacity
├── status
├── created_at
└── updated_at
```

## Understand

```text
Migration
↓
php artisan migrate
↓
SQL schema change
↓
MySQL
```

## Codex Step 1 — Inspect

```text
Inspect migration bawaan Laravel.

Jelaskan:
up()
down()
migration history

Jangan modify.
```

## Codex Step 2 — Generate

```text
Generate create_tours_table migration.

Jangan menjalankan migration dulu.
```

## Codex Step 3 — Design Columns

```text
Review proposed Tour columns dan database types.

Jangan implement tambahan dulu.

Jelaskan trade-off khusus:
money
duration
status
capacity
```

## Codex Step 4 — Implement & Run

```text
Implement agreed schema.
Run migration.
Verify migrate:status.
```

## Verify

Pastikan table benar-benar muncul di MySQL.

---

# WEEK 2
# ELOQUENT, CRUD, RELATIONSHIPS, BUSINESS RULES

---

# Day 8 — Eloquent Model & Read Flow

## Goal

Memahami model, table, query, collection, dan row.

## Understand

```text
Tour Model
↓
Eloquent
↓
SQL
↓
tours table
```

## Codex Step 1

```text
Create Tour model only.

Jangan resource controller.
Jangan factory.
Jangan CRUD.
```

## Codex Step 2

```text
Gunakan Tinker untuk membuat maksimal 3 Tour sample.

Jangan create seeder dulu.
```

## Codex Step 3

```text
Update TourController@index agar membaca Tour dari database.

Render name, duration, base price saja.
```

## Verify

```powershell
php artisan tinker
php artisan route:list
git diff
```

## Explain Back

Bedakan:

```text
Tour class
Tour instance
tours table
database row
Collection<Tour>
```

---

# Day 9 — Show One Tour + Route Model Binding

## Goal

Memahami lookup satu resource dan 404 behavior.

## Codex Step 1

```text
Add GET /tours/{tour}
→ TourController@show

Gunakan route model binding.

Jangan create/edit/delete.
```

## Codex Step 2

```text
Create tours/show.blade.php.

Tampilkan Tour fields secara sederhana.
Tanpa Tailwind dulu.
```

## Verify

Coba ID valid dan ID tidak ada.

## Explain Back

```text
/tours/5
↓
router
↓
route model binding
↓
SELECT Tour
↓
Tour object atau 404
```

---

# Day 10 — Create Form: Understand Request Before Saving

## Goal

Memahami HTML form dan request payload.

## Codex Step 1

```text
Create GET /tours/create.

Render form:
name
description
duration
price
capacity
status

Jangan store ke database.
```

## Codex Step 2

```text
Add POST /tours.

Untuk eksperimen pertama:
inspect/dump incoming request fields.

Jangan save.
```

## Manual

Submit form.

Pelajari:

```text
form action
form method
input name
request payload
CSRF token
```

## Security

Pastikan form POST memakai CSRF protection.

---

# Day 11 — Validation + Store

## Goal

Memisahkan untrusted client input dari validated data.

## Codex Step 1 — Validation in Controller

```text
Tambahkan validation minimal untuk Tour create.

name: required
duration: integer positive
price: numeric non-negative
capacity: integer positive
status: allowed values

Jangan FormRequest dulu.
```

## Test Manual

Coba:

```text
empty name
negative price
capacity text
unknown status
```

## Codex Step 2 — Save

```text
Setelah validation bekerja,
save hanya validated data menggunakan Eloquent.

Redirect ke halaman yang masuk akal.
```

## Codex Step 3 — Extract FormRequest

```text
Move validation ke StoreTourRequest.

Behavior tidak boleh berubah.
```

## Explain Back

```text
untrusted request
↓
validation
↓
trusted validated subset
↓
Eloquent
↓
database
```

---

# Day 12 — Edit, Update, Archive

## Goal

Melengkapi lifecycle Tour tanpa langsung menghapus history bisnis.

## Codex Step 1

```text
Create GET /tours/{tour}/edit.

Prefill existing values.

Jangan implement update.
```

## Codex Step 2

```text
Add PATCH /tours/{tour}.

Inspect validated payload dahulu.
Jangan save pada step ini.
```

## Codex Step 3

```text
Implement update dengan UpdateTourRequest.

Run tests/manual verification.
```

## Product Decision

Untuk Tour yang pernah dipakai, prefer:

```text
status = inactive
```

daripada hard delete.

Pahami alasan audit/history sebelum memakai delete sembarangan.

---

# Day 13 — Guide Model + Relationships

## Goal

Memahami relational data dengan use case nyata.

## Guide

```text
Guide
├── id
├── name
├── phone
├── email
├── status
└── timestamps
```

## Codex Step 1

```text
Plan Guide schema.

Jangan implement.

Jelaskan field minimum yang diperlukan.
```

## Codex Step 2

```text
Create Guide migration dan model.

Run migration setelah review.
```

## Codex Step 3

```text
Create minimal Guide list page.

Read only.
Belum CRUD lengkap.
```

## Understand

Guide belum punya relation langsung ke Tour.

Relation penting akan muncul lewat `Departure`.

---

# Day 14 — Departure: Joining Tour + Guide + Time

## Goal

Memahami foreign key dan relational scheduling.

## Departure

```text
Departure
├── id
├── tour_id
├── guide_id nullable
├── starts_at
├── capacity
├── status
└── timestamps
```

## Mental Model

```text
Tour 1
   │
   ├── Departure A → Guide Made
   └── Departure B → Guide Wayan
```

## Codex Step 1 — Plan

```text
Design Departure schema.

Jangan implement.

Explain:
foreign key
nullable guide_id
datetime
capacity
status
```

## Codex Step 2 — Migration/Model

```text
Create Departure migration dan model.
Run migration setelah diff review.
```

## Codex Step 3 — Relationships

```text
Add only these Eloquent relationships:

Tour hasMany Departures
Departure belongsTo Tour
Departure belongsTo Guide
Guide hasMany Departures

Jangan add business rules.
```

## Verify

Gunakan Tinker untuk relationship queries kecil.

---

# Day 15 — Guide Scheduling Conflict

## Goal

Belajar business rule yang bukan CRUD sederhana.

Rule:

> Satu guide tidak boleh assigned ke dua departure yang overlap waktunya.

## Before Coding

Tulis kasus:

```text
Departure A
08:00 - 12:00
Guide Made

Departure B
10:00 - 14:00
Guide Made

→ conflict
```

Dan:

```text
Departure A
08:00 - 12:00

Departure B
13:00 - 17:00

→ allowed
```

## Codex Step 1 — Specify Only

```text
Jangan code.

Bantu saya mendefinisikan overlap rule untuk guide scheduling.

Berikan:
allowed cases
conflict cases
edge cases
```

## Codex Step 2 — Query Exploration

```text
Tulis query approach paling sederhana untuk mendeteksi overlap.

Jangan pasang ke controller dulu.
```

## Codex Step 3 — Implement One Boundary

```text
Implement conflict check pada guide assignment flow.

Gunakan error message yang jelas.

Jangan buat service abstraction kecuali benar-benar diperlukan.
```

## Verify

Test minimal 4 time-overlap scenarios.

---

# WEEK 3
# BOOKINGS, AUTH, AUTHORIZATION, UI, TESTING, SECURITY

---

# Day 16 — Booking + Capacity Rule

## Goal

Menambahkan booking sebagai transactional business data.

## Booking

```text
Booking
├── id
├── departure_id
├── customer_name
├── customer_phone
├── guest_count
├── status
├── notes
└── timestamps
```

## Business Rule

```text
current confirmed guests
+
new booking guests
<=
departure capacity
```

## Codex Step 1

```text
Plan Booking schema dan relationship.

Jangan implement.
```

## Codex Step 2

```text
Create migration/model/relationships only.
```

## Codex Step 3

```text
Create booking form untuk departure.

Jangan save.
Inspect request payload.
```

## Codex Step 4

```text
Implement validation dan capacity check.

Save hanya jika capacity cukup.
```

## Verify

Coba exact capacity dan over-capacity.

---

# Day 17 — Authentication

## Goal

Aplikasi operasional tidak boleh terbuka untuk semua orang.

## Understand

```text
Unauthenticated visitor
      ↓
     login
      ↓
authenticated session
      ↓
protected dashboard
```

## Codex Step 1 — Inspect Options

```text
Inspect current Laravel authentication options yang cocok untuk Blade app.

Jangan install dulu.

Recommend simplest first-party approach compatible with project.
Explain what it adds.
```

## Codex Step 2 — Install Minimal Auth

```text
Implement only minimal session-based authentication for Blade app.

Jangan add social login.
Jangan API auth.
```

## Codex Step 3 — Protect Routes

```text
Protect operations dashboard and operational routes with auth middleware.

Public login route tetap accessible.
```

## Verify

Test:

```text
guest → protected page → redirected
logged-in user → page allowed
logout → access revoked
```

## Security

Jangan membuat custom password hashing.

Gunakan mekanisme framework yang benar.

---

# Day 18 — Authorization: Owner/Admin vs Staff

## Goal

Memahami bahwa authenticated ≠ authorized.

Roles sederhana:

```text
admin
staff
```

Contoh:

```text
staff
→ lihat tour/departure/booking
→ create/update booking

admin
→ manage tours
→ manage guides
→ manage user-sensitive operations
```

## Codex Step 1 — Threat/Permission Matrix

```text
Jangan code.

Buat permission matrix minimal admin vs staff untuk current features.
```

## Codex Step 2

```text
Implement role representation paling sederhana.

Hindari permission package jika dua role sederhana sudah cukup.
```

## Codex Step 3

```text
Implement satu authorization policy/check untuk Tour update.

Test dahulu sebelum memperluas ke action lain.
```

## Codex Step 4

Perluas hanya setelah pola dipahami.

## Verify

Coba user role berbeda.

Penting:

UI menyembunyikan tombol **bukan** security boundary.

Server harus tetap menolak unauthorized request.

---

# Day 19 — Tailwind + Flowbite + Blade Layout

## Goal

Membuat UI demo-ready tanpa mengubah backend behavior.

## Codex Step 1 — Inspect Frontend

```text
Inspect:
package.json
Vite config
CSS/JS entry files

Jangan install apa pun.
```

## Codex Step 2 — Tailwind

```text
Setup Tailwind dengan approach yang kompatibel dengan project Laravel saat ini.

Jangan redesign app.
Verify build.
```

## Codex Step 3 — Flowbite / Prebuilt UI

```text
Add Flowbite atau pre-built Tailwind components dengan perubahan minimal.

Jangan ganti stack ke React/Vue/Livewire.
```

## Codex Step 4 — Layout

```text
Create shared Blade layout:
sidebar
top navigation
main content

Jangan componentize semuanya.
```

## UI Target

```text
Dashboard
Tours
Guides
Departures
Bookings
```

---

# Day 20 — Operational Dashboard + Usability

## Goal

Mengubah data menjadi informasi operasional.

Dashboard:

```text
Today's Departures
Today's Guests
Available Guides
Upcoming Departures
Capacity Warnings
```

## Codex Step 1

```text
Plan dashboard metrics.

Jangan code.

Untuk setiap metric:
source table
query
meaning
risk of incorrect interpretation
```

## Codex Step 2

```text
Implement hanya Today's Departures count.
```

Inspect query.

## Codex Step 3

Tambahkan satu metric per perubahan:

```text
Today's Guests
Upcoming Departures
```

## Codex Step 4

Gunakan pre-built cards/table untuk UI.

## Verify

Bandingkan nilai UI dengan query/data database.

---

# Day 21 — Feature Tests for Critical Flows

## Goal

Membangun safety net sebelum security/deployment.

## Understand

```text
Arrange
↓
Act
↓
Assert
```

## Codex Step 1

```text
Inspect test setup.

Jangan write test.

Explain:
test environment
database isolation
feature vs unit test
```

## Codex Step 2

Satu test:

```text
guest cannot access dashboard
```

Run.

## Codex Step 3

Satu test:

```text
authorized user can create Tour with valid data
```

Run.

## Codex Step 4

Tambahkan satu-satu:

```text
invalid Tour rejected
staff cannot perform admin-only Tour update
booking cannot exceed departure capacity
guide cannot be assigned to overlapping departures
missing Tour returns 404
```

Jangan generate seluruh test suite dalam satu prompt.

## Verify

```powershell
php artisan test
```

---

# Day 22 — Application Security Review

## Goal

Melakukan threat-driven review, bukan checklist kosmetik.

## Security Topics

```text
authentication
authorization
CSRF
XSS
mass assignment
validation
SQL injection
session/cookies
open redirect
file permissions
APP_DEBUG
secrets
dependency vulnerabilities
rate limiting
```

## Codex Step 1 — Threat Model

```text
Jangan modify code.

Threat-model aplikasi TourFlow saat ini.

Assets:
user accounts
customer booking data
tour schedules
guide contact data
database credentials

Entry points:
login
forms
URLs
deployment config

Pisahkan:
threat
impact
existing control
missing control
```

## Codex Step 2 — Code Review

```text
Review codebase.

Jangan modify.

Cari evidence untuk:
unvalidated input
missing authorization
raw Blade output
mass assignment risks
unsafe redirects
SQL/query misuse
secret exposure
debug config risk
```

## Codex Step 3 — Fix One Confirmed Issue at a Time

Setiap issue:

```text
confirm evidence
↓
small fix
↓
test
↓
diff review
```

## Manual Security Checks

Pastikan:

```text
.env ignored
APP_DEBUG production plan = false
POST/PATCH/DELETE protected CSRF
untrusted data uses escaped Blade output
authorization server-side
```

---

# Day 23 — Security Hardening + Dependency Hygiene

## Goal

Menambah protection yang relevan setelah threat model.

## Codex Step 1 — Dependencies

```text
Inspect Composer dan npm dependency health.

Jangan auto-upgrade major versions.

Run appropriate audit commands.
Explain each finding.
```

Potential commands:

```powershell
composer audit
npm audit
```

## Codex Step 2 — Rate Limiting

```text
Review which endpoint actually benefits from rate limiting.

Do not add global aggressive limits.

Start with login or other abuse-prone endpoint if applicable.
```

## Codex Step 3 — Security Headers

```text
Plan security headers suitable for HTTPS production.

Jangan blindly add headers.

Explain:
X-Content-Type-Options
X-Frame-Options / frame policy
Referrer-Policy
HSTS
Content-Security-Policy trade-offs
```

Implement minimal safe set only after understanding effect.

## Codex Step 4 — Secret Scan

```text
Inspect git tracked files/history for obvious secrets.

Jangan print secret values.
Report file/path and remediation only.
```

## Verify

Tests tetap hijau.

---

# WEEK 4
# DOCKER, OPERATIONS, AWS, CI/CD, PRODUCTION READINESS

---

# Day 24 — Production Configuration & Laravel Runtime

## Goal

Memahami perbedaan development dan production.

## Understand

Production Laravel baseline:

```text
APP_ENV=production
APP_DEBUG=false
secure APP_KEY
HTTPS
config cache
route cache
view cache
logs
health check
```

## Codex Step 1

```text
Inspect current Laravel production requirements.

Jangan modify.

List:
PHP extensions
writable directories
environment variables
build artifacts
health endpoint
```

## Codex Step 2

```text
Create production configuration checklist/documentation.

Jangan put secrets in repo.
```

## Codex Step 3

```text
Test Laravel optimization commands locally in a safe way.

Explain:
php artisan optimize
php artisan optimize:clear

Verify app still boots.
```

## Important

Production must not serve project root publicly.

Web server document root must point to Laravel `public/`.

---

# Day 25 — Docker Production Image

## Goal

Membuat reproducible application artifact.

## Architecture Awal

```text
Nginx
↓
PHP-FPM Laravel
↓
MySQL
```

AWS nanti menggunakan RDS, bukan MySQL container production.

## Codex Step 1 — Plan Dockerfile

```text
Jangan create Dockerfile.

Plan minimal production Docker image for Laravel.

Explain:
base image
PHP extensions
Composer dependencies
frontend asset build
non-root considerations
writable directories
startup command
```

## Codex Step 2 — Build PHP App Image

```text
Implement Dockerfile untuk Laravel app only.

Jangan Nginx dulu.
Jangan MySQL.
```

Build dan inspect.

## Codex Step 3 — Nginx

```text
Add minimal Nginx config/container for local production-like testing.

Document root harus /public.
```

## Verify

```powershell
docker build ...
docker compose ps
docker compose logs
```

No secrets baked into image.

---

# Day 26 — Local Production-Like Stack + Health + Persistence

## Goal

Menjalankan full stack container lokal sebagai rehearsal.

## Local Only

```text
Browser
↓
Nginx
↓
Laravel/PHP-FPM
↓
MySQL container
   ↓
named volume
```

## Codex Step 1

```text
Add compose service untuk local MySQL.

Gunakan local env/secrets.
Jangan hardcode production credentials.
```

## Codex Step 2

```text
Add healthchecks untuk service yang relevan.

Gunakan Laravel health route bila sesuai.
```

## Codex Step 3

```text
Test restart behavior.

Verify database data survives container recreation yang tidak menghapus volume.
```

## Failure Drill

Stop database.

Observe:

```text
symptom
Laravel error/log
container status
```

Start kembali.

## Explain Back

Bedakan:

```text
image
container
volume
network
port
environment variable
health check
```

---

# Day 27 — AWS Architecture, IAM, Networking, and Cost Guardrails

## Goal

Memahami cloud architecture sebelum deploy.

## Target Architecture

```text
Internet
   ↓
Route 53 / Domain
   ↓
HTTPS / ACM
   ↓
Application Load Balancer
   ↓
ECS Fargate Service
   ↓
Laravel Container
   ↓
RDS MySQL

Container Image
   ↑
ECR

Secrets
   ↓
AWS Secrets Manager

Logs / Metrics
   ↓
CloudWatch
```

## Network Goal

```text
Public:
ALB

Private:
ECS tasks where practical
RDS

RDS:
no public internet access
security group allows DB traffic only from app layer
```

## Codex Step 1 — Architecture Review

```text
Jangan create AWS resources.

Design AWS architecture for this Laravel app using:

ECR
ECS Fargate
ALB
RDS MySQL
Secrets Manager
CloudWatch
ACM
Route 53 if domain is available

Explain each component and why it exists.

Keep architecture suitable for small production MVP.
Avoid Kubernetes.
```

## Codex Step 2 — IAM Plan

```text
Jangan create credentials.

Define minimal IAM roles needed:
deployment identity
ECS task execution role
application task role

Explain difference.
```

## Codex Step 3 — Cost Guardrail

Before provisioning:

```text
list resources that can generate ongoing AWS cost
identify free-tier/non-free assumptions
define teardown checklist
```

## Security

Never place AWS access keys in repository or `.env` committed to Git.

---

# Day 28 — AWS Provisioning: ECR, RDS, Secrets, ECS

## Goal

Deploy infrastructure in controlled increments.

Do **not** ask Codex to deploy everything at once.

## Step 1 — ECR

Codex prompt:

```text
Create a plan to create only the ECR repository for TourFlow.

Do not create ECS/RDS yet.

Show exact AWS CLI commands before execution.
Explain each command.
```

Then:

```text
build Docker image
tag image
authenticate to ECR
push image
verify image exists
```

## Step 2 — RDS

Prompt:

```text
Plan only RDS MySQL.

Requirements:
not publicly accessible
dedicated security group
encrypted storage
automated backups
credentials not in Git

Do not create yet.
```

Review cost/security.

Then provision.

## Step 3 — Secrets Manager

Store sensitive production configuration.

Examples:

```text
DB credentials
APP_KEY
other application secrets
```

Do not store non-secret configuration there unless useful.

## Step 4 — ECS Task Definition

Create task definition using:

```text
ECR image
environment configuration
Secrets Manager references
CloudWatch logs
health configuration
```

Do not create service until task definition is understood.

## Step 5 — ECS Service + ALB

Create in final sub-step.

## Verify

Do not call deployment successful merely because task is `RUNNING`.

Verify:

```text
container healthy
Laravel /up healthy
DB connection works
migration state understood
logs visible
no secrets printed
```

---

# Day 29 — HTTPS, Domain, Observability, Backups, CI/CD

## Goal

Membuat deployment operable, bukan hanya accessible.

## Part A — HTTPS

Jika domain tersedia:

```text
Route 53 / DNS
↓
ACM certificate
↓
ALB HTTPS listener
↓
redirect HTTP → HTTPS
```

Jika belum punya domain, dokumentasikan blocker; jangan mengklaim full HTTPS/domain selesai.

## Part B — CloudWatch

Codex step:

```text
Inspect existing ECS logs and metrics.

Jangan add complex observability stack.

Set up useful baseline:
application logs
ECS health
CPU/memory signals
ALB unhealthy target awareness
RDS storage/connection awareness
```

## Part C — Backups

RDS:

```text
automated backups enabled
retention understood
manual snapshot before risky change
```

Yang penting:

> backup yang belum pernah diuji restore-nya belum cukup.

Lakukan restore rehearsal ke non-production resource jika budget/permissions memungkinkan.

## Part D — GitHub CI/CD

Pipeline minimum:

```text
push/merge main
↓
tests
↓
dependency/security checks
↓
build image
↓
push ECR
↓
update ECS service
```

## Codex Rule

Build CI/CD step by step:

```text
1. test job only
2. add Docker build
3. add ECR auth/push
4. add ECS deploy
```

Jangan satu workflow besar sekaligus.

## Security

Prefer short-lived/federated GitHub → AWS credentials (OIDC) over long-lived AWS access keys in GitHub secrets when available.

---

# Day 30 — Production Readiness Review + Failure Drills + Demo

## Goal

Membuktikan system bekerja, aman secara baseline, dan bisa dioperasikan.

Tidak cukup dengan:

```text
"website bisa dibuka"
```

---

## Part 1 — Full Functional Verification

Test:

```text
login
logout
role authorization

Tour:
list
create
show
update
deactivate

Guide:
list
manage baseline data

Departure:
create
assign guide
reject overlapping guide schedule

Booking:
create
reject over-capacity booking

Dashboard:
metrics match database
```

---

## Part 2 — Automated Tests

```powershell
php artisan test
```

Pastikan critical tests hijau.

Review test failures, skipped tests, dan environment.

---

## Part 3 — Security Verification

Checklist:

```text
[ ] APP_DEBUG=false
[ ] .env not tracked
[ ] no hardcoded database/AWS secrets
[ ] HTTPS works
[ ] RDS not publicly accessible
[ ] authorization enforced server-side
[ ] CSRF protection present
[ ] untrusted Blade output escaped
[ ] validation for mutating inputs
[ ] dependency audits reviewed
[ ] least-privilege security groups
[ ] AWS IAM roles understood
[ ] production logs do not expose secrets
[ ] login abuse protection/rate limit reviewed
```

---

## Part 4 — Operations Verification

```text
[ ] /up health endpoint responds
[ ] CloudWatch logs visible
[ ] failed request can be traced
[ ] ECS task replacement understood
[ ] RDS backup configured
[ ] restore procedure documented/tested
[ ] deployment procedure documented
[ ] rollback procedure documented
```

---

## Part 5 — Failure Drills

Lakukan controlled drills.

### Drill A — Broken Application Deployment

Deploy image dengan controlled harmless failure di non-production/staging bila memungkinkan.

Pelajari:

```text
ALB health
ECS deployment state
CloudWatch logs
rollback
```

### Drill B — Database Unavailable

Di local/staging, simulasi DB unavailable.

Pahami:

```text
symptom
logs
health
recovery
```

### Drill C — Bad Migration Reasoning

Jangan sengaja merusak production.

Diskusikan:

```text
migration compatibility
backup/snapshot
expand-contract mindset
rollback limitations
```

---

## Part 6 — Final Codex Engineering Review

```text
Perform a final review.

DO NOT modify anything.

Review:
correctness
business rules
authentication
authorization
validation
CSRF
XSS handling
mass assignment
database queries
secrets
Laravel production config
Docker image
AWS networking
AWS IAM assumptions
RDS exposure
logging
backup/restore
tests
deployment rollback

Classify findings:

1. BLOCKER
2. IMPORTANT
3. OPTIONAL

For every finding:
- evidence
- risk
- recommended remediation
- verification

Do not suggest architectural complexity without a concrete requirement.
```

Fix blockers one by one.

---

# FINAL AWS ARCHITECTURE MENTAL MODEL

```text
USER
 │
 │ HTTPS
 ▼
DNS / Route 53
 │
 ▼
AWS Certificate Manager
 │
 ▼
Application Load Balancer
 │
 │ HTTP internal traffic
 ▼
ECS Fargate
┌──────────────────────────┐
│ Nginx / Laravel runtime  │
│ PHP-FPM                  │
│ Laravel                  │
└──────────────────────────┘
 │
 │ private DB connection
 ▼
Amazon RDS MySQL
 │
 └── automated backup / snapshots


Docker Image
     ▲
     │
Amazon ECR


Secrets Manager
     │
     └── secrets injected to ECS


Application / Container Logs
     │
     ▼
CloudWatch
```

---

# FINAL REQUEST FLOW MENTAL MODEL

Contoh operator membuka departure:

```text
Browser
↓
HTTPS
↓
ALB
↓
ECS container
↓
Nginx
↓
public/index.php
↓
Laravel Router
↓
Auth middleware
↓
Authorization
↓
DepartureController
↓
Eloquent
↓
RDS MySQL
↓
Controller
↓
Blade
↓
HTML
↓
Browser
```

---

# FINAL BOOKING WRITE FLOW

```text
Staff submits booking form
↓
POST request
↓
CSRF verification
↓
authentication
↓
authorization
↓
FormRequest validation
↓
capacity business rule
↓
Eloquent INSERT
↓
RDS MySQL
↓
redirect
↓
success message
```

---

# FINAL GUIDE ASSIGNMENT FLOW

```text
Admin selects Guide
↓
PATCH Departure
↓
validation
↓
authorization
↓
check existing Guide departures
↓
overlap?
 ├── YES → reject with validation/business error
 └── NO  → save guide assignment
```

---

# Git Workflow yang Digunakan Sepanjang 30 Hari

Setiap unit pekerjaan:

```text
inspect
↓
small change
↓
git diff
↓
test
↓
security/correctness review
↓
git add
↓
git commit
↓
push
```

Useful commands:

```powershell
git status
git diff
git diff --staged
git log --oneline
git show
```

Jangan menerima perubahan Codex tanpa membaca diff.

---

# Daily End-of-Day Template

Setelah setiap hari, jawab sendiri:

## WHAT

```text
Hari ini code/infrastructure apa yang berubah?
```

## WHY

```text
Masalah apa yang diselesaikan?
```

## FLOW

```text
Bagaimana request/data berpindah?
```

## RISK

```text
Apa failure mode atau security risk terbesarnya?
```

## VERIFY

```text
Command/test/manual evidence apa yang membuktikan implementasi benar?
```

## UNKNOWN

```text
Apa yang masih belum saya pahami?
```

Jangan menyembunyikan bagian UNKNOWN.

---

# Definition of Done Project

Project dianggap mencapai target 30 hari hanya jika:

## Application

```text
[ ] Laravel app boots cleanly
[ ] MySQL/RDS is source of persistent business data
[ ] Tour workflow works
[ ] Guide workflow works
[ ] Departure scheduling works
[ ] guide overlap rule works
[ ] Booking works
[ ] capacity rule works
[ ] Dashboard works
```

## UX

```text
[ ] Blade used
[ ] Tailwind used
[ ] pre-built UI used where useful
[ ] forms show validation errors
[ ] basic responsive usability
[ ] destructive/important actions understandable
```

## Authentication / Authorization

```text
[ ] login/logout
[ ] protected operational routes
[ ] role/policy checks server-side
[ ] staff cannot bypass permissions via crafted URL/request
```

## Security

```text
[ ] no committed secrets
[ ] APP_DEBUG=false production
[ ] CSRF protection
[ ] XSS-safe Blade output
[ ] validation
[ ] dependency audit
[ ] security review
[ ] production database private
[ ] least privilege baseline
[ ] HTTPS
```

## Testing

```text
[ ] authentication tests
[ ] authorization tests
[ ] Tour validation tests
[ ] booking capacity tests
[ ] guide conflict tests
[ ] relevant tests pass
```

## Containers

```text
[ ] reproducible Docker build
[ ] no secrets baked into image
[ ] health checks
[ ] local production-like stack works
```

## AWS

```text
[ ] ECR image
[ ] ECS Fargate service
[ ] ALB
[ ] RDS MySQL
[ ] Secrets Manager
[ ] CloudWatch
[ ] HTTPS when domain available
[ ] backup configured
[ ] restore procedure tested/documented
```

## Operations

```text
[ ] health endpoint
[ ] deployment runbook
[ ] rollback runbook
[ ] logs accessible
[ ] backup/restore plan
[ ] cost/teardown awareness
```

---

# What We Deliberately Do NOT Add

Belum perlu:

```text
microservices
Kubernetes
CQRS
event sourcing
Kafka
RabbitMQ
Redis cluster
service mesh
multi-region architecture
complex repository layer
generic service layer for every model
GraphQL
React/Vue frontend
```

Kalau requirement belum membutuhkan, kompleksitas tersebut bukan upgrade.

---

# Setelah Day 30

Phase berikutnya bisa memilih salah satu:

## Product Expansion

```text
customer accounts
pickup locations
vehicles
equipment allocation
guide commission
calendar view
notifications
email/WhatsApp integration
guest manifest
check-in
cancellation workflow
refund/payment integration
```

## Engineering Expansion

```text
staging environment
Terraform / Infrastructure as Code
blue/green deployment
Sentry/OpenTelemetry
queue workers
Redis cache
load testing
performance profiling
database indexing
CI security scanning lebih dalam
AWS WAF bila threat model membutuhkannya
multi-AZ / availability improvements
```

Jangan menambah semua sekaligus.

---

# North Star

Targetnya bukan:

> "Saya bisa menyuruh Codex membuat Tour SaaS."

Targetnya:

> "Saya dapat memahami requirement bisnis, memecahnya menjadi perubahan kecil, mengarahkan Codex, membaca diff, mengikuti request/data flow, menguji behavior, menemukan root cause saat gagal, melakukan security review, membuat deployment yang dapat direproduksi, dan mempertanggungjawabkan aplikasi Laravel yang berjalan di production."
