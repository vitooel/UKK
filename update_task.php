<?php
include('config.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'];
    $task = mysqli_real_escape_string($mysqli, $_POST['task']);
    $description = mysqli_real_escape_string($mysqli, $_POST['description']);
    $completed_at = mysqli_real_escape_string($mysqli, $_POST['completed_at']); // Ambil tanggal selesai dari formulir
    $status = mysqli_real_escape_string($mysqli, $_POST['status']);
    
    $query = "UPDATE tasks SET task='$task', description='$description', completed_at='$completed_at',  status = '$status' WHERE id=$id";
    mysqli_query($mysqli, $query);
    
}

header('Location: index.php');
exit();

