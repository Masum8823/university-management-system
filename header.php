<?php
session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    // Redirect to login page if not logged in, except for the login page itself
    if (basename($_SERVER['PHP_SELF']) != 'index.php') {
        header("location: index.php");
        exit;
    }
}
// Get the current page name to set the 'active' class on the sidebar link
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
    
    <!-- Google Fonts: Poppins (for the new animated style) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Custom CSS (should be last to override other styles) -->
    <link rel="stylesheet" href="style.css">
</head>
<body>

<!-- Sidebar Navigation -->
<div class="sidebar">
    <a class="navbar-brand" href="dashboard.php">
        <i class="fas fa-graduation-cap"></i> <!-- Changed icon for a new look -->
        <span>UMS Portal</span>
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
        <li class="nav-item">
            <a class="nav-link <?php echo ($current_page == 'send_email.php') ? 'active' : ''; ?>" href="send_email.php">
                <i class="fas fa-paper-plane"></i> Send Email
            </a>
        </li>
    </ul>
</div>

<!-- Main Content Area with fade-in animation class -->
<div class="main-content fade-in">
    <!-- Top Navbar for user info and logout -->
    <nav class="top-navbar">
        <ul class="navbar-nav ms-auto">
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="fas fa-user-circle me-2"></i><?php echo isset($_SESSION['username']) ? htmlspecialchars($_SESSION['username']) : 'Admin'; ?>
                </a>
                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                    <li><a class="dropdown-item" href="logout.php"><i class="fas fa-sign-out-alt me-2"></i>Logout</a></li>
                </ul>
            </li>
        </ul>
    </nav>

    <div class="container-fluid">