    </div> <!-- Closes the container-fluid div from header.php -->
</div> <!-- Closes the main-content div from header.php -->

<footer class="text-center mt-5 py-3 bg-light">
    <p>© <?php echo date("Y"); ?> University Management System. All Rights Reserved.</p>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
// This function runs after the entire page is loaded
document.addEventListener('DOMContentLoaded', function () {

    // --- JavaScript for the Student Details Modal ---
    var viewStudentModal = document.getElementById('viewStudentModal');
    // Check if the student modal exists on the current page to avoid errors
    if (viewStudentModal) {
        viewStudentModal.addEventListener('show.bs.modal', function (event) {
            // Button that triggered the modal
            var button = event.relatedTarget;
            
            // Extract information from data-* attributes of the button
            var id = button.getAttribute('data-id');
            var name = button.getAttribute('data-name');
            var email = button.getAttribute('data-email');
            var phone = button.getAttribute('data-phone');
            var department = button.getAttribute('data-department');
            var semester = button.getAttribute('data-semester');
            var blood = button.getAttribute('data-blood');
            
            // Update the content inside the modal
            var modal = this;
            modal.querySelector('#modal_student_id').textContent = id;
            modal.querySelector('#modal_name').textContent = name;
            modal.querySelector('#modal_email').textContent = email;
            modal.querySelector('#modal_phone').textContent = phone;
            modal.querySelector('#modal_department').textContent = department;
            modal.querySelector('#modal_semester').textContent = semester;
            modal.querySelector('#modal_blood').textContent = blood;
            
            // Update the href attribute of the PDF export button
            var pdfBtn = modal.querySelector('#export_pdf_btn');
            pdfBtn.href = 'generate_student_pdf.php?id=' + id;
        });
    }

    // --- JavaScript for the Teacher Details Modal (UPDATED with AJAX) ---
    var viewTeacherModal = document.getElementById('viewTeacherModal');
    // Check if the teacher modal exists on the current page
    if (viewTeacherModal) {
        viewTeacherModal.addEventListener('show.bs.modal', function (event) {
            // Button that triggered the modal
            var button = event.relatedTarget;
            
            // Extract basic info from data-* attributes
            var id = button.getAttribute('data-id');
            var name = button.getAttribute('data-name');
            var department = button.getAttribute('data-department');
            var email = button.getAttribute('data-email');
            var phone = button.getAttribute('data-phone');
            
            // Update the basic info in the modal
            var modal = this;
            modal.querySelector('#modal_teacher_id').textContent = id;
            modal.querySelector('#modal_teacher_name').textContent = name;
            modal.querySelector('#modal_teacher_department').textContent = department;
            modal.querySelector('#modal_teacher_email').textContent = email;
            modal.querySelector('#modal_teacher_phone').textContent = phone;
            
            // Update the PDF export button link
            var pdfBtn = modal.querySelector('#export_teacher_pdf_btn');
            pdfBtn.href = 'generate_teacher_pdf.php?id=' + id;

            // New AJAX part to fetch and display assigned courses
            var courseListDiv = modal.querySelector('#modal_teacher_courses');
            // Show a loading message initially
            courseListDiv.innerHTML = '<div class="text-center"><div class="spinner-border spinner-border-sm" role="status"><span class="visually-hidden">Loading...</span></div> <em>Loading courses...</em></div>';

            // Fetch course data from the server
            fetch('get_teacher_courses.php?id=' + id)
                .then(response => response.text()) // Get the response as HTML text
                .then(html => {
                    // Display the fetched HTML in our placeholder div
                    courseListDiv.innerHTML = html;
                })
                .catch(error => {
                    console.error('Error fetching courses:', error);
                    courseListDiv.innerHTML = '<div class="alert alert-danger">Failed to load course data.</div>';
                });
        });
    }

});
</script>

</body>
</html>
