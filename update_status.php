<?php
session_start();
include('config.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $task_id = $_POST['id'];
    $status = $_POST['status']; // Nilai status dari form

    if ($status == 'completed') {
        $completed_at = date('Y-m-d'); // Set tanggal penyelesaian
        $query = "UPDATE tasks SET status='completed', completed_at='$completed_at' WHERE id='$task_id'";
    } else {
        $query = "UPDATE tasks SET status='$status', completed_at=NULL WHERE id='$task_id'";
    }

    if (mysqli_query($mysqli, $query)) {
        header("Location: index.php");
        exit();
    } else {
        echo "Gagal memperbarui status: " . mysqli_error($mysqli);
    }
}
