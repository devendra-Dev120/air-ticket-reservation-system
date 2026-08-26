# ✈️ Online Air Ticket Reservation System

A web-based **Online Air Ticket Reservation System** developed as a BCA academic project using **PHP and MySQL**. It provides separate panels for users and administrators to manage flight searching, ticket booking, passenger details, and flight information.

## 🛠️ Technologies Used

* **Frontend:** HTML, CSS, Bootstrap, JavaScript
* **Backend:** PHP
* **Database:** MySQL
* **Server:** Apache (XAMPP)
* **Tools:** VS Code, phpMyAdmin

## ✨ Main Features

### 👤 User Panel

* User registration and login
* Search flights by source, destination, and date
* View flight details
* Select number of passengers/seats
* Enter passenger details
* Book tickets
* View booking history
* View payment and booking information

### 👨‍💼 Admin Panel

* Admin login
* Dashboard
* Add new flights
* View flights
* Edit flight information
* View passenger bookings
* Manage flight status

## 🔄 Booking Flow

```text
Register / Login
      ↓
Search Flight
      ↓
Select Flight
      ↓
Select Passengers / Seats
      ↓
Enter Passenger Details
      ↓
Confirm Booking
      ↓
Payment Information
      ↓
Booking Completed
```

## 🗄️ Database

The system uses **MySQL** to store and manage:

* User information
* Flight information
* Booking information
* Passenger details
* Seat information
* Payment information

## 🔐 Demo Login Credentials

### Admin

```text
Username: admin
Password: admin123
```

### User

```text
Username: user
Password: user123
```

> These are demo credentials for testing the project locally.

## ▶️ How to Run

1. Install **XAMPP**.
2. Start **Apache** and **MySQL** from the XAMPP Control Panel.
3. Copy the project folder into:

```text
C:\xampp\htdocs\air_ticket
```

4. Create the project database in **phpMyAdmin** and import the provided SQL file.
5. Configure the database connection in:

```text
config/db.php
```

6. Open the project in your browser:

```text
http://localhost/air_ticket/
```

## 🎯 Project Objective

The objective of this project is to provide a simple and efficient online platform for **flight searching and ticket reservation**, while allowing administrators to manage flights and passenger bookings through a dedicated admin panel.

## 🚀 Future Improvements

* Online payment gateway integration
* Automatic e-ticket/PDF generation
* Email/SMS booking confirmation
* Real-time seat availability
* Enhanced security
* Cloud deployment

## 👨‍💻 Author

**Devendra Nandurkar**

BCA Graduate

**Technologies:** PHP • MySQL • HTML • CSS • Bootstrap • JavaScript

---

⭐ **If you find this project useful, consider giving the repository a star.**
