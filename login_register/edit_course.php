<?php
session_start();
if (!isset($_SESSION['email'])){
    header("Location: index.php");
    exit();
}
require_once 'config.php';

$id = $_GET['id'] ?? null;
if (!$id) {
    header("Location: user_page.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$result = $conn->query("SELECT * FROM courses WHERE id = $id AND user_id = $user_id");
$course = $result->fetch_assoc();
if (!$course) {
    header("Location: user_page.php");
    exit();
}

if (isset($_POST['edit_course'])) {
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

    $conn->query("UPDATE courses SET room='$room', year_level='$year_level', section='$section', subject='$subject' WHERE id=$id");

    header("Location: user_page.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Course</title>
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
            <h2>Edit Course</h2>
            <form action="edit_course.php?id=<?= $id ?>" method="post">
                <label>Room</label>
                <input type="number" name="room" placeholder="e.g. 101" value="<?= filter_var($course['room'], FILTER_SANITIZE_NUMBER_INT); ?>" required>

                <label>Year Level</label>
                <input type="number" name="year_level" placeholder="e.g. 1" min="1" max="4" value="<?= filter_var($course['year_level'], FILTER_SANITIZE_NUMBER_INT); ?>" required>

                <label>Section</label>
                <input type="text" name="section" placeholder="e.g. A" maxlength="1" value="<?= str_replace('Section ', '', $course['section']); ?>" required>

                <label>Subject</label>
                <input type="text" name="subject" placeholder="e.g. Programming 1" value="<?= $course['subject']; ?>" required>

               <div class="form-actions">
    <button type="submit" name="edit_course">Save Changes</button>
    <button type="button" class="btn-cancel" onclick="window.location.href='user_page.php'">Cancel</button>
    <button type="button" class="btn-delete" onclick="confirmDelete(<?= $id ?>)">Delete</button>
</div>

<script>
function confirmDelete(id) {
    if (confirm("Are you sure you want to delete this course?")) {
        window.location.href = 'delete_course.php?id=' + id;
    }
}
</script>
            </form>
        </div>
    </div>

</body>
</html>