<?php
session_start();
if (!isset($_SESSION['email'])){
    header("Location: index.php");
    exit();
}
require_once 'config.php';

if (isset($_POST['add_course'])) {
    $room = "Room " . $_POST['room'];
    $year_num = $_POST['year_level'];
    $suffix = match((int)$year_num) {
        1 => '1st',
        2 => '2nd',
        3 => '3rd',
        default => $year_num . 'th'
    };
    $year_level = $suffix . " Year";
    $section = "Section " . strtoupper($_POST['section']);
    $subject = $_POST['subject'];

    $conn->query("INSERT INTO courses (room, year_level, section, subject) 
                  VALUES ('$room', '$year_level', '$section', '$subject')");

    header("Location: user_page.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Course</title>
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
            <h2>Add Course</h2>
            <form action="add_course.php" method="post">
                <label>Room</label>
                <input type="number" name="room" placeholder="e.g. 101" required>

                <label>Year Level</label>
                <input type="number" name="year_level" placeholder="e.g. 1" min="1" max="4" required>

                <label>Section</label>
                <input type="text" name="section" placeholder="e.g. A" maxlength="1" required>

                <label>Subject</label>
                <input type="text" name="subject" placeholder="e.g. Programming 1" required>

                <div class="form-actions">
                    <button type="submit" name="add_course">Add Course</button>
                    <button type="button" class="btn-cancel" onclick="window.location.href='user_page.php'">Cancel</button>
                </div>
            </form>
        </div>
    </div>

</body>
</html>
