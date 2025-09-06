<div align="center">
  <h1 align="center">University Management System (UMS)</h1>
  <p align="center">
    A dynamic, feature-rich, and secure web application for managing university operations.
    <br />
    ·
    <a href="#">Report Bug</a>
    ·
    <a href="#">Request Feature</a>
  </p>
</div>

---

## 🌟 About The Project

The University Management System (UMS) is a comprehensive, admin-centric portal designed to streamline the administrative tasks of a modern educational institution. Built with a classic PHP & MySQL stack and enhanced with a modern, animated user interface, this system provides a centralized and efficient way to manage students, teachers, courses, and communications.

This project has evolved from a basic CRUD application into a multi-featured platform, incorporating advanced functionalities like dynamic data modals, PDF generation, and an integrated email communication system.

### ✨ Key Features

-   **🖥️ Animated & Modern UI:** A professional, eye-catching, and responsive user interface built with Bootstrap 5 and custom CSS animations for a superior user experience.
-   **🔐 Secure Admin Panel:** A robust login system with password hashing (`password_hash` & `password_verify`) ensures secure access for administrators.
-   **👤 User & Role Management:**
    -   **Admin Management:** Admins can add new administrators, view existing ones, reset passwords, and delete accounts (with self-deletion prevention).
-   **🎓 Student Management:**
    -   Full CRUD (Create, Read, Update, Delete) functionality for student records.
    -   Advanced details per student: Department, Semester, and Blood Group.
    -   **Course Enrollment:** Assign multiple courses to each student via a user-friendly multi-select interface.
    -   **Dynamic Search & Sort:** Instantly search for students by ID, name, or email, and sort records.
    -   **Detailed View & PDF Export:** View complete student details, including enrolled courses, in a pop-up modal and export the information as a PDF file.
-   **👨‍🏫 Teacher Management:**
    -   Complete CRUD functionality for faculty records.
    -   **Assigned Courses View:** View all courses assigned to a specific teacher in a dynamic modal.
    -   **Dynamic Search & Sort:** Quickly find teachers and sort records.
    -   **PDF Export:** Export individual teacher details as a PDF.
-   **📚 Course & Enrollment Management:**
    -   Manage the university's course catalog with details like course code, title, and credits.
    -   Assign courses to specific teachers.
-   **📢 Dynamic Notice Board:**
    -   Publish, edit, and delete notices.
    -   A clean interface shows only titles, with a "View Details" button to open the full notice in a modal.
-   **📧 Integrated Email System:**
    -   A dedicated page for sending emails to specific groups (`All Students`, `All Teachers`, `Everyone`).
    -   Powered by the robust **PHPMailer** library using Gmail's SMTP for reliable delivery.

---

## 🛠️ Built With

This project leverages a powerful and classic stack of web technologies.

*   **Frontend:**
    *   ![HTML5](https://img.shields.io/badge/html5-%23E34F26.svg?style=for-the-badge&logo=html5&logoColor=white)
    *   ![CSS3](https://img.shields.io/badge/css3-%231572B6.svg?style=for-the-badge&logo=css3&logoColor=white)
    *   ![Bootstrap](https://img.shields.io/badge/bootstrap-%237952B3.svg?style=for-the-badge&logo=bootstrap&logoColor=white)
    *   ![JavaScript](https://img.shields.io/badge/javascript-%23F7DF1E.svg?style=for-the-badge&logo=javascript&logoColor=black)
*   **Backend:**
    *   ![PHP](https://img.shields.io/badge/php-%23777BB4.svg?style=for-the-badge&logo=php&logoColor=white)
*   **Database:**
    *   ![MySQL](https://img.shields.io/badge/mysql-%234479A1.svg?style=for-the-badge&logo=mysql&logoColor=white)
*   **Server & Tools:**
    *   **XAMPP (Apache)**
    *   **PHPMailer** (for sending emails)
    *   **FPDF** (for PDF generation)
    *   **Git & GitHub** (for version control)

---

## 🚀 Getting Started

To get a local copy up and running, follow these simple steps.

### Prerequisites

*   **XAMPP:** Make sure you have XAMPP installed. You can download it [here](https://www.apachefriends.org/index.html).
*   **Git:** Git must be installed on your system.

### Installation & Setup

1.  **Clone the repository** into your `htdocs` folder:
    ```sh
    cd C:/xampp/htdocs/
    git clone https://github.com/Masum8823/university-management-system.git university_project
    ```

2.  **Start XAMPP:** Launch the XAMPP Control Panel and start the `Apache` and `MySQL` services.

3.  **Database Setup:**
    *   Navigate to `http://localhost/phpmyadmin` in your browser.
    *   Create a new database and name it `university_db`.
    *   Select the new database and go to the **Import** tab.
    *   Click on "Choose File" and select the `database.sql` file located in the root of the project directory.
    *   Click "Go" to create all the necessary tables.

4.  **Email System Configuration (PHPMailer):**
    *   Navigate to `send_email.php`.(We skip this file for security reasons)
    *   Inside the file, find the following lines:
        ```php
        $mail->Username = 'gmail-address@gmail.com';
        $mail->Password = '16-digit-app-password';
        ```
    *   *(Note: You must have 2-Step Verification enabled on your Google account to generate an App Password.)*

5.  **Run the Application:**
    *   Open your browser and go to: `http://localhost/university_project/`
    *   You should see the admin login page.

---

## 🧑‍💻 Project Contributors

A collaborative effort by:

*   **Masum:** [Lead Developer, Student & Teacher Modules]
*   **Ritu:** [Assistant Lead Developer, Core Layout, Authentication & Admin Modules]
*   **Mahin:** [Add Course module]
*   **Ashraful:** [Add Noticeboard module]
*   **Efte:** []

---

<p align="center">
  <em>Thank you for checking out our project!</em>
</p>
