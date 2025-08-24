</div> <!-- End of container-fluid -->
</div> <!-- End of main-content -->

<footer class="text-center mt-5 py-3 bg-light">
    <p>© <?php echo date("Y"); ?> University Management System. All Rights Reserved.</p>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    var viewStudentModal = document.getElementById('viewStudentModal');
    viewStudentModal.addEventListener('show.bs.modal', function (event) {
        var button = event.relatedTarget; // Button that triggered the modal
        
        // Extract info from data-* attributes
        var id = button.getAttribute('data-id');
        var name = button.getAttribute('data-name');
        var email = button.getAttribute('data-email');
        var phone = button.getAttribute('data-phone');
        var department = button.getAttribute('data-department');
        var semester = button.getAttribute('data-semester');
        var blood = button.getAttribute('data-blood');
        
        // Update the modal's content.
        var modal = this;
        modal.querySelector('#modal_student_id').textContent = id;
        modal.querySelector('#modal_name').textContent = name;
        modal.querySelector('#modal_email').textContent = email;
        modal.querySelector('#modal_phone').textContent = phone;
        modal.querySelector('#modal_department').textContent = department;
        modal.querySelector('#modal_semester').textContent = semester;
        modal.querySelector('#modal_blood').textContent = blood;
        
        // Update the PDF export button link
        var pdfBtn = modal.querySelector('#export_pdf_btn');
        pdfBtn.href = 'generate_student_pdf.php?id=' + id;
    });
});
</script>
</body>
</html>
