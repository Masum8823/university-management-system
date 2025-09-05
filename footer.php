    </div> <!-- Closes the container-fluid div from header.php -->
</div> <!-- Closes the main-content div from header.php -->

<footer class="text-center mt-5 py-3 bg-light">
    <p>© <?php echo date("Y"); ?> University Management System. All Rights Reserved.</p>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
// This function runs after the entire page is loaded
document.addEventListener('DOMContentLoaded', function () {

    // --- JavaScript for the Student Details Modal (with AJAX for courses) ---
    var viewStudentModal = document.getElementById('viewStudentModal');
    if (viewStudentModal) {
        viewStudentModal.addEventListener('show.bs.modal', function (event) {
            var button = event.relatedTarget;
            var id = button.getAttribute('data-id');
            var name = button.getAttribute('data-name');
            var email = button.getAttribute('data-email');
            var phone = button.getAttribute('data-phone');
            var department = button.getAttribute('data-department');
            var semester = button.getAttribute('data-semester');
            var blood = button.getAttribute('data-blood');
            var modal = this;
            modal.querySelector('#modal_student_id').textContent = id;
            modal.querySelector('#modal_name').textContent = name;
            modal.querySelector('#modal_email').textContent = email;
            modal.querySelector('#modal_phone').textContent = phone;
            modal.querySelector('#modal_department').textContent = department;
            modal.querySelector('#modal_semester').textContent = semester;
            modal.querySelector('#modal_blood').textContent = blood;
            var pdfBtn = modal.querySelector('#export_pdf_btn');
            pdfBtn.href = 'generate_student_pdf.php?id=' + id;
            var courseListDiv = modal.querySelector('#modal_student_courses');
            courseListDiv.innerHTML = '<div class="text-center"><div class="spinner-border spinner-border-sm" role="status"><span class="visually-hidden">Loading...</span></div> <em>Loading courses...</em></div>';
            fetch('get_student_courses.php?id=' + id)
                .then(response => response.text())
                .then(html => { courseListDiv.innerHTML = html; })
                .catch(error => { console.error('Error fetching courses:', error); courseListDiv.innerHTML = '<div class="alert alert-danger">Failed to load course data.</div>'; });
        });
    }

    // --- JavaScript for the Teacher Details Modal (with AJAX for courses) ---
    var viewTeacherModal = document.getElementById('viewTeacherModal');
    if (viewTeacherModal) {
        viewTeacherModal.addEventListener('show.bs.modal', function (event) {
            var button = event.relatedTarget;
            var id = button.getAttribute('data-id');
            var name = button.getAttribute('data-name');
            var department = button.getAttribute('data-department');
            var email = button.getAttribute('data-email');
            var phone = button.getAttribute('data-phone');
            var modal = this;
            modal.querySelector('#modal_teacher_id').textContent = id;
            modal.querySelector('#modal_teacher_name').textContent = name;
            modal.querySelector('#modal_teacher_department').textContent = department;
            modal.querySelector('#modal_teacher_email').textContent = email;
            modal.querySelector('#modal_teacher_phone').textContent = phone;
            var pdfBtn = modal.querySelector('#export_teacher_pdf_btn');
            pdfBtn.href = 'generate_teacher_pdf.php?id=' + id;
            var courseListDiv = modal.querySelector('#modal_teacher_courses');
            courseListDiv.innerHTML = '<div class="text-center"><div class="spinner-border spinner-border-sm" role="status"><span class="visually-hidden">Loading...</span></div> <em>Loading courses...</em></div>';
            fetch('get_teacher_courses.php?id=' + id)
                .then(response => response.text())
                .then(html => { courseListDiv.innerHTML = html; })
                .catch(error => { console.error('Error fetching courses:', error); courseListDiv.innerHTML = '<div class="alert alert-danger">Failed to load course data.</div>'; });
        });
    }

    // --- JavaScript for the Admin Reset Password Modal ---
    var resetPasswordModal = document.getElementById('resetPasswordModal');
    if (resetPasswordModal) {
        resetPasswordModal.addEventListener('show.bs.modal', function (event) {
            var button = event.relatedTarget;
            var adminId = button.getAttribute('data-id');
            var adminName = button.getAttribute('data-name');
            var modal = this;
            modal.querySelector('#resetAdminName').textContent = adminName;
            modal.querySelector('#admin_id_reset').value = adminId;
        });
    }
    
    // #############################################################
    // ### New JavaScript for the Notice Details Modal            ###
    // #############################################################
    var viewNoticeModal = document.getElementById('viewNoticeModal');
    if (viewNoticeModal) {
        viewNoticeModal.addEventListener('show.bs.modal', function (event) {
            // Button that triggered the modal
            var button = event.relatedTarget;
            
            // Extract info from data-* attributes
            var title = button.getAttribute('data-title');
            var date = button.getAttribute('data-date');
            var details = button.getAttribute('data-details');
            
            // Update the modal's content
            var modal = this;
            modal.querySelector('#modal_notice_title').textContent = title;
            modal.querySelector('#modal_notice_date').textContent = "Date: " + date;
            modal.querySelector('#modal_notice_details').textContent = details;
        });
    }

});
</script>

</body>
</html>