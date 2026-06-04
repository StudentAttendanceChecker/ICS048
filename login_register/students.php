<?php
session_start();
if (!isset($_SESSION['email'])){
    header("Location: index.php");
    exit();
}
require_once 'config.php';

$students = $conn->query("SELECT * FROM students");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Students</title>
    <link rel="stylesheet" href="style.css">
</head>
<body class="dashboard">

    <div class="topbar">
        <div class="topbar-brand">Student Attendance Checker</div>
        <div class="topbar-right">
            <span>Welcome, <strong><?= $_SESSION['name']; ?></strong></span>
            <button onclick="window.location.href='logout.php'">Logout</button>
        </div>
    </div>

    <div class="content">
        <div class="section-header">
            <h2>Students Masterlist</h2>
            <div class="header-actions">
                <button class="btn-add-course" onclick="window.location.href='add_student.php'">+ Add Student</button>
                <button class="btn-back" onclick="window.location.href='user_page.php'">Back</button>
            </div>
        </div>

        <div class="table-wrapper">
            <table>
                <thead>
                    <tr>
                        <th>Student ID</th>
                        <th>Last Name</th>
                        <th>First Name</th>
                        <th>Sex</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($students->num_rows > 0): ?>
                        <?php while ($student = $students->fetch_assoc()): ?>
                        <tr>
                            <td><?= htmlspecialchars($student['student_id']); ?></td>
                            <td><?= htmlspecialchars($student['last_name']); ?></td>
                            <td><?= htmlspecialchars($student['first_name']); ?></td>
                            <td><?= htmlspecialchars($student['sex']); ?></td>
                            <td>
                                <button class="btn-edit-sm" onclick="window.location.href='edit_student.php?id=<?= $student['student_id']; ?>'">Edit</button>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5" style="text-align:center; color:#888;">No students yet.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

</body>
</html>