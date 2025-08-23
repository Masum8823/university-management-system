<?php
$page_title = "Notice Board";
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

// --- ACTION: DELETE NOTICE ---
if (isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['id'])) {
    $notice_id_to_delete = $_GET['id'];
    $sql = "DELETE FROM notices WHERE id = ?";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("i", $notice_id_to_delete);
        if ($stmt->execute()) {
            $feedback_message = "Notice deleted successfully!";
            $feedback_class = "alert-success";
        } else {
            $feedback_message = "Error deleting notice.";
            $feedback_class = "alert-danger";
        }
        $stmt->close();
    }
}

// --- ACTION: ADD NEW NOTICE ---
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_notice'])) {
    $title = trim($_POST['title']);
    $details = trim($_POST['details']);
    $notice_date = trim($_POST['notice_date']);

    if (empty($title) || empty($details) || empty($notice_date)) {
        $feedback_message = "Title, Details, and Notice Date are required.";
        $feedback_class = "alert-danger";
    } else {
        $sql_insert = "INSERT INTO notices (title, details, notice_date) VALUES (?, ?, ?)";
        if ($stmt_insert = $conn->prepare($sql_insert)) {
            $stmt_insert->bind_param("sss", $title, $details, $notice_date);
            if ($stmt_insert->execute()) {
                $feedback_message = "New notice published successfully!";
                $feedback_class = "alert-success";
            } else {
                $feedback_message = "Error publishing notice.";
                $feedback_class = "alert-danger";
            }
            $stmt_insert->close();
        }
    }
}

// --- DATA FETCH: Get all notices, newest first ---
$notices = [];
$sql_fetch = "SELECT id, title, details, notice_date FROM notices ORDER BY notice_date DESC";
if ($result = $conn->query($sql_fetch)) {
    while ($row = $result->fetch_assoc()) {
        $notices[] = $row;
    }
    $result->free();
}
$conn->close();
?>

<!-- Add Notice Modal -->
<div class="modal fade" id="addNoticeModal" tabindex="-1" aria-labelledby="addNoticeModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addNoticeModalLabel">Add New Notice</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="noticeboard.php" method="post">
                    <div class="mb-3">
                        <label for="title" class="form-label">Notice Title</label>
                        <input type="text" class="form-control" name="title" required>
                    </div>
                    <div class="mb-3">
                        <label for="details" class="form-label">Details</label>
                        <textarea class="form-control" name="details" rows="5" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="notice_date" class="form-label">Notice Date</label>
                        <input type="date" class="form-control" name="notice_date" value="<?php echo date('Y-m-d'); ?>" required>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" name="add_notice" class="btn btn-primary">Publish Notice</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Main Content: Notice List -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Academic Notices</h2>
    <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addNoticeModal">
        Add New Notice
    </button>
</div>

<!-- Feedback Message Display -->
<?php if ($feedback_message): ?>
<div class="alert <?php echo $feedback_class; ?> alert-dismissible fade show" role="alert">
    <?php echo $feedback_message; ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
<?php endif; ?>

<?php if (!empty($notices)): ?>
    <?php foreach ($notices as $notice): ?>
    <div class="card shadow-sm mb-3">
        <div class="card-header d-flex justify-content-between">
            <span class="fw-bold"><?php echo htmlspecialchars($notice['title']); ?></span>
            <span class="text-muted">Date: <?php echo date("d M, Y", strtotime($notice['notice_date'])); ?></span>
        </div>
        <div class="card-body">
            <p class="card-text"><?php echo nl2br(htmlspecialchars($notice['details'])); ?></p>
            <div class="text-end">
                 <a href="edit_notice.php?id=<?php echo $notice['id']; ?>" class="btn btn-sm btn-outline-warning" title="Edit">
        <i class="fas fa-edit"></i> Edit
     </a>
     <a href="noticeboard.php?action=delete&id=<?php echo $notice['id']; ?>" class="btn btn-sm btn-outline-danger" title="Delete" onclick="return confirm('Are you sure you want to delete this notice?');">
        <i class="fas fa-trash-alt"></i> Delete
     </a>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
<?php else: ?>
    <div class="alert alert-info">No notices found on the board.</div>
<?php endif; ?>

<?php include 'footer.php'; ?>