<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
<?php
include 'config.php';

$Id             = $_POST["StudentId"];
$Name           = $_POST["StudentName"];
$Password       = password_hash($_POST["StudentPassword"], PASSWORD_DEFAULT); // ✅ hash
$Gender         = $_POST["StudentGender"];
$Email          = $_POST["StudentEmail"];
$MobileNumber   = $_POST["StudentPhoneNumber"];
$Programme      = $_POST["StudentProgramme"];
$Admission      = $_POST["StudentMonthAdmission"] . " " . $_POST["StudentYearAdmission"];
$EducationLevel = $_POST["StudentEducationLevel"];
$Institution    = $_POST["StudentInstitution"];

$stmt = $conn->prepare("INSERT INTO student 
    (Student_Id, Student_Name, Student_Password, Student_Gender, Student_Email, 
     Student_MobileNumber, Programme, YearofAdmission, EducationLevel, Institution) 
    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

$stmt->bind_param("ssssssssss", 
    $Id, $Name, $Password, $Gender, $Email,
    $MobileNumber, $Programme, $Admission, $EducationLevel, $Institution);

if ($stmt->execute()) {
    $stmt->close();
    $conn->close();
    header("Location: ../CMED/LoginPage.php?signup=success");
    exit();
} else {
    echo "Error: " . $stmt->error;
}
?>
    
</body>
</html>