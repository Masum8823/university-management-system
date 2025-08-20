<?php
$page_title = "Admin Management";
include 'header.php';
require_once 'db_connect.php';

$feedback_message = '';
$feedback_class = '';

// Handle new admin creation
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_admin'])) {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    if (empty($username) || empty($password)) {
        $feedback_message = "Username and password cannot be empty.";
        $feedback_class = "alert-danger";
    } else {
        // Check if username already exists
        $sql_check = "SELECT id FROM admins WHERE username = ?";
        if ($stmt_check = $conn->prepare($sql_check)) {
            $stmt_check->bind_param("s", $username);
            $stmt_check->execute();
            $stmt_check->store_result();

            if ($stmt_check->num_rows > 0) {
                $feedback_message = "This username is already taken.";
                $feedback_class = "alert-danger";
            } else {
                // Proceed to insert new admin
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

// Fetch all admins to display
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

<!-- Add New Admin Form -->
<div class="card shadow-sm mb-4">
    <div class="card-header">
        <h4 class="mb-0">Add New Admin</h4>
    </div>
    <div class="card-body">
        <?php if ($feedback_message): ?>
            <div class="alert <?php echo $feedback_class; ?>"><?php echo $feedback_message; ?></div>
        <?php endif; ?>
        <form action="admins.php" method="post">
            <div class="row">
                <div class="col-md-5">
                    <div class="mb-3">
                        <label for="username" class="form-label">Username</label>
                        <input type="text" class="form-control" name="username" id="username" required>
                    </div>
                </div>
                <div class="col-md-5">
                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" class="form-control" name="password" id="password" required>
                    </div>
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <div class="mb-3 d-grid w-100">
                        <button type="submit" name="add_admin" class="btn btn-success">Add Admin</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Existing Admins List -->
<div class="card shadow-sm">
    <div class="card-header">
        <h4 class="mb-0">Existing Admin Users</h4>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead class="table-dark">
    <tr>
        <th><i class="fas fa-id-badge"></i> ID</th>
        <th><i class="fas fa-user"></i> Username</th>
        <th><i class="fas fa-calendar-alt"></i> Created At</th>
    </tr>
</thead>
                <tbody>
                    <?php if (!empty($admins)): ?>
                        <?php foreach ($admins as $admin): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($admin['id']); ?></td>
                            <td><?php echo htmlspecialchars($admin['username']); ?></td>
                            <td><?php echo date("d-M-Y, g:i A", strtotime($admin['created_at'])); ?></td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="3" class="text-center">No admin users found.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>