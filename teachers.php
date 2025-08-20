<?php
$page_title = "Teacher Management";
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

// --- ACTION: DELETE TEACHER ---
if (isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['id'])) {
    $teacher_id_to_delete = $_GET['id'];
    
    $sql = "DELETE FROM teachers WHERE teacher_id = ?";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("s", $teacher_id_to_delete);
        if ($stmt->execute()) {
            $feedback_message = "Teacher record deleted successfully!";
            $feedback_class = "alert-success";
        } else {
            $feedback_message = "Error deleting record.";
            $feedback_class = "alert-danger";
        }
        $stmt->close();
    }
}

// --- ACTION: ADD NEW TEACHER ---
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_teacher'])) {
    $teacher_id = trim($_POST['teacher_id']);
    $name = trim($_POST['name']);
    $department = trim($_POST['department']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);

    if (empty($teacher_id) || empty($name) || empty($department) || empty($email)) {
        $feedback_message = "Teacher ID, Name, Department, and Email are required.";
        $feedback_class = "alert-danger";
    } else {
        $sql_check = "SELECT teacher_id FROM teachers WHERE teacher_id = ? OR email = ?";
        if ($stmt_check = $conn->prepare($sql_check)) {
            $stmt_check->bind_param("ss", $teacher_id, $email);
            $stmt_check->execute();
            $stmt_check->store_result();

            if ($stmt_check->num_rows > 0) {
                $feedback_message = "A teacher with this ID or Email already exists.";
                $feedback_class = "alert-danger";
            } else {
                $sql_insert = "INSERT INTO teachers (teacher_id, name, department, email, phone) VALUES (?, ?, ?, ?, ?)";
                if ($stmt_insert = $conn->prepare($sql_insert)) {
                    $stmt_insert->bind_param("sssss", $teacher_id, $name, $department, $email, $phone);
                    if ($stmt_insert->execute()) {
                        $feedback_message = "New teacher added successfully!";
                        $feedback_class = "alert-success";
                    } else {
                        $feedback_message = "Error: Could not add the teacher.";
                        $feedback_class = "alert-danger";
                    }
                    $stmt_insert->close();
                }
            }
            $stmt_check->close();
        }
    }
}

// --- DATA FETCH: Get all teachers ---
$teachers = [];
$sql_fetch = "SELECT teacher_id, name, department, email, phone FROM teachers ORDER BY name ASC";
if ($result = $conn->query($sql_fetch)) {
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $teachers[] = $row;
        }
    }
    $result->free();
}
$conn->close();
?>

<!-- Add Teacher Modal -->
<div class="modal fade" id="addTeacherModal" tabindex="-1" aria-labelledby="addTeacherModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addTeacherModalLabel">Add New Teacher</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="teachers.php" method="post">
                    <div class="mb-3">
                        <label for="teacher_id" class="form-label">Teacher ID</label>
                        <input type="text" class="form-control" name="teacher_id" required>
                    </div>
                    <div class="mb-3">
                        <label for="name" class="form-label">Full Name</label>
                        <input type="text" class="form-control" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="department" class="form-label">Department</label>
                        <input type="text" class="form-control" name="department" required>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email Address</label>
                        <input type="email" class="form-control" name="email" required>
                    </div>
                    <div class="mb-3">
                        <label for="phone" class="form-label">Phone Number</label>
                        <input type="text" class="form-control" name="phone">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" name="add_teacher" class="btn btn-primary">Save Teacher</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Main Content: Teacher List -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Teacher Records</h2>
    <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addTeacherModal">
        Add New Teacher
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
                        <th>ID</th>
                        <th>Name</th>
                        <th>Department</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($teachers)): ?>
                        <?php foreach ($teachers as $teacher): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($teacher['teacher_id']); ?></td>
                            <td><?php echo htmlspecialchars($teacher['name']); ?></td>
                            <td><?php echo htmlspecialchars($teacher['department']); ?></td>
                            <td><?php echo htmlspecialchars($teacher['email']); ?></td>
                            <td><?php echo htmlspecialchars($teacher['phone']); ?></td>
                            <td>
                            <a href="edit_teacher.php?id=<?php echo htmlspecialchars($teacher['teacher_id']); ?>" class="btn btn-sm btn-outline-warning" title="Edit">
                                <i class="fas fa-edit"></i>
                            </a>
                            <a href="teachers.php?action=delete&id=<?php echo htmlspecialchars($teacher['teacher_id']); ?>" class="btn btn-sm btn-outline-danger" title="Delete" onclick="return confirm('Are you sure you want to delete this teacher?');">
                                <i class="fas fa-trash-alt"></i>
                            </a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" class="text-center">No teacher records found.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>