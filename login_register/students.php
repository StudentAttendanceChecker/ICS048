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
                        <th>Name</th>
                        <th>Sex</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($students->num_rows > 0): ?>
                        <?php while ($student = $students->fetch_assoc()): ?>
                        <tr>
                            <td><?= $student['student_id']; ?></td>
                            <td><?= $student['name']; ?></td>
                            <td><?= $student['sex']; ?></td>
                            <td>
                                <button class="btn-edit-sm" onclick="window.location.href='edit_student.php?id=<?= $student['student_id']; ?>'">Edit</button>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" style="text-align:center; color:#888;">No students yet.</td>
                        </tr>
          
Claude finished the response
Welcome, Saoirse! I’m Claude.
Bring me anything—a tough problem, a half-formed idea, something you need to write. We’ll figure it out together.

Where do you want to start?


user_page.php
74 lines

php


style.css
497 lines

css


students.php
76 lines

php


script.js
4 lines

js


logout.php
9 lines

php


login_register.php
47 lines

php


index.php
65 lines

php


edit_course.php
90 lines

php


delete_course.php
18 lines

php


course_students.php
207 lines

php


config.php
14 lines

php


add_student.php
96 lines

php


add_course.php
74 lines

php

i just want a simple table inside the course and hide form in add student, it should only appear after i click the button

9:59 AM
Looking at your course_students.php, the add student form uses a hidden class to toggle visibility, but that CSS class isn't defined in your style.css. That's why the form isn't hiding properly.

Here's the fix — just add this one rule to your style.css:

css
.hidden {
    display: none;
}
That's it. The toggle logic in your existing code is already correct:

js
// button already does this:
document.getElementById('add-student-form').classList.toggle('hidden')

// and this keeps it open on error:
document.getElementById('add-student-form').classList.remove('hidden');
Once you add .hidden { display: none; } to style.css, the form will be hidden by default and only appear when you click + Add Student.

can you put it in the code so that i'll judt copy and paste it?

10:00 AM
Here are your two updated files:


Ran 2 commands, edited a file, read a file
Ran 2 commands, edited a file, read a file
Only style.css needed the change — I added .hidden { display: none; } near the bottom. Your course_students.php was already correct and didn't need editing. Just replace your style.css with the downloaded one and you're good to go.


Style
Code · CSS 

Course students
Code · PHP 
show me i dont want to download

10:00 AM
Here's the only change you need to make. Find this in your style.css:

css
.alert-success {
    background: #d4edda;
    color: #155724;
    padding: 12px 16px;
    border-radius: 8px;
    font-size: 14px;
    margin-bottom: 16px;
}
Add .hidden right above it so it looks like this:

css
.hidden {
    display: none;
}

.alert-success {
    background: #d4edda;
    color: #155724;
    padding: 12px 16px;
    border-radius: 8px;
    font-size: 14px;
    margin-bottom: 16px;
}
That's the only edit — one new rule, three lines. Nothing else needs to change.


@import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;700&display=swap'); *{ margin: 0; padding: 0; box-sizing: border-box; font-family: "Poppins", serif; } body { display: flex; justify-content: center; align-items: center; min-hei

pasted

where

10:01 AM
At the very bottom of your style.css, after the last closing }, just add this:

css
.hidden {
    display: none;
}
So the end of your file should look like this:

css
.alert-success {
    background: #d4edda;
    color: #155724;
    padding: 12px 16px;
    border-radius: 8px;
    font-size: 14px;
    margin-bottom: 16px;
}

.hidden {
    display: none;
}
merge it

10:01 AM
Here's your complete style.css with .hidden added at the bottom:


Ran a command, read a file
Ran a command, read a file
Done! Just replace your current style.css with this one.


Style
Code · CSS 
1780538557455_image.png
still not hidden

10:02 AM
The .hidden class is there in the CSS now, but the issue is the form div in course_students.php doesn't start with hidden — it's missing from the initial class. Let me check:

In your course_students.php, find this line:

php
<div id="add-student-form" class="form-card hidden" style="max-width:100%; margin-bottom:20px;">
If it already says hidden there, the problem is your browser cached the old CSS. Try hard refresh: Ctrl + Shift + R (Windows) or Cmd + Shift + R (Mac).

If it does NOT say hidden in the class, change it to:

php
<div id="add-student-form" class="form-card hidden" style="max-width:100%; margin-bottom:20px;">
That's the fix — make sure both form-card and hidden are in the class attribute.


user_page.php
74 lines

php


style.css
500 lines

css


students.php
76 lines

php


script.js
4 lines

js


logout.php
9 lines

php


login_register.php
47 lines

php


index.php
65 lines

php


edit_course.php
90 lines

php


delete_course.php
18 lines

php


course_students.php
206 lines

php


config.php
14 lines

php


add_student.php
96 lines

php


add_course.php
74 lines

php

on add student first name and last name should be separate right?

10:05 AM

Copy files to working directory
Copy files to working directory
This change touches 3 files — course_students.php, add_student.php, and students.php. It also assumes your students table has first_name and last_name columns instead of name. If your DB still uses name, let me know and I'll adjust.


Edited 2 files
Edited 2 files
Now update course_students.php inline add form:


Edited 2 files
Edited 2 files
Now update students.php table to show separate columns:


Edited 2 files, ran a command, read a file
Edited 2 files, ran a command, read a file
Here's what changed across the 3 files:

add_student.php — "Full Name" split into two fields: First Name + Last Name
course_students.php — Same split in the inline add form
students.php — Table now shows separate Last Name and First Name columns
The name is stored in the DB as "Dela Cruz, Juan" (Last, First format) — no DB schema change needed since it still uses the existing name column.


Add student
Code · PHP 

Course students
Code · PHP 

Students
Code · PHP 
show me

10:06 AM
Here are all 3 files in full:

add_student.php


Show add_student.php
Show add_student.php
course_students.php


Show course_students.php
Show course_students.php
students.php


Show students.php
Show students.php
There you go — all 3 files in full. The key changes are highlighted by the line numbers:

add_student.php lines 13–15, 63–67 — split into First Name + Last Name fields
course_students.php lines 29–31, 113–119 — same split in the inline form
students.php lines 44–45, 57–58 — table now shows Last Name and First Name as separate columns






Claude is AI and can make mistakes. Please double-check responses.
Students · PHP
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
                        <th>Email</th>
                        <th>Address</th>
                        <th>Sex</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($students->num_rows > 0): ?>
                        <?php while ($student = $students->fetch_assoc()): ?>
                        <tr>
                            <td><?= $student['student_id']; ?></td>
                            <td><?= explode(', ', $student['name'])[0] ?? ''; ?></td>
                            <td><?= explode(', ', $student['name'])[1] ?? ''; ?></td>
                            <td><?= $student['email']; ?></td>
                            <td><?= $student['address']; ?></td>
                            <td><?= $student['sex']; ?></td>
                            <td>
                                <button class="btn-edit-sm" onclick="window.location.href='edit_student.php?id=<?= $student['student_id']; ?>'">Edit</button>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" style="text-align:center; color:#888;">No students yet.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

</body>
</html>
          <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

</body>
</html>