<?php
require_once 'db_connect.php';

// Check if teacher ID is provided
if (!isset($_GET['id'])) {
    echo '<div class="alert alert-danger">Error: Teacher ID not provided.</div>';
    exit;
}

$teacher_id = $_GET['id'];

// Prepare and execute the query to fetch courses for the given teacher
$courses = [];
$sql = "SELECT course_code, title FROM courses WHERE teacher_id = ? ORDER BY course_code ASC";
if ($stmt = $conn->prepare($sql)) {
    $stmt->bind_param("s", $teacher_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        // Build an HTML list of courses
        echo '<ul class="list-group">';
        while ($row = $result->fetch_assoc()) {
            echo '<li class="list-group-item">';
            echo '<strong>' . htmlspecialchars($row['course_code']) . ':</strong> ';
            echo htmlspecialchars($row['title']);
            echo '</li>';
        }
        echo '</ul>';
    } else {
        // If no courses are found
        echo '<div class="alert alert-info">No courses are currently assigned to this teacher.</div>';
    }
    
    $stmt->close();
} else {
    echo '<div class="alert alert-danger">Error: Could not prepare the SQL statement.</div>';
}

$conn->close();
?>