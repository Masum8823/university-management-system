<?php
$page_title = "Edit Teacher";
include 'header.php';
require_once 'db_connect.php';

$teacher_id_to_edit = '';
$name = '';
$department = '';
$email = '';
$phone = '';
$error_message = '';

// --- ACTION: HANDLE FORM SUBMISSION (UPDATE) ---
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_teacher'])) {
    $teacher_id = trim($_POST['teacher_id']);
    $name = trim($_POST['name']);
    $department = trim($_POST['department']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);

    if (empty($name) || empty($department) || empty($email)) {
        $error_message = "Name, Department and Email cannot be empty.";
    } else {
        $sql = "UPDATE teachers SET name = ?, department = ?, email = ?, phone = ? WHERE teacher_id = ?";
        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param("sssss", $name, $department, $email, $phone, $teacher_id);
            
            if ($stmt->execute()) {
                $_SESSION['feedback_message'] = "Teacher record for ID '{$teacher_id}' updated successfully!";
                $_SESSION['feedback_class'] = "alert-success";
                header("location: teachers.php");
                exit;
            } else {
                $error_message = "Error updating record. Please try again.";
            }
            $stmt->close();
        }
    }
}

// --- DATA FETCH: GET TEACHER DETAILS FOR EDITING ---
if (isset($_GET['id'])) {
    $teacher_id_to_edit = $_GET['id'];
    $sql = "SELECT name, department, email, phone FROM teachers WHERE teacher_id = ?";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("s", $teacher_id_to_edit);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows == 1) {
            $stmt->bind_result($name, $department, $email, $phone);
            $stmt->fetch();
        } else {
            $error_message = "No teacher found with this ID.";
        }
        $stmt->close();
    }
} else {
    header("location: teachers.php");
    exit;
}

$conn->close();
?>

<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card shadow-sm">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4 class="mb-0">Edit Teacher Record</h4>
                <a href="teachers.php" class="btn btn-sm btn-secondary">Back to List</a>
            </div>
            <div class="card-body">
                <?php if ($error_message): ?>
                    <div class="alert alert-danger"><?php echo $error_message; ?></div>
                <?php endif; ?>

                <?php if (!empty($teacher_id_to_edit) && empty($error_message)): ?>
                <form action="edit_teacher.php" method="post">
                    <input type="hidden" name="teacher_id" value="<?php echo htmlspecialchars($teacher_id_to_edit); ?>">
                    
                    <div class="mb-3">
                        <label class="form-label">Teacher ID</label>
                        <input type="text" class="form-control" value="<?php echo htmlspecialchars($teacher_id_to_edit); ?>" readonly>
                    </div>
                    <div class="mb-3">
                        <label for="name" class="form-label">Full Name</label>
                        <input type="text" class="form-control" id="name" name="name" value="<?php echo htmlspecialchars($name); ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="department" class="form-label">Department</label>
                        <input type="text" class="form-control" id="department" name="department" value="<?php echo htmlspecialchars($department); ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email Address</label>
                        <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($email); ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="phone" class="form-label">Phone Number</label>
                        <input type="text" class="form-control" id="phone" name="phone" value="<?php echo htmlspecialchars($phone); ?>">
                    </div>
                    <div class="text-end">
                        <button type="submit" name="update_teacher" class="btn btn-primary">Update Teacher</button>
                    </div>
                </form>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>