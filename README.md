# University Management System (UMS)

A comprehensive and dynamic web-based application designed to manage the core functionalities of a university. This project serves as a centralized system for administrators to handle student, teacher, course, and notice board information efficiently.

---

## 🌟 Project Overview

The University Management System is a robust platform built with a classic technology stack, focusing on simplicity, security, and functionality. The system provides an admin-centric panel where authorized users can perform CRUD (Create, Read, Update, Delete) operations on all major entities of the university.

### ✨ Key Features

-   **Secure Admin Panel:** A secure login system for administrators with password hashing to protect user credentials.
-   **Admin Management:** System administrators can add, view, and manage other admin accounts.
-   **Student Management:** A complete module to manage student records, including adding new students, viewing their details, editing information, and deleting records.
-   **Teacher Management:** Efficiently handle faculty information. Add new teachers, manage their departmental details, and update or remove their records.
-   **Course Management:** A dynamic module to manage university courses. It allows assigning specific teachers to courses from a dropdown list, ensuring data integrity.
-   **Dynamic Notice Board:** An interactive notice board where administrators can publish, edit, and delete notices for students and staff.

---

## 🛠️ Technology Stack

This project is built using fundamental web technologies, making it a great example of a classic LAMP stack application.

-   **Frontend:** `HTML5`, `CSS3`, `Bootstrap 5`
-   **Backend:** `PHP`
-   **Database:** `MySQL`
-   **Server:** `Apache` (via XAMPP)
-   **Version Control:** `Git` & `GitHub`

---

## 🚀 How to Run the Project Locally

To set up and run this project on your local machine, please follow these steps:

1.  **Prerequisites:**
    -   Make sure you have [XAMPP](https://www.apachefriends.org/index.html) installed.
    -   Ensure `Git` is installed on your system.

2.  **Clone the Repository:**
    -   Open your terminal or Git Bash.
    -   Navigate to the `htdocs` directory inside your XAMPP installation folder (e.g., `C:/xampp/htdocs/`).
    -   Clone the repository:
        ```bash
        git clone https://github.com/Masum688823/university-management-system.git
        ```
    -   Rename the cloned folder to `university_project`.

3.  **Database Setup:**
    -   Start `Apache` and `MySQL` from the XAMPP Control Panel.
    -   Open your web browser and go to `http://localhost/phpmyadmin`.
    -   Create a new database named `university_db`.
    -   Select the `university_db` database and go to the "Import" tab.
    -   Choose the `database.sql` file (if provided) from the project directory and click "Go".
    *   *(Note: If a `.sql` file is not provided, you will need to create the tables manually as per the schema in the source code.)*

4.  **Running the Application:**
    -   Open your browser and navigate to:
        ```
        http://localhost/university_project/
        ```
    -   You should see the admin login page.

### 🔑 Default Admin Credentials
*   **Username:** `Masum`
*   **Password:** 12345

---

## 🧑‍💻 Project Contributors

This project was developed collaboratively by our team. The contribution of each member was tracked using Git and GitHub's feature-branch workflow.

-   **Masum:** [Briefly mention Masum's part, e.g., "Student & Teacher Management Modules"]
-   **Ritu:** [Briefly mention Ritu's part, e.g., "Core Layout, Authentication & Admin Panel"]
-   **Mahin:** []
-   **Ashraful:** []
-   **Efte:** []

---

Thank you for reviewing our project!
