<?php
require_once 'db_connect.php';

// Check if student ID is provided
if (!isset($_GET['id'])) {
    echo '<div class="alert alert-danger">Error: Student ID not provided.</div>';
    exit;
}

$student_id = $_GET['id'];

// Prepare and execute the query to fetch courses for the given student using a JOIN
$sql = "SELECT c.course_code, c.title 
        FROM courses c
        JOIN student_courses sc ON c.course_code = sc.course_code
        WHERE sc.student_id = ? 
        ORDER BY c.course_code ASC";

if ($stmt = $conn->prepare($sql)) {
    $stmt->bind_param("s", $student_id);
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
        echo '<div class="alert alert-info">This student is not enrolled in any courses.</div>';
    }
    
    $stmt->close();
} else {
    echo '<div class="alert alert-danger">Error: Could not prepare the SQL statement.</div>';
}

$conn->close();
?>