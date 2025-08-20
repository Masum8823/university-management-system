<?php
session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    if (basename($_SERVER['PHP_SELF']) != 'index.php') {
        header("location: index.php");
        exit;
    }
}
$current_page = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $page_title ?? 'University Management System'; ?></title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome for Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="style.css">
</head>
<body>

<!-- Sidebar Navigation -->
<div class="sidebar">
    <a class="navbar-brand text-white text-center d-block py-3 mb-3" href="dashboard.php" style="background: #002244;">
        <i class="fas fa-university"></i> UMS
    </a>
    <ul class="nav flex-column">
        <li class="nav-item">
            <a class="nav-link <?php echo ($current_page == 'dashboard.php') ? 'active' : ''; ?>" href="dashboard.php">
                <i class="fas fa-tachometer-alt"></i> Dashboard
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link <?php echo ($current_page == 'students.php' || $current_page == 'edit_student.php') ? 'active' : ''; ?>" href="students.php">
                <i class="fas fa-user-graduate"></i> Students
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link <?php echo ($current_page == 'teachers.php' || $current_page == 'edit_teacher.php') ? 'active' : ''; ?>" href="teachers.php">
                <i class="fas fa-chalkboard-teacher"></i> Teachers
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link <?php echo ($current_page == 'courses.php' || $current_page == 'edit_course.php') ? 'active' : ''; ?>" href="courses.php">
                <i class="fas fa-book"></i> Courses
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link <?php echo ($current_page == 'noticeboard.php' || $current_page == 'edit_notice.php') ? 'active' : ''; ?>" href="noticeboard.php">
                <i class="fas fa-bullhorn"></i> Notice Board
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link <?php echo ($current_page == 'admins.php') ? 'active' : ''; ?>" href="admins.php">
                <i class="fas fa-user-shield"></i> Admins
            </a>
        </li>
    </ul>
</div>

<!-- Main Content Area -->
<div class="main-content">
    <!-- Top Navbar for user info and logout -->
    <nav class="top-navbar">
        <ul class="navbar-nav ms-auto">
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="fas fa-user-circle me-2"></i><?php echo htmlspecialchars($_SESSION['username']); ?>
                </a>
                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                    <li><a class="dropdown-item" href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
                </ul>
            </li>
        </ul>
    </nav>

    <div class="container-fluid">