<?php
session_start();
include('config.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_SESSION['user_id'];
    $task = mysqli_real_escape_string($mysqli, $_POST['task']);
    $description = mysqli_real_escape_string($mysqli, $_POST['description']);
    $start_date = date('Y-m-d'); // Set otomatis saat tugas dibuat
    $due_date = mysqli_real_escape_string($mysqli, $_POST['due_date']);
    $priority = mysqli_real_escape_string($mysqli, $_POST['priority']);  // Ambil nilai prioritas dari form

    // Pastikan prioritas valid
    if (!in_array($priority, ['biasa', 'segera'])) {
        echo "Prioritas tidak valid!";
        exit();
    }

    // Query untuk menambahkan tugas
    $query = "INSERT INTO tasks (user_id, task, description, start_date, due_date, status, priority) 
              VALUES ('$user_id', '$task', '$description', '$start_date', '$due_date', 'pending', '$priority')";

    // Eksekusi query
    if (mysqli_query($mysqli, $query)) {
        header("Location: index.php");
        exit();
    } else {
        echo "Gagal menambahkan tugas: " . mysqli_error($mysqli);
    }
}
