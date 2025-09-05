<?php
require_once 'db_connect.php';
require('fpdf/fpdf.php'); // নিশ্চিত করবে যে fpdf ফোল্ডারটি আছে

if (!isset($_GET['id'])) {
    die("Teacher ID not provided.");
}

$teacher_id = $_GET['id'];

// Fetch teacher data
$teacher = null;
$sql = "SELECT * FROM teachers WHERE teacher_id = ?";
if ($stmt = $conn->prepare($sql)) {
    $stmt->bind_param("s", $teacher_id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows == 1) {
        $teacher = $result->fetch_assoc();
    } else {
        die("Teacher not found.");
    }
    $stmt->close();
}
$conn->close();

// Create PDF
$pdf = new FPDF();
$pdf->AddPage();

// Header
$pdf->SetFont('Arial', 'B', 16);
$pdf->Cell(0, 10, 'Teacher Information', 0, 1, 'C');
$pdf->Ln(10);

// Body
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(40, 10, 'Teacher ID:', 0, 0);
$pdf->SetFont('Arial', '', 12);
$pdf->Cell(0, 10, $teacher['teacher_id'], 0, 1);

$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(40, 10, 'Name:', 0, 0);
$pdf->SetFont('Arial', '', 12);
$pdf->Cell(0, 10, $teacher['name'], 0, 1);

$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(40, 10, 'Department:', 0, 0);
$pdf->SetFont('Arial', '', 12);
$pdf->Cell(0, 10, $teacher['department'], 0, 1);

$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(40, 10, 'Email:', 0, 0);
$pdf->SetFont('Arial', '', 12);
$pdf->Cell(0, 10, $teacher['email'], 0, 1);

$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(40, 10, 'Phone:', 0, 0);
$pdf->SetFont('Arial', '', 12);
$pdf->Cell(0, 10, $teacher['phone'], 0, 1);

// Output PDF
$pdf->Output('D', 'teacher-'.$teacher['teacher_id'].'.pdf');
?>