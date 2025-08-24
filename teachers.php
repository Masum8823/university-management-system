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

// --- DATA FETCH: Get all teachers with Search and Filter ---
$teachers = [];
$search_term = isset($_GET['search']) ? trim($_GET['search']) : '';
$sort_order = isset($_GET['sort']) ? $_GET['sort'] : 'teacher_id_asc';

// Base SQL query
$sql_fetch = "SELECT teacher_id, name, department, email, phone FROM teachers WHERE 1";

// Append search condition
$params = [];
$types = '';
if (!empty($search_term)) {
    $sql_fetch .= " AND (name LIKE ? OR teacher_id LIKE ? OR email LIKE ?)";
    $like_term = "%" . $search_term . "%";
    $params[] = &$like_term;
    $params[] = &$like_term;
    $params[] = &$like_term;
    $types .= 'sss';
}

// Append sorting condition
switch ($sort_order) {
    case 'name_asc':
        $sql_fetch .= " ORDER BY name ASC";
        break;
    case 'name_desc':
        $sql_fetch .= " ORDER BY name DESC";
        break;
    case 'teacher_id_desc':
        $sql_fetch .= " ORDER BY teacher_id DESC";
        break;
    default:
        $sql_fetch .= " ORDER BY teacher_id ASC";
        break;
}

// Prepare and execute the statement
if ($stmt_fetch = $conn->prepare($sql_fetch)) {
    if (!empty($params)) {
        $stmt_fetch->bind_param($types, ...$params);
    }
    $stmt_fetch->execute();
    $result = $stmt_fetch->get_result();
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $teachers[] = $row;
        }
    }
    $stmt_fetch->close();
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
        <i class="fas fa-plus"></i> Add New Teacher
    </button>
</div>

<!-- Search and Filter Bar -->
<div class="card mb-4">
    <div class="card-body">
        <form action="teachers.php" method="GET" class="row g-3 align-items-center">
            <div class="col-md-6">
                <input type="text" name="search" class="form-control" placeholder="Search by Name, ID, or Email..." value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
            </div>
            <div class="col-md-4">
                <select name="sort" class="form-select">
                    <option value="teacher_id_asc" <?php if (isset($_GET['sort']) && $_GET['sort'] == 'teacher_id_asc') echo 'selected'; ?>>Sort by ID (Asc)</option>
                    <option value="teacher_id_desc" <?php if (isset($_GET['sort']) && $_GET['sort'] == 'teacher_id_desc') echo 'selected'; ?>>Sort by ID (Desc)</option>
                    <option value="name_asc" <?php if (isset($_GET['sort']) && $_GET['sort'] == 'name_asc') echo 'selected'; ?>>Sort by Name (A-Z)</option>
                    <option value="name_desc" <?php if (isset($_GET['sort']) && $_GET['sort'] == 'name_desc') echo 'selected'; ?>>Sort by Name (Z-A)</option>
                </select>
            </div>
            <div class="col-md-2 d-grid">
                <button type="submit" class="btn btn-primary">Filter</button>
            </div>
        </form>
    </div>
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
                                <!-- View Button -->
                                <button type="button" class="btn btn-sm btn-outline-info view-btn-teacher"
                                        data-bs-toggle="modal"
                                        data-bs-target="#viewTeacherModal"
                                        data-id="<?php echo htmlspecialchars($teacher['teacher_id']); ?>"
                                        data-name="<?php echo htmlspecialchars($teacher['name']); ?>"
                                        data-department="<?php echo htmlspecialchars($teacher['department']); ?>"
                                        data-email="<?php echo htmlspecialchars($teacher['email']); ?>"
                                        data-phone="<?php echo htmlspecialchars($teacher['phone']); ?>"
                                        title="View Details">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <!-- Edit Button -->
                                <a href="edit_teacher.php?id=<?php echo htmlspecialchars($teacher['teacher_id']); ?>" class="btn btn-sm btn-outline-warning" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <!-- Delete Button -->
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

<!-- View Teacher Details Modal -->
<div class="modal fade" id="viewTeacherModal" tabindex="-1" aria-labelledby="viewTeacherModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="viewTeacherModalLabel">Teacher Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Basic teacher info table -->
                <table class="table table-bordered">
                    <tr>
                        <th>Teacher ID</th>
                        <td id="modal_teacher_id"></td>
                    </tr>
                    <tr>
                        <th>Name</th>
                        <td id="modal_teacher_name"></td>
                    </tr>
                    <tr>
                        <th>Department</th>
                        <td id="modal_teacher_department"></td>
                    </tr>
                    <tr>
                        <th>Email</th>
                        <td id="modal_teacher_email"></td>
                    </tr>
                    <tr>
                        <th>Phone</th>
                        <td id="modal_teacher_phone"></td>
                    </tr>
                </table>
                <!-- Section for showing assigned courses -->
                <hr>
                <h5 class="mt-3">Assigned Courses:</h5>
                <div id="modal_teacher_courses">
                    <!-- Course list will be loaded here by JavaScript -->
                </div>
            </div>
            <div class="modal-footer">
                <a href="#" id="export_teacher_pdf_btn" class="btn btn-danger" target="_blank"><i class="fas fa-file-pdf"></i> Export as PDF</a>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>
