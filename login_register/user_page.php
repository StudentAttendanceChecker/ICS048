<?php
session_start();
if (!isset($_SESSION['email'])){
    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Page</title>
    <link rel="stylesheet" href="style.css">
</head>
<body class="dashboard">
    <div class="topbar">
        <h1>Welcome, Teacher<span><?= $_SESSION['name']; ?>!</span></h1>
        <button onclick="window.location.href='logout.php'">Logout</button>
    </div>

    <div class="content">
        <h2>List of Clients</h2>
        <button class="new-client-btn">New Client</button>

        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Address</th>
                    <th>Created At</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <!-- Your PHP rows will go here -->
            </tbody>
        </table>
    </div>
</body>
</html>