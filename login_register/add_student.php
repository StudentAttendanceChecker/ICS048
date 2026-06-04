<?php
session_start();
if (!isset($_SESSION['email'])){
    header("Location: index.php");
    exit();
}
require_once 'config.php';

$error = '';

if (isset($_POST['add_student'])) {
    $student_id = trim($_POST['student_id']);
    $first_name = trim($_POST['first_name']);
    $last_name  = trim($_POST['last_name']);
    $name       = $last_name . ', ' . $first_name;
    $sex        = $_POST['sex'];
    $course_id  = $_POST['course_id'];

    $check = $conn->query("SELECT student_id FROM students WHERE student_id = '$student_id'");
    if ($check->num_rows > 0) {
        $error = 'Student ID already exists!';
    } else {
        $conn->query("INSERT INTO students (student_id, name, sex) 
                      VALUES ('$student_id', '$name', '$sex')");
        $conn->query("INSERT INTO student_courses (student_id, course_id) 
                      VALUES ('$student_id', '$course_id')");
        header("Location: students.php");
        exit();
    }
}

$courses = $conn->query("SELECT * FROM courses ORDER BY subject ASC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Student</title>
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
        <div class="form-card">
            <h2>Add Student</h2>
            <?php if ($error): ?>
                <p class="error-message"><?= $error; ?></p>
            <?php endif; ?>
            <form action="add_student.php" method="post">
                <label>Student ID</label>
                <input type="text" name="student_id" placeholder="e.g. 2023-0001" required>

                <label>First Name</label>
                <input type="text" name="first_name" placeholder="e.g. Juan" required>

                <label>Last Name</label>
                <input type="text" name="last_name" placeholder="e.g. Dela Cruz" required>

               <label>Sex</label>
                <select name="sex" required>
                    <option value="" disabled selected>Select sex</option>
                    <option value="Male">Male</option>
                    <option value="Female">Female</option>
                </select>

                <label>Course</label>
                <select name="course_id" required>
                    <option value="" disabled selected>Select course</option>
                    <?php while ($c = $courses->fetch_assoc()): ?>
                    <option value="<?= $c['id']; ?>">
                        <?= htmlspecialchars($c['subject']); ?> — <?= htmlspecialchars($c['section']); ?>
                    </option>
                    <?php endwhile; ?>
                </select>

                <div class="form-actions">
                    <button type="submit" name="add_student">Add Student</button>
                    <button type="button" class="btn-cancel" onclick="window.location.href='students.php'">Cancel</button>
                </div>
            </form>
        </div>
    </div>

</body>
</html>