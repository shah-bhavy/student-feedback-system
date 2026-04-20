# 🎓 Smart School Feedback Management System

## 👨‍💻 Developer

**Bhavya Shah**

---

## 📌 Project Overview

This is a Laravel-based **Student Feedback Management System** developed as part of a technical assignment.

The system allows students to submit feedback under strict rules, while Admin, Faculty, and Principal can review and manage it through a structured workflow.

---

## 🚀 Features

### 🔐 Authentication & Roles

* Single login system for all users
* Role-based access:

  * Admin
  * Principal
  * Faculty
  * Student

---

### 📝 Feedback System

* Students can:

  * Submit feedback
  * Edit/delete their own feedback

#### 📏 Rules:

* Only **1 feedback per week**
* Submission allowed **only on Friday**

---

### 🔄 Multi-Step Workflow

Feedback follows approval flow:

👉 **Student → Faculty → Principal**

* Faculty reviews feedback
* Principal gives final approval
* Status tracking implemented

---

### 💾 Draft System

* Students can save feedback as draft anytime
* Draft auto-loads on revisit
* Final submission only allowed on Friday

---

### 📅 Admin Date Control

* Admin can override system date
* Useful for testing Friday restriction
* System uses admin-defined date if set

---

### 📧 Email System

* Email notification on registration
* Notification flow supports system communication

---

### 🗑️ Soft Deletes

* Users and feedback use soft delete
* Data can be restored

---

### 🔢 Custom CAPTCHA

* Arithmetic-based captcha
* No external libraries used

---

### 🧪 Pre-configured Test Users

| Role      | Email                                           | Password |
| --------- | ----------------------------------------------- | -------- |
| Admin     | [admin@test.com](mailto:admin@test.com)         | password |
| Principal | [principal@test.com](mailto:principal@test.com) | password |
| Faculty   | [faculty@test.com](mailto:faculty@test.com)     | password |
| Student   | [student@test.com](mailto:student@test.com)     | password |

---

## 🏗️ Tech Stack

* Laravel
* Blade
* Tailwind CSS
* MySQL / SQLite
* Eloquent ORM

---

## ⚙️ Installation & Setup

### 1. Clone / Download Project

### 2. Install Dependencies

```bash
composer install
```

### 3. Setup Environment

```bash
cp .env.example .env
```

Update database credentials in `.env`

---

### 4. Generate App Key

```bash
php artisan key:generate
```

---

### 5. Run Migrations

```bash
php artisan migrate
```

---

### 6. Start Server

```bash
php artisan serve
```

---

## 🧠 System Architecture

This project follows **MVC Architecture**:

* **Model** → Database logic
* **View** → Blade UI
* **Controller** → Business logic

---

## 🔐 Security Features

* Middleware-based route protection
* Role-based access control
* Server-side validation
* Secure authentication

---

## 🎯 Key Highlights

* Real-world business logic implementation
* Role-based workflow system
* Clean Laravel structure
* Scalable and maintainable design

---

## 📌 Developer Note

This project demonstrates my ability to:

* Work with Laravel framework
* Implement structured applications using MVC
* Build full-stack solutions
* Adapt quickly and deliver under time constraints

---

## 🙌 Thank You

Looking forward to your feedback!
