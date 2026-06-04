<?php
session_start();
if (!isset($_SESSION['email'])){
    header("Location: index.php");
    exit();
}
require_once 'config.php';

$course_id = $_GET['course_id'] ?? null;
if (!$course_id) {
    header("Location: user_page.php");
    exit();
}

$result = $conn->query("SELECT * FROM courses WHERE id = $course_id");
$course = $result->fetch_assoc();
if (!$course) {
    header("Location: user_page.php");
    exit();
}

$today = date('Y-m-d');
$saved = false;
$error = '';

// Update student
if (isset($_POST['update_student'])) {
    $sid        = $conn->real_escape_string(trim($_POST['edit_student_id']));
    $first_name = $conn->real_escape_string(trim($_POST['edit_first_name']));
    $last_name  = $conn->real_escape_string(trim($_POST['edit_last_name']));
    $sex        = $conn->real_escape_string($_POST['edit_sex']);
    $conn->query("UPDATE students SET first_name='$first_name', last_name='$last_name', sex='$sex' WHERE student_id='$sid'");
    header("Location: course_students.php?course_id=$course_id");
    exit();
}

// Delete student from course
if (isset($_POST['delete_student'])) {
    $sid = $conn->real_escape_string(trim($_POST['delete_student_id']));
    $conn->query("DELETE FROM student_courses WHERE student_id='$sid' AND course_id=$course_id");
    $conn->query("DELETE FROM attendance WHERE student_id='$sid' AND course_id=$course_id");
    header("Location: course_students.php?course_id=$course_id");
    exit();
}

// Add student directly from this page
if (isset($_POST['add_student'])) {
    $student_id = trim($_POST['student_id']);
    $first_name = trim($_POST['first_name'] ?? '');
    $last_name  = trim($_POST['last_name'] ?? '');
    $sex        = $_POST['sex'];

    $check = $conn->query("SELECT student_id FROM students WHERE student_id = '$student_id'");
    if ($check->num_rows > 0) {
        $error = 'Student ID already exists!';
    } else {
        $conn->query("INSERT INTO students (student_id, first_name, last_name, sex) 
                      VALUES ('$student_id', '$first_name', '$last_name', '$sex')");
        $conn->query("INSERT INTO student_courses (student_id, course_id) 
                      VALUES ('$student_id', '$course_id')");
    }
}

// Save attendance
if (isset($_POST['save_attendance'])) {
    $date = $_POST['date'] ?? $today;
    $statuses = $_POST['status'] ?? [];
    foreach ($statuses as $student_id => $status) {
        $student_id = $conn->real_escape_string($student_id);
        $status     = $conn->real_escape_string($status);
        $check = $conn->query("SELECT student_id FROM attendance WHERE course_id = $course_id AND student_id = '$student_id' AND date = '$date'");
        if ($check->num_rows > 0) {
            $conn->query("UPDATE attendance SET status = '$status' WHERE course_id = $course_id AND student_id = '$student_id' AND date = '$date'");
        } else {
            $conn->query("INSERT INTO attendance (course_id, student_id, date, status) VALUES ($course_id, '$student_id', '$date', '$status')");
        }
    }
    $saved = true;
}

$selected_date = $_POST['date'] ?? $_GET['date'] ?? $today;

$students = $conn->query("SELECT s.* FROM students s 
    INNER JOIN student_courses sc ON s.student_id = sc.student_id 
    WHERE sc.course_id = $course_id 
    ORDER BY s.student_id ASC");

$attendance_map = [];
$att_result = $conn->query("SELECT student_id, status FROM attendance WHERE course_id = $course_id AND date = '$selected_date'");
if ($att_result) {
    while ($row = $att_result->fetch_assoc()) {
        $attendance_map[$row['student_id']] = $row['status'];
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Attendance - <?= htmlspecialchars($course['subject']); ?></title>
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

    <?php if ($saved): ?>
        <div class="alert-success">Attendance saved for <?= date('F j, Y', strtotime($selected_date)); ?>.</div>
    <?php endif; ?>

    <div class="content">
        <div class="section-header">
            <div>
                <h2><?= htmlspecialchars($course['subject']); ?></h2>
                <p class="course-meta"><?= htmlspecialchars($course['year_level']); ?> &mdash; <?= htmlspecialchars($course['section']); ?> &mdash; <?= htmlspecialchars($course['room']); ?></p>
            </div>
            <div class="header-actions">
                <button class="btn-add-course" onclick="document.getElementById('add-student-form').classList.toggle('hidden')">+ Add Student</button>
                <button class="btn-back" onclick="window.location.href='user_page.php'">&#8592; Back</button>
            </div>
        </div>

        <!-- Add Student inline form -->
        <div id="add-student-form" class="form-card hidden" style="max-width:100%; margin-bottom:20px;">
            <?php if ($error): ?>
                <p class="error-message"><?= $error; ?></p>
            <?php endif; ?>
            <form method="post" action="course_students.php?course_id=<?= $course_id; ?>">
                <div style="display:flex; gap:12px; flex-wrap:wrap; align-items:flex-end;">
                    <div style="flex:1; min-width:140px;">
                        <label>Student ID</label>
                        <input type="text" name="student_id" placeholder="e.g. 2023-0001" required style="margin:0;">
                    </div>
                    <div style="flex:1; min-width:140px;">
                        <label>First Name</label>
                        <input type="text" name="first_name" placeholder="e.g. Juan" required style="margin:0;">
                    </div>
                    <div style="flex:1; min-width:140px;">
                        <label>Last Name</label>
                        <input type="text" name="last_name" placeholder="e.g. Dela Cruz" required style="margin:0;">
                    </div>
                    <div style="flex:1; min-width:120px;">
                        <label>Sex</label>
                        <select name="sex" required style="margin:0;">
                            <option value="" disabled selected>Select</option>
                            <option value="Male">Male</option>
                            <option value="Female">Female</option>
                        </select>
                    </div>
                    <div>
                        <button type="submit" name="add_student" style="width:auto; padding:12px 20px; margin:0;">Add</button>
                    </div>
                </div>
            </form>
        </div>

        <form method="post" action="course_students.php?course_id=<?= $course_id; ?>">
            <div class="date-bar">
                <label for="date">Date:</label>
                <input type="date" name="date" id="date" value="<?= htmlspecialchars($selected_date); ?>" max="<?= $today; ?>">
                <button type="submit" name="load_date" class="btn-load">Load</button>
            </div>

            <div class="table-wrapper">
                <table>
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Student ID</th>
                            <th>Name</th>
                            <th>Sex</th>
                            <th>Attendance</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($students && $students->num_rows > 0):
                            $i = 1;
                            while ($student = $students->fetch_assoc()):
                                $sid     = $student['student_id'];
                                $current = $attendance_map[$sid] ?? 'Present';
                                $fn      = htmlspecialchars($student['first_name']);
                                $ln      = htmlspecialchars($student['last_name']);
                                $sx      = htmlspecialchars($student['sex']);
                        ?>
                        <!-- Normal row -->
                        <tr id="row-<?= $sid ?>">
                            <td><?= $i++; ?></td>
                            <td><?= htmlspecialchars($sid); ?></td>
                            <td><?= $ln . ', ' . $fn; ?></td>
                            <td><?= $sx; ?></td>
                            <td>
                                <select name="status[<?= $sid; ?>]" style="margin:0; padding:6px 10px; font-size:13px; width:auto;">
                                    <option value="Present" <?= $current === 'Present' ? 'selected' : ''; ?>>Present</option>
                                    <option value="Absent"  <?= $current === 'Absent'  ? 'selected' : ''; ?>>Absent</option>
                                    <option value="Late"    <?= $current === 'Late'    ? 'selected' : ''; ?>>Late</option>
                                </select>
                            </td>
                            <td style="white-space:nowrap;">
                                <button type="button" class="btn-edit-sm" style="background:#f39c12; margin-right:4px;" onclick="toggleEdit('<?= $sid ?>')">Edit</button>
                                <button type="button" class="btn-edit-sm" style="background:#e74c3c;" onclick="deleteStudent('<?= $sid ?>', '<?= $ln ?>, <?= $fn ?>')">Delete</button>
                            </td>
                        </tr>
                        <!-- Inline edit row -->
                        <tr id="edit-row-<?= $sid ?>" class="hidden" style="background:#fffbea;">
                            <td colspan="6">
                                <form method="post" action="course_students.php?course_id=<?= $course_id ?>">
                                    <input type="hidden" name="edit_student_id" value="<?= htmlspecialchars($sid) ?>">
                                    <div style="display:flex; gap:10px; flex-wrap:wrap; align-items:flex-end; padding:6px 0;">
                                        <div style="flex:1; min-width:130px;">
                                            <label style="font-size:12px;">First Name</label>
                                            <input type="text" name="edit_first_name" value="<?= $fn ?>" required style="margin:0;">
                                        </div>
                                        <div style="flex:1; min-width:130px;">
                                            <label style="font-size:12px;">Last Name</label>
                                            <input type="text" name="edit_last_name" value="<?= $ln ?>" required style="margin:0;">
                                        </div>
                                        <div style="min-width:110px;">
                                            <label style="font-size:12px;">Sex</label>
                                            <select name="edit_sex" required style="margin:0;">
                                                <option value="Male" <?= $sx === 'Male' ? 'selected' : '' ?>>Male</option>
                                                <option value="Female" <?= $sx === 'Female' ? 'selected' : '' ?>>Female</option>
                                            </select>
                                        </div>
                                        <div style="display:flex; gap:6px;">
                                            <button type="submit" name="update_student" style="width:auto; padding:10px 16px; margin:0; background:#27ae60;">Save</button>
                                            <button type="button" style="width:auto; padding:10px 16px; margin:0; background:#aaa;" onclick="toggleEdit('<?= $sid ?>')">Cancel</button>
                                        </div>
                                    </div>
                                </form>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                        <?php else: ?>
                        <tr>
                            <td colspan="6" style="text-align:center; color:#888;">No students yet. Click "+ Add Student" to add one.</td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <?php if ($students && $students->num_rows > 0): ?>
            <div class="save-bar">
                <button type="submit" name="save_attendance">Save Attendance</button>
            </div>
            <?php endif; ?>
        </form>
    </div>

    <!-- Hidden delete form -->
    <form id="delete-form" method="post" action="course_students.php?course_id=<?= $course_id ?>">
        <input type="hidden" name="delete_student_id" id="delete-student-id">
        <input type="hidden" name="delete_student">
    </form>

    <script>
    <?php if ($error): ?>
    document.getElementById('add-student-form').classList.remove('hidden');
    <?php endif; ?>

    function toggleEdit(sid) {
        document.getElementById('edit-row-' + sid).classList.toggle('hidden');
    }

    function deleteStudent(sid, name) {
        if (confirm('Remove ' + name + ' from this course?')) {
            document.getElementById('delete-student-id').value = sid;
            document.getElementById('delete-form').submit();
        }
    }
    </script>
</body>
</html>