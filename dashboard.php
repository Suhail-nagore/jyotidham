<?php
session_start();
if(!isset($_SESSION['username'])) {
    header("Location: admin-login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h2 class="text-center">Admin Dashboard</h2>
                    </div>
                    <div class="card-body">
                        <div class="text-center mb-3">
                            <a href="add-user.php" class="btn btn-primary mb-3">Add User</a> <br>
                            <!-- <a href="manage-blogs.php" class="btn btn-primary mb-3">Manage Blogs</a> <br>
                            <a href="manage-news.php" class="btn btn-primary mb-3">Manage News</a> <br> -->
                            <a href="index.html" class="btn btn-secondary mb-3">Homepage</a> <br>
                            <a href="logout.php" class="btn btn-danger mb3">Logout</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
