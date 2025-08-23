<?php
$page_title = "Edit Notice";
include 'header.php';
require_once 'db_connect.php';

$notice_id_to_edit = '';
$title = '';
$details = '';
$notice_date = '';
$error_message = '';

// --- ACTION: HANDLE FORM SUBMISSION (UPDATE) ---
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_notice'])) {
    $id = trim($_POST['id']);
    $title = trim($_POST['title']);
    $details = trim($_POST['details']);
    $notice_date = trim($_POST['notice_date']);

    if (empty($title) || empty($details) || empty($notice_date)) {
        $error_message = "All fields are required.";
    } else {
        $sql = "UPDATE notices SET title = ?, details = ?, notice_date = ? WHERE id = ?";
        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param("sssi", $title, $details, $notice_date, $id);
            if ($stmt->execute()) {
                $_SESSION['feedback_message'] = "Notice updated successfully!";
                $_SESSION['feedback_class'] = "alert-success";
                header("location: noticeboard.php");
                exit;
            } else {
                $error_message = "Error updating notice.";
            }
            $stmt->close();
        }
    }
}

// --- DATA FETCH: GET NOTICE DETAILS FOR EDITING ---
if (isset($_GET['id'])) {
    $notice_id_to_edit = $_GET['id'];
    $sql = "SELECT title, details, notice_date FROM notices WHERE id = ?";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("i", $notice_id_to_edit);
        $stmt->execute();
        $stmt->store_result();
        if ($stmt->num_rows == 1) {
            $stmt->bind_result($title, $details, $notice_date);
            $stmt->fetch();
        } else {
            $error_message = "No notice found with this ID.";
        }
        $stmt->close();
    }
} else {
    header("location: noticeboard.php");
    exit;
}

$conn->close();
?>

<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card shadow-sm">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4 class="mb-0">Edit Notice</h4>
                <a href="noticeboard.php" class="btn btn-sm btn-secondary">Back to Notice Board</a>
            </div>
            <div class="card-body">
                <?php if ($error_message): ?>
                    <div class="alert alert-danger"><?php echo $error_message; ?></div>
                <?php endif; ?>

                <?php if (!empty($notice_id_to_edit) && empty($error_message)): ?>
                <form action="edit_notice.php" method="post">
                    <input type="hidden" name="id" value="<?php echo htmlspecialchars($notice_id_to_edit); ?>">
                    
                    <div class="mb-3">
                        <label for="title" class="form-label">Notice Title</label>
                        <input type="text" class="form-control" id="title" name="title" value="<?php echo htmlspecialchars($title); ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="details" class="form-label">Details</label>
                        <textarea class="form-control" id="details" name="details" rows="8" required><?php echo htmlspecialchars($details); ?></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="notice_date" class="form-label">Notice Date</label>
                        <input type="date" class="form-control" id="notice_date" name="notice_date" value="<?php echo htmlspecialchars($notice_date); ?>" required>
                    </div>
                    <div class="text-end">
                        <button type="submit" name="update_notice" class="btn btn-primary">Update Notice</button>
                    </div>
                </form>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>