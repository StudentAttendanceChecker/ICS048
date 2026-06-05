<?php
session_start();
if (!isset($_SESSION['email'])){
    header("Location: index.php");
    exit();
}
require_once 'config.php';
//fetch only the courses that belong to the logged in teacher 
$user_id = $_SESSION['user_id'];
$courses = $conn->query("SELECT * FROM courses WHERE user_id = $user_id");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="style.css">
</head>
<body class="dashboard">

    <div class="topbar">
        <div class="topbar-brand">Student Attendance Checker</div>
        <div class="topbar-right">
            <span>Welcome, <strong><?= $_SESSION['name']; ?>!</strong></span>
            <button onclick="window.location.href='logout.php'">Logout</button>
        </div>
    </div>

    <div class="content">

        <div class="add-course-bar">
            <button class="btn-add-course" onclick="window.location.href='add_course.php'">+ Add Course</button>
        </div>

        <div class="section-header">
            <h2>Your Courses</h2>
        </div>

        <div class="card-grid">
            <?php if ($courses->num_rows > 0): ?>
                <?php while ($course = $courses->fetch_assoc()): ?>
                <div class="card">
                    <div class="card-top">
                        <span class="room-tag"><?= $course['room']; ?></span>
                        <span class="student-count">
                            <?php
                                $cid   = $course['id'];
                                //dire gina count pila ka students ang enrolled in each course
                                $count = $conn->query("SELECT COUNT(*) as total FROM student_courses WHERE course_id = $cid");
                                $row   = $count->fetch_assoc();
                                echo $row['total'];
                            ?> Students
                        </span>
                    </div>
                    <h3><?= $course['subject']; ?></h3>
                    <p><?= $course['year_level']; ?> - <?= $course['section']; ?></p>
                    <div class="card-actions">
                        <button class="btn-start" onclick="window.location.href='course_students.php?course_id=<?= $course['id']; ?>'">Check Attendance</button>
                        <button class="btn-edit" onclick="window.location.href='edit_course.php?id=<?= $course['id']; ?>'">Edit</button>
                    </div>
                </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p class="no-courses">No courses yet. Click "Add Course" to get started!</p>
            <?php endif; ?>
        </div>

    </div>
    <script>
    function confirmDelete(id) {
        if (confirm("Are you sure you want to delete this course?")) {
            window.location.href = 'delete_course.php?id=' + id;
        }
    }
    </script>
</body>
</html>