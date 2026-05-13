
# 🚦 Limpopo Traffic System

An online traffic appointment booking and management system developed using PHP and MySQL.

The system allows citizens to:
- Register and log in securely
- Book traffic-related appointments online
- Receive email confirmations and reminders
- Download booking receipts as PDF files
- Verify appointments using QR codes

---

# 📌 Features

## 👤 User Authentication
- User registration
- Secure login system
- Password hashing
- Forgot password & reset password functionality

---

## 📅 Appointment Booking
- Book appointments online
- Select date and available time slot
- Prevents double booking
- Prevents duplicate appointments on same date

---

## 📧 Email Notifications
- Booking confirmation emails
- Automatic appointment reminder emails
- PHPMailer integration

---

## 📄 PDF Receipt Generation
- Download appointment receipt as PDF
- Includes booking details
- Generated using FPDF

---

## 🔳 QR Code Verification
- QR code generated for each booking
- QR scan opens verification page
- Booking status changes to USED after verification
- Prevents duplicate usage

---

## ❌ Booking Management
- Users can cancel/delete their own appointments
- Session-based security protection

---

# 🛠 Technologies Used

- PHP
- MySQL
- HTML
- CSS
- JavaScript
- PHPMailer
- FPDF
- phpqrcode
- Git & GitHub
- XAMPP

---

# 📷 Screenshots

## Login Page


## Booking Page


## PDF Receipt


## QR Verification


---

# ⚙️ Installation Guide

## 1. Clone Repository

```bash
git clone https://github.com/YOUR_USERNAME/Limpopo-traffic-system.git
```

---

## 2. Move Project

Move project folder to:

```text
C:\xampp\htdocs\
```

---

## 3. Start XAMPP

Start:
- Apache
- MySQL

---

## 4. Import Database

1. Open phpMyAdmin
2. Create database
3. Import SQL file

---

## 5. Run Project

Open browser:

```text
http://localhost/traffic-system
```

---

# 🔐 Security Features

- Password hashing
- Session authentication
- Protected booking deletion
- QR verification system
- Duplicate booking prevention

---

# 🚀 Future Improvements

- Admin dashboard
- SMS notifications
- Online payment integration
- Mobile app version
- Real-time slot management

---

# 👨‍💻 Developer

Developed by Mulalo Lithole

---

# 📄 License

This project is licensed under the MIT License.
