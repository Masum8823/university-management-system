<?php
$page_title = "Student Management";
include 'header.php';
require_once 'db_connect.php';

// Check for feedback messages from session (e.g., after an update)
if (isset($_SESSION['feedback_message'])) {
    $feedback_message = $_SESSION['feedback_message'];
    $feedback_class = $_SESSION['feedback_class'];
    // Unset them so they don't appear on page reload
    unset($_SESSION['feedback_message']);
    unset($_SESSION['feedback_class']);
} else {
    // Default feedback variables
    $feedback_message = '';
    $feedback_class = '';
}

// Feedback message variables
$feedback_message = '';
$feedback_class = '';

// --- ACTION: DELETE STUDENT ---
if (isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['id'])) {
    $student_id_to_delete = $_GET['id'];
    
    $sql = "DELETE FROM students WHERE student_id = ?";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("s", $student_id_to_delete);
        if ($stmt->execute()) {
            $feedback_message = "Student record deleted successfully!";
            $feedback_class = "alert-success";
        } else {
            $feedback_message = "Error deleting record. Please try again.";
            $feedback_class = "alert-danger";
        }
        $stmt->close();
    }
}

// --- ACTION: ADD NEW STUDENT ---
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_student'])) {
    $student_id = trim($_POST['student_id']);
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);

    if (empty($student_id) || empty($name) || empty($email)) {
        $feedback_message = "Student ID, Name, and Email are required.";
        $feedback_class = "alert-danger";
    } else {
        // Check if student ID or email already exists
        $sql_check = "SELECT student_id FROM students WHERE student_id = ? OR email = ?";
        if ($stmt_check = $conn->prepare($sql_check)) {
            $stmt_check->bind_param("ss", $student_id, $email);
            $stmt_check->execute();
            $stmt_check->store_result();

            if ($stmt_check->num_rows > 0) {
                $feedback_message = "A student with this ID or Email already exists.";
                $feedback_class = "alert-danger";
            } else {
                // Insert new student
                $sql_insert = "INSERT INTO students (student_id, name, email, phone) VALUES (?, ?, ?, ?)";
                if ($stmt_insert = $conn->prepare($sql_insert)) {
                    $stmt_insert->bind_param("ssss", $student_id, $name, $email, $phone);
                    if ($stmt_insert->execute()) {
                        $feedback_message = "New student added successfully!";
                        $feedback_class = "alert-success";
                    } else {
                        $feedback_message = "Error: Could not add the student.";
                        $feedback_class = "alert-danger";
                    }
                    $stmt_insert->close();
                }
            }
            $stmt_check->close();
        }
    }
}

// --- DATA FETCH: Get all students ---
$students = [];
$sql_fetch = "SELECT student_id, name, email, phone FROM students ORDER BY student_id ASC";
if ($result = $conn->query($sql_fetch)) {
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $students[] = $row;
        }
    }
    $result->free();
}
$conn->close();
?>

<!-- Add Student Modal (Pop-up Form) -->
<div class="modal fade" id="addStudentModal" tabindex="-1" aria-labelledby="addStudentModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addStudentModalLabel">Add New Student</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="students.php" method="post">
                    <div class="mb-3">
                        <label for="student_id" class="form-label">Student ID</label>
                        <input type="text" class="form-control" name="student_id" required>
                    </div>
                    <div class="mb-3">
                        <label for="name" class="form-label">Full Name</label>
                        <input type="text" class="form-control" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email Address</label>
                        <input type="email" class="form-control" name="email" required>
                    </div>
                     <div class="mb-3">
                        <label for="department" class="form-label">Department</label>
                        <select class="form-select" name="department" required>
                            <option value="">Select Department</option>
                            <option value="CSE">Computer Science & Engineering</option>
                            <option value="EEE">Electrical & Electronic Engineering</option>
                            <option value="BBA">Business Administration</option>
                            <option value="CE">Civil Engineering</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="semester" class="form-label">Semester</label>
                        <input type="number" class="form-control" name="semester" min="1" max="8" required>
                    </div>
                    <div class="mb-3">
                        <label for="phone" class="form-label">Phone Number</label>
                        <input type="text" class="form-control" name="phone">
                    </div>
                                        <div class="mb-3">
                        <label for="blood_group" class="form-label">Blood Group</label>
                        <select class="form-select" name="blood_group" required>
                            <option value="">Select Group</option>
                            <option value="A+">A+</option>
                            <option value="A-">A-</option>
                            <option value="B+">B+</option>
                            <option value="B-">B-</option>
                            <option value="O+">O+</option>
                            <option value="O-">O-</option>
                            <option value="AB+">AB+</option>
                            <option value="AB-">AB-</option>
                        </select>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" name="add_student" class="btn btn-primary">Save Student</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Main Content: Student List -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Student Records</h2>
    <!-- This button now opens the modal -->
    <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addStudentModal">
        Add New Student
    </button>
</div>

<!-- Feedback Message Display -->
<?php if ($feedback_message): ?>
<div class="alert <?php echo $feedback_class; ?> alert-dismissible fade show" role="alert">
    <?php echo $feedback_message; ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
<?php endif; ?>

<div class="card shadow-sm">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped table-hover align-middle">
                <thead class="table-dark">
                    <tr>
                        <th>Student ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($students)): ?>
                        <?php foreach ($students as $student): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($student['student_id']); ?></td>
                            <td><?php echo htmlspecialchars($student['name']); ?></td>
                            <td><?php echo htmlspecialchars($student['email']); ?></td>
                            <td><?php echo htmlspecialchars($student['phone']); ?></td>
                            <td>
                                <a href="edit_student.php?id=<?php echo htmlspecialchars($student['student_id']); ?>" class="btn btn-sm btn-outline-warning" title="Edit">
        <i class="fas fa-edit"></i>
    </a>
    <a href="students.php?action=delete&id=<?php echo htmlspecialchars($student['student_id']); ?>" class="btn btn-sm btn-outline-danger" title="Delete" onclick="return confirm('Are you sure you want to delete this student?');">
        <i class="fas fa-trash-alt"></i>
    </a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5" class="text-center">No student records found. Add one to get started!</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>
