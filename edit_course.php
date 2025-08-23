<?php
$page_title = "Edit Course";
include 'header.php';
require_once 'db_connect.php';

$course_code_to_edit = '';
$title = '';
$credits = '';
$current_teacher_id = '';
$error_message = '';

// --- ACTION: HANDLE FORM SUBMISSION (UPDATE) ---
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_course'])) {
    $course_code = trim($_POST['course_code']);
    $title = trim($_POST['title']);
    $credits = trim($_POST['credits']);
    $teacher_id = !empty($_POST['teacher_id']) ? trim($_POST['teacher_id']) : null;

    if (empty($title) || empty($credits)) {
        $error_message = "Title and Credits cannot be empty.";
    } else {
        $sql = "UPDATE courses SET title = ?, credits = ?, teacher_id = ? WHERE course_code = ?";
        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param("siss", $title, $credits, $teacher_id, $course_code);
            if ($stmt->execute()) {
                $_SESSION['feedback_message'] = "Course '{$course_code}' updated successfully!";
                $_SESSION['feedback_class'] = "alert-success";
                header("location: courses.php");
                exit;
            } else {
                $error_message = "Error updating course.";
            }
            $stmt->close();
        }
    }
}

// --- DATA FETCH: GET COURSE DETAILS FOR EDITING ---
if (isset($_GET['code'])) {
    $course_code_to_edit = $_GET['code'];
    $sql = "SELECT title, credits, teacher_id FROM courses WHERE course_code = ?";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("s", $course_code_to_edit);
        $stmt->execute();
        $stmt->store_result();
        if ($stmt->num_rows == 1) {
            $stmt->bind_result($title, $credits, $current_teacher_id);
            $stmt->fetch();
        } else {
            $error_message = "No course found with this code.";
        }
        $stmt->close();
    }
} else {
    header("location: courses.php");
    exit;
}

// --- DATA FETCH: Get all teachers for the dropdown ---
$teachers = [];
$sql_fetch_teachers = "SELECT teacher_id, name FROM teachers ORDER BY name ASC";
if ($result = $conn->query($sql_fetch_teachers)) {
    while ($row = $result->fetch_assoc()) {
        $teachers[] = $row;
    }
}

$conn->close();
?>

<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card shadow-sm">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4 class="mb-0">Edit Course</h4>
                <a href="courses.php" class="btn btn-sm btn-secondary">Back to List</a>
            </div>
            <div class="card-body">
                <?php if ($error_message): ?>
                    <div class="alert alert-danger"><?php echo $error_message; ?></div>
                <?php endif; ?>

                <?php if (!empty($course_code_to_edit) && empty($error_message)): ?>
                <form action="edit_course.php" method="post">
                    <input type="hidden" name="course_code" value="<?php echo htmlspecialchars($course_code_to_edit); ?>">
                    
                    <div class="mb-3">
                        <label class="form-label">Course Code</label>
                        <input type="text" class="form-control" value="<?php echo htmlspecialchars($course_code_to_edit); ?>" readonly>
                    </div>
                    <div class="mb-3">
                        <label for="title" class="form-label">Course Title</label>
                        <input type="text" class="form-control" id="title" name="title" value="<?php echo htmlspecialchars($title); ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="credits" class="form-label">Credits</label>
                        <input type="number" class="form-control" id="credits" name="credits" value="<?php echo htmlspecialchars($credits); ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="teacher_id" class="form-label">Assign Teacher</label>
                        <select class="form-select" id="teacher_id" name="teacher_id">
                            <option value="">-- Unassigned --</option>
                            <?php foreach ($teachers as $teacher): ?>
                                <option value="<?php echo htmlspecialchars($teacher['teacher_id']); ?>" <?php if ($teacher['teacher_id'] == $current_teacher_id) echo 'selected'; ?>>
                                    <?php echo htmlspecialchars($teacher['name']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="text-end">
                        <button type="submit" name="update_course" class="btn btn-primary">Update Course</button>
                    </div>
                </form>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>