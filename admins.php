<?php
$page_title = "Admin Management";
include 'header.php';
require_once 'db_connect.php';

$feedback_message = '';
$feedback_class = '';

// --- ACTION: DELETE ADMIN ---
if (isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['id'])) {
    $admin_id_to_delete = $_GET['id'];
    if ($admin_id_to_delete == $_SESSION['id']) {
        $feedback_message = "Error: You cannot delete your own account.";
        $feedback_class = "alert-danger";
    } else {
        $sql = "DELETE FROM admins WHERE id = ?";
        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param("i", $admin_id_to_delete);
            if ($stmt->execute()) {
                $feedback_message = "Admin user deleted successfully!";
                $feedback_class = "alert-success";
            } else {
                $feedback_message = "Error deleting user.";
                $feedback_class = "alert-danger";
            }
            $stmt->close();
        }
    }
}

// --- ACTION: RESET ADMIN PASSWORD ---
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['reset_password'])) {
    $admin_id_to_reset = $_POST['admin_id'];
    $new_password = $_POST['new_password'];
    if (empty($new_password)) {
        $feedback_message = "Password cannot be empty.";
        $feedback_class = "alert-danger";
    } else {
        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
        $sql = "UPDATE admins SET password = ? WHERE id = ?";
        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param("si", $hashed_password, $admin_id_to_reset);
            if ($stmt->execute()) {
                $feedback_message = "Password has been reset successfully!";
                $feedback_class = "alert-success";
            } else {
                $feedback_message = "Error resetting password.";
                $feedback_class = "alert-danger";
            }
            $stmt->close();
        }
    }
}

// --- ACTION: ADD NEW ADMIN ---
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_admin'])) {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    if (empty($username) || empty($password)) {
        $feedback_message = "Username and password cannot be empty.";
        $feedback_class = "alert-danger";
    } else {
        $sql_check = "SELECT id FROM admins WHERE username = ?";
        if ($stmt_check = $conn->prepare($sql_check)) {
            $stmt_check->bind_param("s", $username);
            $stmt_check->execute();
            $stmt_check->store_result();
            if ($stmt_check->num_rows > 0) {
                $feedback_message = "This username is already taken.";
                $feedback_class = "alert-danger";
            } else {
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                $sql_insert = "INSERT INTO admins (username, password) VALUES (?, ?)";
                if ($stmt_insert = $conn->prepare($sql_insert)) {
                    $stmt_insert->bind_param("ss", $username, $hashed_password);
                    if ($stmt_insert->execute()) {
                        $feedback_message = "New admin added successfully!";
                        $feedback_class = "alert-success";
                    } else {
                        $feedback_message = "Something went wrong. Please try again.";
                        $feedback_class = "alert-danger";
                    }
                    $stmt_insert->close();
                }
            }
            $stmt_check->close();
        }
    }
}

// --- DATA FETCH: Get all admins ---
$admins = [];
$sql_fetch = "SELECT id, username, created_at FROM admins ORDER BY username ASC";
if ($result = $conn->query($sql_fetch)) {
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $admins[] = $row;
        }
    }
}
$conn->close();
?>

<!-- Reset Password Modal -->
<div class="modal fade" id="resetPasswordModal" tabindex="-1" aria-labelledby="resetPasswordModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header"><h5 class="modal-title" id="resetPasswordModalLabel">Reset Password</h5><button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button></div>
            <form action="admins.php" method="post">
                <div class="modal-body">
                    <p>You are resetting password for: <strong id="resetAdminName"></strong></p>
                    <input type="hidden" name="admin_id" id="admin_id_reset">
                    <div class="mb-3"><label for="new_password" class="form-label">New Password</label><input type="password" class="form-control" name="new_password" id="new_password" required></div>
                </div>
                <div class="modal-footer"><button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button><button type="submit" name="reset_password" class="btn btn-primary">Save New Password</button></div>
            </form>
        </div>
    </div>
</div>

<!-- Page Header -->
<div class="page-header">
    <h1>Admin Management</h1>
    <p class="lead mb-0">Manage system administrators.</p>
</div>

<!-- Feedback Message Display -->
<?php if ($feedback_message): ?>
<div class="alert <?php echo $feedback_class; ?> alert-dismissible fade show" role="alert">
    <?php echo $feedback_message; ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
<?php endif; ?>

<!-- Add New Admin Widget -->
<div class="widget-box">
    <div class="widget-header">
        <h4 class="widget-title"><i class="fas fa-user-plus"></i> Add New Admin</h4>
    </div>
    <form action="admins.php" method="post" class="p-3">
        <div class="row">
            <div class="col-md-5"><div class="mb-3"><label for="username" class="form-label">Username</label><input type="text" class="form-control" name="username" id="username" required></div></div>
            <div class="col-md-5"><div class="mb-3"><label for="password" class="form-label">Password</label><input type="password" class="form-control" name="password" id="password" required></div></div>
            <div class="col-md-2 d-flex align-items-end"><div class="mb-3 d-grid w-100"><button type="submit" name="add_admin" class="btn btn-success">Add Admin</button></div></div>
        </div>
    </form>
</div>

<!-- Existing Admins List Widget -->
<div class="widget-box">
    <div class="widget-header">
        <h4 class="widget-title"><i class="fas fa-users-cog"></i> Existing Admin Users</h4>
    </div>
    <div class="table-responsive">
        <table class="table table-striped table-hover">
            <thead class="table-dark">
                <tr><th>ID</th><th>Username</th><th>Created At</th><th>Actions</th></tr>
            </thead>
            <tbody>
                <?php if (!empty($admins)): ?>
                    <?php foreach ($admins as $admin): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($admin['id']); ?></td>
                        <td><?php echo htmlspecialchars($admin['username']); ?></td>
                        <td><?php echo date("d-M-Y, g:i A", strtotime($admin['created_at'])); ?></td>
                        <td>
                            <button type="button" class="btn btn-sm btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#resetPasswordModal" data-id="<?php echo htmlspecialchars($admin['id']); ?>" data-name="<?php echo htmlspecialchars($admin['username']); ?>" title="Reset Password"><i class="fas fa-key"></i></button>
                            <a href="admins.php?action=delete&id=<?php echo htmlspecialchars($admin['id']); ?>" class="btn btn-sm btn-outline-danger <?php if ($admin['id'] == $_SESSION['id']) echo 'disabled'; ?>" title="Delete" onclick="return confirm('Are you sure?');"><i class="fas fa-trash-alt"></i></a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="4" class="text-center">No admin users found.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include 'footer.php'; ?>