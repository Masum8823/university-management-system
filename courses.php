<?php
$page_title = "Course Management";
include 'header.php';
require_once 'db_connect.php';

// Check for feedback messages from session
if (isset($_SESSION['feedback_message'])) {
    $feedback_message = $_SESSION['feedback_message'];
    $feedback_class = $_SESSION['feedback_class'];
    unset($_SESSION['feedback_message'], $_SESSION['feedback_class']);
} else {
    $feedback_message = '';
    $feedback_class = '';
}

// --- ACTION: DELETE COURSE ---
if (isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['code'])) {
    $course_code_to_delete = $_GET['code'];
    $sql = "DELETE FROM courses WHERE course_code = ?";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("s", $course_code_to_delete);
        if ($stmt->execute()) {
            $feedback_message = "Course deleted successfully!";
            $feedback_class = "alert-success";
        } else {
            $feedback_message = "Error deleting course.";
            $feedback_class = "alert-danger";
        }
        $stmt->close();
    }
}

// --- ACTION: ADD NEW COURSE ---
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_course'])) {
    $course_code = trim($_POST['course_code']);
    $title = trim($_POST['title']);
    $credits = trim($_POST['credits']);
    $teacher_id = !empty($_POST['teacher_id']) ? trim($_POST['teacher_id']) : null;

    if (empty($course_code) || empty($title) || empty($credits)) {
        $feedback_message = "Course Code, Title, and Credits are required.";
        $feedback_class = "alert-danger";
    } else {
        $sql_check = "SELECT course_code FROM courses WHERE course_code = ?";
        if ($stmt_check = $conn->prepare($sql_check)) {
            $stmt_check->bind_param("s", $course_code);
            $stmt_check->execute();
            $stmt_check->store_result();
            if ($stmt_check->num_rows > 0) {
                $feedback_message = "This Course Code already exists.";
                $feedback_class = "alert-danger";
            } else {
                $sql_insert = "INSERT INTO courses (course_code, title, credits, teacher_id) VALUES (?, ?, ?, ?)";
                if ($stmt_insert = $conn->prepare($sql_insert)) {
                    $stmt_insert->bind_param("ssis", $course_code, $title, $credits, $teacher_id);
                    if ($stmt_insert->execute()) {
                        $feedback_message = "New course added successfully!";
                        $feedback_class = "alert-success";
                    } else {
                        $feedback_message = "Error adding course.";
                        $feedback_class = "alert-danger";
                    }
                    $stmt_insert->close();
                }
            }
            $stmt_check->close();
        }
    }
}

// --- DATA FETCH: Get all courses with teacher names ---
$courses = [];
// Using a LEFT JOIN to get teacher's name from the teachers table
$sql_fetch_courses = "
    SELECT c.course_code, c.title, c.credits, c.teacher_id, t.name as teacher_name
    FROM courses c
    LEFT JOIN teachers t ON c.teacher_id = t.teacher_id
    ORDER BY c.course_code ASC";

if ($result = $conn->query($sql_fetch_courses)) {
    while ($row = $result->fetch_assoc()) {
        $courses[] = $row;
    }
    $result->free();
}

// --- DATA FETCH: Get all teachers for the dropdown menu ---
$teachers = [];
$sql_fetch_teachers = "SELECT teacher_id, name FROM teachers ORDER BY name ASC";
if ($result = $conn->query($sql_fetch_teachers)) {
    while ($row = $result->fetch_assoc()) {
        $teachers[] = $row;
    }
    $result->free();
}

$conn->close();
?>

<!-- Add Course Modal -->
<div class="modal fade" id="addCourseModal" tabindex="-1" aria-labelledby="addCourseModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addCourseModalLabel">Add New Course</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="courses.php" method="post">
                    <div class="mb-3">
                        <label for="course_code" class="form-label">Course Code</label>
                        <input type="text" class="form-control" name="course_code" required>
                    </div>
                    <div class="mb-3">
                        <label for="title" class="form-label">Course Title</label>
                        <input type="text" class="form-control" name="title" required>
                    </div>
                    <div class="mb-3">
                        <label for="credits" class="form-label">Credits</label>
                        <input type="number" class="form-control" name="credits" required>
                    </div>
                    <div class="mb-3">
                        <label for="teacher_id" class="form-label">Assign Teacher</label>
                        <select class="form-select" name="teacher_id">
                            <option value="">-- Select a Teacher --</option>
                            <?php foreach ($teachers as $teacher): ?>
                                <option value="<?php echo htmlspecialchars($teacher['teacher_id']); ?>">
                                    <?php echo htmlspecialchars($teacher['name']) . " (" . htmlspecialchars($teacher['teacher_id']) . ")"; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" name="add_course" class="btn btn-primary">Save Course</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Main Content: Course List -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Course List</h2>
    <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addCourseModal">
        Add New Course
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
                        <th>Course Code</th>
                        <th>Course Title</th>
                        <th>Credits</th>
                        <th>Assigned Teacher</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($courses)): ?>
                        <?php foreach ($courses as $course): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($course['course_code']); ?></td>
                            <td><?php echo htmlspecialchars($course['title']); ?></td>
                            <td><?php echo htmlspecialchars($course['credits']); ?></td>
                            <td>
                                <?php if ($course['teacher_name']): ?>
                                    <?php echo htmlspecialchars($course['teacher_name']); ?>
                                    <small class="text-muted">(<?php echo htmlspecialchars($course['teacher_id']); ?>)</small>
                                <?php else: ?>
                                    <span class="text-muted">Not Assigned</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <a href="edit_course.php?code=<?php echo htmlspecialchars($course['course_code']); ?>" class="btn btn-sm btn-outline-warning" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <a href="courses.php?action=delete&code=<?php echo htmlspecialchars($course['course_code']); ?>" class="btn btn-sm btn-outline-danger" title="Delete" onclick="return confirm('Are you sure you want to delete this course?');">
                                    <i class="fas fa-trash-alt"></i>
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5" class="text-center">No courses found.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>