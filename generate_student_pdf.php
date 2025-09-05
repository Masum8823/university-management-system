<?php
require_once 'db_connect.php';
require('fpdf/fpdf.php');

if (!isset($_GET['id'])) {
    die("Student ID not provided.");
}

$student_id = $_GET['id'];

// Fetch student data
$student = null;
$sql = "SELECT * FROM students WHERE student_id = ?";
if ($stmt = $conn->prepare($sql)) {
    $stmt->bind_param("s", $student_id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows == 1) {
        $student = $result->fetch_assoc();
    } else {
        die("Student not found.");
    }
    $stmt->close();
}
$conn->close();

// Create PDF
$pdf = new FPDF();
$pdf->AddPage();

// Header
$pdf->SetFont('Arial', 'B', 16);
$pdf->Cell(0, 10, 'Student Information', 0, 1, 'C');
$pdf->Ln(10); // Line break

// Body
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(50, 10, 'Student ID:', 0, 0);
$pdf->SetFont('Arial', '', 12);
$pdf->Cell(0, 10, $student['student_id'], 0, 1);

$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(50, 10, 'Name:', 0, 0);
$pdf->SetFont('Arial', '', 12);
$pdf->Cell(0, 10, $student['name'], 0, 1);

$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(50, 10, 'Department:', 0, 0);
$pdf->SetFont('Arial', '', 12);
$pdf->Cell(0, 10, $student['department'], 0, 1);

$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(50, 10, 'Semester:', 0, 0);
$pdf->SetFont('Arial', '', 12);
$pdf->Cell(0, 10, $student['semester'], 0, 1);

$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(50, 10, 'Email:', 0, 0);
$pdf->SetFont('Arial', '', 12);
$pdf->Cell(0, 10, $student['email'], 0, 1);

$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(50, 10, 'Phone:', 0, 0);
$pdf->SetFont('Arial', '', 12);
$pdf->Cell(0, 10, $student['phone'], 0, 1);

$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(50, 10, 'Blood Group:', 0, 0);
$pdf->SetFont('Arial', '', 12);
$pdf->Cell(0, 10, $student['blood_group'], 0, 1);

// Output PDF
$pdf->Output('D', 'student-'.$student['student_id'].'.pdf'); // 'D' forces download
?>