<?php
$page_title = "Dashboard";
include 'header.php';
require_once 'db_connect.php';

// Fetch statistics
$total_students = $conn->query("SELECT COUNT(*) as count FROM students")->fetch_assoc()['count'];
$total_teachers = $conn->query("SELECT COUNT(*) as count FROM teachers")->fetch_assoc()['count'];
$total_courses = $conn->query("SELECT COUNT(*) as count FROM courses")->fetch_assoc()['count'];
$total_admins = $conn->query("SELECT COUNT(*) as count FROM admins")->fetch_assoc()['count'];

$conn->close();
?>

<div class="px-4 py-3">
    <h1 class="display-6 fw-bold">Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>!</h1>
    <p class="lead">Here is a quick overview of the university system.</p>
</div>

<!-- Statistics Cards -->
<div class="row g-4 mb-5">
    <div class="col-xl-3 col-md-6">
        <div class="card stat-card bg-stat-students">
            <div class="card-body">
                <div>
                    <h5 class="card-title">Total Students</h5>
                    <h2 class="fw-bold"><?php echo $total_students; ?></h2>
                </div>
                <div class="stat-card-icon">
                    <i class="fas fa-user-graduate"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="card stat-card bg-stat-teachers">
            <div class="card-body">
                <div>
                    <h5 class="card-title">Total Teachers</h5>
                    <h2 class="fw-bold"><?php echo $total_teachers; ?></h2>
                </div>
                <div class="stat-card-icon">
                    <i class="fas fa-chalkboard-teacher"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="card stat-card bg-stat-courses">
            <div class="card-body">
                <div>
                    <h5 class="card-title">Total Courses</h5>
                    <h2 class="fw-bold"><?php echo $total_courses; ?></h2>
                </div>
                <div class="stat-card-icon">
                    <i class="fas fa-book-open"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="card stat-card bg-stat-admins">
            <div class="card-body">
                <div>
                    <h5 class="card-title">System Admins</h5>
                    <h2 class="fw-bold"><?php echo $total_admins; ?></h2>
                </div>
                <div class="stat-card-icon">
                    <i class="fas fa-user-shield"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<h3 class="mb-4">Quick Actions</h3>
<!-- Your old cards can be placed here as Quick Action links, but simplified -->
<div class="list-group">
  <a href="students.php" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
    Manage Student Records
    <i class="fas fa-arrow-right"></i>
  </a>
  <a href="teachers.php" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
    Manage Teacher Records
    <i class="fas fa-arrow-right"></i>
  </a>
  <a href="courses.php" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
    Manage Course List
    <i class="fas fa-arrow-right"></i>
  </a>
  <a href="noticeboard.php" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
    Publish a New Notice
    <i class="fas fa-arrow-right"></i>
  </a>
</div>

<?php
include 'footer.php';
?>