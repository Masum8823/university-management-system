<?php
$page_title = "Edit Student";
include 'header.php';
require_once 'db_connect.php';

$student_id_to_edit = '';
$name = '';
$email = '';
$phone = '';
$error_message = '';

// --- ACTION: HANDLE FORM SUBMISSION (UPDATE) ---
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_student'])) {
    $student_id = trim($_POST['student_id']);
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);

    if (empty($name) || empty($email)) {
        $error_message = "Name and Email cannot be empty.";
    } else {
        $sql = "UPDATE students SET name = ?, email = ?, phone = ? WHERE student_id = ?";
        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param("ssss", $name, $email, $phone, $student_id);
            
            if ($stmt->execute()) {
                // Set feedback message in session to show on the students list page
                $_SESSION['feedback_message'] = "Student record for ID '{$student_id}' updated successfully!";
                $_SESSION['feedback_class'] = "alert-success";
                
                // Redirect back to the student list
                header("location: students.php");
                exit;
            } else {
                $error_message = "Error updating record. Please try again.";
            }
            $stmt->close();
        }
    }
}

// --- DATA FETCH: GET STUDENT DETAILS FOR EDITING ---
if (isset($_GET['id'])) {
    $student_id_to_edit = $_GET['id'];
    $sql = "SELECT name, email, phone FROM students WHERE student_id = ?";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("s", $student_id_to_edit);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows == 1) {
            $stmt->bind_result($name, $email, $phone);
            $stmt->fetch();
        } else {
            $error_message = "No student found with this ID.";
        }
        $stmt->close();
    }
} else {
    // If no ID is provided in URL, redirect back
    header("location: students.php");
    exit;
}

$conn->close();
?>

<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card shadow-sm">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4 class="mb-0">Edit Student Record</h4>
                <a href="students.php" class="btn btn-sm btn-secondary">Back to List</a>
            </div>
            <div class="card-body">
                <?php if ($error_message): ?>
                    <div class="alert alert-danger"><?php echo $error_message; ?></div>
                <?php endif; ?>

                <?php if (!empty($student_id_to_edit) && empty($error_message)): ?>
                <form action="edit_student.php" method="post">
                    <!-- Hidden input to pass the student ID during POST -->
                    <input type="hidden" name="student_id" value="<?php echo htmlspecialchars($student_id_to_edit); ?>">
                    
                    <div class="mb-3">
                        <label class="form-label">Student ID</label>
                        <!-- Student ID is the primary key, so we don't allow editing it. -->
                        <input type="text" class="form-control" value="<?php echo htmlspecialchars($student_id_to_edit); ?>" readonly>
                    </div>
                    <div class="mb-3">
                        <label for="name" class="form-label">Full Name</label>
                        <input type="text" class="form-control" id="name" name="name" value="<?php echo htmlspecialchars($name); ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email Address</label>
                        <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($email); ?>" required>
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
                        <input type="text" class="form-control" id="phone" name="phone" value="<?php echo htmlspecialchars($phone); ?>">
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
                    <div class="text-end">
                        <button type="submit" name="update_student" class="btn btn-primary">Update Student</button>
                    </div>
                </form>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>
