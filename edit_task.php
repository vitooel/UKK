<?php
session_start();
include('config.php');

// Cek apakah pengguna sudah login
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Pastikan ID tugas tersedia di URL
if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("ID tugas tidak valid.");
}

$task_id = intval($_GET['id']); // Amankan ID tugas

// Ambil data tugas berdasarkan ID
$query = $mysqli->prepare("SELECT * FROM tasks WHERE id = ? AND user_id = ?");
$query->bind_param("ii", $task_id, $_SESSION['user_id']);
$query->execute();
$result = $query->get_result();
$task = $result->fetch_assoc();

// Jika tugas tidak ditemukan, tampilkan pesan error
if (!$task) {
    die("Tugas tidak ditemukan atau Anda tidak memiliki izin.");
}

// Update tugas jika formulir dikirimkan
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $task_name = $_POST['task'];
    $description = $_POST['description'];
    $start_date = $_POST['start_date'];
    $due_date = $_POST['due_date'];
    $status = $_POST['status'];
    $priority = $_POST['priority'];  // Pastikan ini ada
    $completed_at = NULL;  // Defaultkan ke NULL

    // Jika status "completed", set completed_at menjadi tanggal sekarang
    if ($status == 'completed') {
        $completed_at = date('Y-m-d');
    }

    // Validasi input
    // Validasi input
    if (empty($task_name) || empty($status) || empty($start_date) || empty($priority)) {
        echo "Nama tugas, status, dan tanggal mulai harus diisi!";
    } else {
        // Query untuk memperbarui tugas tanpa bind_param
        $query = "UPDATE tasks SET task = '$task_name', description = '$description', start_date = '$start_date', due_date = '$due_date', status = '$status', priority = '$priority', completed_at = '$completed_at' WHERE id = $task_id AND user_id = {$_SESSION['user_id']}";

        // Eksekusi query dan periksa apakah berhasil
        if ($mysqli->query($query)) {
            header("Location: index.php");
            exit();
        } else {
            echo "Gagal memperbarui tugas!";
        }
    }
}





?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Tugas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" />
    <style>
        body {
            padding-top: 80px; /* Add padding for fixed navbar */
            background-color: #f8f9fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: #333;
            margin: 0;
            padding-top: 80px;
        }

        /* Navbar Styling */
        .navbar {
            background: linear-gradient(135deg, #2c3e50, #3498db);
            padding: 12px 30px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .navbar .navbar-brand {
            font-size: 20px;
            color: #fff;
            font-weight: 600;
        }

        .navbar .navbar-brand img {
            height: 35px;
        }

        .container {
            max-width: 600px;
            margin: 60px auto;
            padding: 25px;
            background: #ffffff;
            border-radius: 10px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
            border-top: 5px solid #2c3e50;
        }

        h1 {
            text-align: center;
            font-size: 24px;
            font-weight: 600;
            color: #2c3e50;
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-top: 12px;
            font-weight: 500;
            color: #34495e;
        }

        input,
        textarea,
        select {
            width: 100%;
            padding: 12px;
            margin-top: 5px;
            border: 1px solid #bdc3c7;
            border-radius: 5px;
            font-size: 14px;
            background-color: #f8f9fa;
            transition: all 0.3s ease;
        }

        input:focus,
        textarea:focus,
        select:focus {
            border-color: #2c3e50;
            outline: none;
            box-shadow: 0px 0px 5px rgba(44, 62, 80, 0.5);
        }

        button {
            display: block;
            width: 100%;
            background: linear-gradient(135deg, #2c3e50, #3498db);
            color: white;
            padding: 12px;
            margin-top: 20px;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.3s ease, transform 0.2s ease;
        }

        button:hover {
            background: #1a252f;
            transform: scale(1.03);
        }

        .back-btn {
            display: block;
            text-align: center;
            margin-top: 15px;
            color: #2c3e50;
            text-decoration: none;
            font-size: 14px;
            font-weight: 500;
            transition: color 0.3s ease;
        }

        .back-btn:hover {
            color: #1a252f;
            text-decoration: underline;
        }
    </style>
</head>

<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-primary fixed-top">
        <div class="container-fluid">
            <!-- Logo di kiri -->
            <a class="navbar-brand" href="#">
                <img src="vtodo.png" alt="Logo" style="height: 40px;">
            </a>

            <!-- Judul di tengah -->
            <div class="mx-auto">
                <h3 class="text-white mb-0">V-TODOLIST</h3>
            </div>
        </div>
    </nav>

    <div class="container">
        <h1>Edit Tugas</h1>
        <form action="" method="POST">
            <label for="task">Nama Tugas:</label>
            <input type="text" name="task" value="<?= htmlspecialchars($task['task']) ?>" required>

            <label for="description">Deskripsi:</label>
            <textarea name="description"><?= htmlspecialchars($task['description']) ?></textarea>

            <label for="start_date">Tanggal Mulai:</label>
            <input type="date" name="start_date" value="<?= htmlspecialchars($task['start_date']) ?>" required>

            <label>Prioritas:</label>
            <select name="priority">
                <option value="biasa" <?= $task['priority'] == 'biasa' ? 'selected' : '' ?>>Biasa</option>
                <option value="segera" <?= $task['priority'] == 'segera' ? 'selected' : '' ?>>Segera</option>
            </select>

            <label for="due_date">Waktu Tempo Selesai:</label>
            <input type="date" name="due_date" value="<?= htmlspecialchars($task['due_date']) ?>" required>

            <label for="status">Status:</label>
            <select name="status">
                <option value="pending" <?= $task['status'] == 'pending' ? 'selected' : '' ?>>Belum Selesai</option>
                <option value="on_proses" <?= $task['status'] == 'on_proses' ? 'selected' : '' ?>>Sedang Diproses</option>
                <option value="completed" <?= $task['status'] == 'completed' ? 'selected' : '' ?>>Selesai</option>
            </select>

            <button type="submit">Simpan Perubahan</button>
        </form>
        <a href="index.php" class="back-btn">Kembali</a>
    </div>
</body>

</html>