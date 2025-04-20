<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

include('config.php');

$username = isset($_SESSION['username']) ? $_SESSION['username'] : 'Pengguna';
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>V-TodoList | Daftar Kegiatan</title>
    
    <link rel="icon" type="image/png" href="favicon.png">
    <link rel="stylesheet" href="styles.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
</head>

<body>
    <!-- Navbar --> 
    <nav class="navbar navbar-expand-lg fixed-top">
        <div class="container">
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent">
                <i class="fas fa-bars"></i>
            </button>

            <a class="navbar-brand d-flex align-items-center" href="#">
                <div class="brand-wrapper">
                    <img src="vtodo.png" alt="V-TodoList Logo" class="logo-img" width="35" height="35">
                    <span class="brand-text d-none d-sm-inline">V-TodoList</span>
                </div>
            </a>

            <div class="collapse navbar-collapse" id="navbarContent">
                <div class="navbar-nav ms-auto">
                    <div class="nav-profile d-flex align-items-center">
                        <div class="user-info text-end">
                            <span class="greeting d-none d-sm-inline">Welcome,</span>
                            <span class="username"><?php echo htmlspecialchars($username); ?></span>
                        </div>
                        <a href="logout.php" class="logout-link ms-3">
                            <i class="fas fa-sign-out-alt"></i>
                            <span class="logout-text d-none d-sm-inline">Logout</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <div class="container main-container animate__animated animate__fadeIn">
        <!-- Pesan Selamat Datang -->
        <div class="welcome animate__animated animate__bounceIn">
            <div class="welcome-content">
                <span class="welcome-icon"><i class="fas fa-tasks"></i></span>
                <div class="welcome-text">
                    <h2>Selamat datang, <strong><?php echo htmlspecialchars($username); ?></strong></h2>
                    <p class="welcome-subtitle">Mari kelola kegiatan Anda hari ini dengan lebih terorganisir</p>
                </div>
            </div>
            <div class="welcome-stats">
                <div class="stat-item">
                    <i class="fas fa-check-circle"></i>
                    <span>Tugas Selesai</span>
                    <?php
                    $completed_query = "SELECT COUNT(*) as count FROM tasks WHERE user_id = ? AND status = 'completed'";
                    $stmt = $mysqli->prepare($completed_query);
                    $stmt->bind_param("i", $_SESSION['user_id']);
                    $stmt->execute();
                    $completed_result = $stmt->get_result();
                    $completed_count = $completed_result->fetch_assoc()['count'];
                    echo "<h3>{$completed_count}</h3>";
                    ?>
                </div>
                <div class="stat-item">
                    <i class="fas fa-clock"></i>
                    <span>Belum Dikerjakan</span>
                    <?php
                    try {
                        $ongoing_query = "SELECT COUNT(*) as count FROM tasks WHERE user_id = ? AND status = 'pending'";
                        $stmt = $mysqli->prepare($ongoing_query);
                        if ($stmt) {
                            $stmt->bind_param("i", $_SESSION['user_id']);
                            $stmt->execute();
                            $ongoing_result = $stmt->get_result();
                            $ongoing_count = $ongoing_result->fetch_assoc()['count'];
                            echo "<h3>{$ongoing_count}</h3>";
                            $stmt->close();
                        } else {
                            echo "<h3>0</h3>";
                        }
                    } catch (Exception $e) {
                        echo "<h3>0</h3>";
                    }
                    ?>
                </div>
            </div>
        </div>

        <!-- Form untuk menambah kegiatan -->
        <div class="card task-form-card animate__animated animate__fadeInUp">
            <div class="card-header">
                <h5><i class="fas fa-plus-circle"></i> Tambah Kegiatan Baru</h5>
            </div>
            <div class="card-body">
                <form action="add_task.php" method="POST" class="task-form" id="addTaskForm">
                    <div class="form-group mb-3">
                        <label for="taskInput" class="form-label"><i class="fas fa-tasks"></i> Nama Kegiatan</label>
                        <input type="text" id="taskInput" name="task" placeholder="Masukkan nama kegiatan..." 
                               required class="form-control" minlength="3" maxlength="255" style="border: 1px solid #ced4da;">
                    </div>
                    <div class="form-group mb-3">
                        <label for="descriptionInput" class="form-label"><i class="fas fa-align-left"></i> Deskripsi</label>
                        <textarea id="descriptionInput" name="description" placeholder="Masukkan deskripsi kegiatan..." 
                                  class="form-control" maxlength="1000" rows="3" style="border: 1px solid #ced4da;"></textarea>
                    </div>
                    <div class="row g-3">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="startDateInput" class="form-label"><i class="far fa-calendar-alt"></i> Tanggal Dimulai</label>
                                <input type="date" id="startDateInput" name="start_date" 
                                       value="<?php echo date('Y-m-d'); ?>" required class="form-control"
                                       style="border: 1px solid #ced4da;">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="dueDateInput" class="form-label"><i class="fas fa-hourglass-end"></i> Tenggat Waktu</label>
                                <input type="date" id="dueDateInput" name="due_date" required class="form-control"
                                       min="<?php echo date('Y-m-d'); ?>"
                                       style="border: 1px solid #ced4da;">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="prioritySelect" class="form-label"><i class="fas fa-flag"></i> Prioritas</label>
                                <select id="prioritySelect" name="priority" required class="form-control">
                                    <option value="biasa">Biasa</option>
                                    <option value="segera">Segera</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="form-group mt-4 text-end">
                        <button type="submit" class="submit-btn">
                            <i class="fas fa-plus"></i> Tambah Kegiatan
                            <span class="loading-spinner"></span>
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Form untuk pencarian -->
        <div class="search-container animate__animated animate__fadeInUp">
            <form action="" method="GET" class="search-form">
                <div class="search-box">
                    <div class="search-input-wrapper">
                        <input type="text" id="searchInput" name="search" placeholder="Cari tugas..." 
                               class="search-input" value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
                    </div>
                </div>
                <div class="filter-options">
                    <select id="statusFilter" name="status" class="filter-select">
                        <option value="">Semua Status</option>
                        <option value="completed" <?php echo isset($_GET['status']) && $_GET['status'] == 'completed' ? 'selected' : ''; ?>>Selesai</option>
                        <option value="on_process" <?php echo isset($_GET['status']) && $_GET['status'] == 'on_process' ? 'selected' : ''; ?>>Dalam Proses</option>
                        <option value="pending" <?php echo isset($_GET['status']) && $_GET['status'] == 'pending' ? 'selected' : ''; ?>>Pending</option>
                    </select>
                    
                    <select id="priorityFilter" name="priority" class="filter-select">
                        <option value="">Semua Prioritas</option>
                        <option value="biasa" <?php echo isset($_GET['priority']) && $_GET['priority'] == 'biasa' ? 'selected' : ''; ?>>Biasa</option>
                        <option value="segera" <?php echo isset($_GET['priority']) && $_GET['priority'] == 'segera' ? 'selected' : ''; ?>>Segera</option>
                    </select>
                </div>
            </form>
        </div>

        <!-- Tabel Daftar Kegiatan -->
        <div class="table-container animate__animated animate__fadeInUp">
            <table class="task-table">
                <thead>
                    <tr>
                        <th><i class="fas fa-hashtag"></i> No</th>
                        <th><i class="fas fa-tasks"></i> Kegiatan</th>
                        <th><i class="fas fa-align-left"></i> Deskripsi</th>
                        <th><i class="far fa-calendar-alt"></i> Tanggal Mulai</th>
                        <th><i class="fas fa-hourglass-end"></i> Tenggat</th>
                        <th><i class="far fa-calendar-check"></i> Tanggal Selesai</th>
                        <th><i class="fas fa-info-circle"></i> Status</th>
                        <th><i class="fas fa-flag"></i> Prioritas</th>
                        <th><i class="fas fa-cogs"></i> Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $user_id = $_SESSION['user_id'];
                    $search = isset($_GET['search']) ? $_GET['search'] : '';
                    $status_filter = isset($_GET['status']) ? $_GET['status'] : '';
                    $priority_filter = isset($_GET['priority']) ? $_GET['priority'] : '';
                    
                    $query = "SELECT * FROM tasks WHERE user_id = ? ";
                    $params = array($user_id);
                    $types = "i";
                    
                    if (!empty($search)) {
                        $query .= "AND (task LIKE ? OR description LIKE ?) ";
                        $search_param = "%$search%";
                        array_push($params, $search_param, $search_param);
                        $types .= "ss";
                    }
                    
                    if (!empty($status_filter)) {
                        $query .= "AND status = ? ";
                        array_push($params, $status_filter);
                        $types .= "s";
                    }
                    
                    if (!empty($priority_filter)) {
                        $query .= "AND priority = ? ";
                        array_push($params, $priority_filter);
                        $types .= "s";
                    }
                    
                    $query .= "ORDER BY start_date DESC";
                    
                    $stmt = $mysqli->prepare($query);
                    if ($stmt) {
                        $stmt->bind_param($types, ...$params);
                        $stmt->execute();
                        $result = $stmt->get_result();
                        
                        if ($result->num_rows > 0) {
                            $no = 1;
                            while ($row = $result->fetch_assoc()) {
                                $status_text = '';
                                $status_class = '';
                                $completed_text = '-';
                                
                                switch ($row['status']) {
                                    case 'on_process':
                                        $status_text = 'Sedang Diproses';
                                        $status_class = 'status-warning';
                                        break;
                                    case 'completed':
                                        $status_text = 'Selesai';
                                        $status_class = 'status-success';
                                        if (!empty($row['completed_at'])) {
                                            $completed_text = date('d/m/Y', strtotime($row['completed_at']));
                                        }
                                        break;
                                    case 'pending':
                                        $status_text = 'Belum Selesai';
                                        $status_class = 'status-danger';
                                        break;
                                    default:
                                        $status_text = 'Sedang Diproses';
                                        $status_class = 'status-warning';
                                        break;
                                }
                                
                                echo "<tr class='task-row animate__animated animate__fadeIn'>";
                                echo "<td>{$no}</td>";
                                echo "<td>" . htmlspecialchars($row['task']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['description']) . "</td>";
                                echo "<td>" . date('d/m/Y', strtotime($row['start_date'])) . "</td>";
                                echo "<td>" . date('d/m/Y', strtotime($row['due_date'])) . "</td>";
                                echo "<td>{$completed_text}</td>";
                                echo "<td><span class='status-badge {$status_class}'>{$status_text}</span></td>";
                                echo "<td><span class='priority-badge'>" . ucfirst($row['priority']) . "</span></td>";
                                echo "<td class='action-buttons'>";
                                echo "<a href='edit_task.php?id=" . $row['id'] . "' class='edit-btn' title='Edit'><i class='fas fa-edit'></i></a>";
                                echo "<a href='delete_task.php?id=" . $row['id'] . "' class='delete-btn' onclick='return confirm(\"Apakah Anda yakin ingin menghapus kegiatan ini?\")'><i class='fas fa-trash-alt'></i></a>";
                                echo "</td></tr>";
                                $no++;
                            }
                        } else {
                            echo "<tr><td colspan='9' class='no-tasks'><i class='fas fa-inbox'></i> Tidak ada kegiatan yang ditemukan</td></tr>";
                        }
                        $stmt->close();
                    } else {
                        echo "<tr><td colspan='9' class='no-tasks'>Error dalam mempersiapkan query</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#addTaskForm').on('submit', function(e) {
                const startDate = new Date($('#startDateInput').val());
                const dueDate = new Date($('#dueDateInput').val());
                
                if (dueDate < startDate) {
                    e.preventDefault();
                    alert('Tenggat waktu tidak boleh lebih awal dari tanggal mulai!');
                    return false;
                }
            });

            $('.form-control').focus(function() {
                $(this).parent().addClass('input-focused');
            }).blur(function() {
                $(this).parent().removeClass('input-focused');
            });

            $('.task-row').hover(
                function() { $(this).addClass('row-hover'); },
                function() { $(this).removeClass('row-hover'); }
            );

            $('.submit-btn').click(function() {
                $(this).addClass('loading');
            });
            
            $('.filter-select').change(function() {
                $(this).closest('form').submit();
            });
        });
    </script>

    <style>
        :root {
            --primary-color: #3498db;
            --secondary-color: #2c3e50;
            --success-color: #2ecc71;
            --warning-color: #f1c40f;
            --danger-color: #e74c3c;
            --light-color: #ecf0f1;
            --dark-color: #2c3e50;
            --transition-speed: 0.3s;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Poppins', sans-serif; }

        body {
            background: #f4f6f9;
            color: var(--dark-color);
            padding-top: 120px;
            line-height: 1.6;
        }

        .navbar {
            background: linear-gradient(135deg, #2c3e50, #3498db);
            backdrop-filter: blur(10px);
            padding: 15px 0;
            box-shadow: 0 2px 15px rgba(0,0,0,0.08);
            min-height: 70px;
        }

        .container { max-width: 1200px; margin: 0 auto; padding: 0 30px; }

        .navbar-brand {
            display: flex;
            align-items: center;
            gap: 12px;
            text-decoration: none;
            padding: 8px;
            border-radius: 8px;
        }

        .logo-img {
            height: 35px;
            width: auto;
            filter: drop-shadow(0 2px 4px rgba(0,0,0,0.1));
        }

        .brand-text {
            color: var(--primary-color);
            font-size: 1.4rem;
            font-weight: 600;
            background: linear-gradient(135deg, #3498db 0%, #2980b9 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .nav-profile {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            padding: 10px 20px;
            border-radius: 15px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }

        .user-info {
            display: flex;
            flex-direction: column;
            align-items: flex-end;
        }

        .greeting { color: #6c757d; font-size: 0.85rem; }
        .username { font-size: 1.1rem; font-weight: 600; }

        .logout-link {
            display: flex;
            align-items: center;
            gap: 8px;
            color: #fff;
            text-decoration: none;
            padding: 8px 16px;
            border-radius: 12px;
            background: linear-gradient(135deg, #e74c3c 0%, #c0392b 100%);
            box-shadow: 0 2px 8px rgba(231, 76, 60, 0.2);
        }

        .welcome {
            background: white;
            padding: 25px;
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.05);
            margin-bottom: 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .welcome-content {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .welcome-icon { font-size: 2.5em; color: var(--primary-color); }

        .welcome-text h2 {
            font-size: 1.5em;
            color: var(--dark-color);
            margin-bottom: 5px;
        }

        .welcome-stats {
            display: flex;
            gap: 20px;
        }

        .stat-item {
            text-align: center;
            padding: 15px;
            background: var(--light-color);
            border-radius: 10px;
            min-width: 120px;
        }

        .task-form-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.05);
            margin-bottom: 30px;
            overflow: hidden;
        }

        .card-header {
            background: var(--primary-color);
            color: white;
            padding: 15px 25px;
        }

        .form-control {
            border: 2px solid transparent;
            padding: 12px;
            border-radius: 8px;
            width: 100%;
        }

        .submit-btn {
            background: var(--primary-color);
            color: white;
            padding: 12px 25px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .search-container { margin-bottom: 30px; }

        .search-form {
            background: white;
            padding: 20px;
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.05);
        }

        .search-input {
            width: 100%;
            padding: 12px;
            border: 1px solid #e1e1e1;
            border-radius: 8px;
            font-size: 14px;
        }

        .filter-options {
            display: flex;
            gap: 10px;
        }

        .filter-select {
            flex: 1;
            padding: 10px;
            border: 1px solid #e1e1e1;
            border-radius: 8px;
            font-size: 14px;
        }

        .table-container {
            background: white;
            padding: 20px;
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.05);
            overflow-x: auto;
        }

        .task-table {
            width: 100%;
            border-collapse: collapse;
            margin: 0;
        }

        .task-table th {
            background: #f8f9fa;
            padding: 15px;
            text-align: left;
            font-weight: 600;
            color: var(--dark-color);
        }

        .task-table td {
            padding: 15px;
            border-bottom: 1px solid #f1f1f1;
            vertical-align: middle;
        }

        .status-badge {
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 500;
            display: inline-flex;
            align-items: center;
            gap: 5px;
        }

        .status-warning { background: #fff3cd; color: #856404; }
        .status-success { background: #d4edda; color: #155724; }
        .status-danger { background: #f8d7da; color: #721c24; }

        .priority-badge {
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 12px;
            background: #e9ecef;
            color: var(--dark-color);
        }

        .action-buttons {s
            display: flex;
            gap: 10px;
        }

        .action-buttons a {
            width: 32px;
            height: 32px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 6px;
            text-decoration: none;
        }

        .edit-btn { background: var(--warning-color); color: white; }
        .delete-btn { background: var(--danger-color); color: white; }

        .no-tasks {
            text-align: center;
            color: #6c757d;
            padding: 30px;
            font-size: 1.1em;
        }

        @media (max-width: 768px) {
            .welcome { flex-direction: column; text-align: center; gap: 20px; }
            .welcome-content { flex-direction: column; }
            .welcome-stats { width: 100%; justify-content: center; }
            .form-row { flex-direction: column; }
            .filter-options { flex-direction: column; }
            .action-buttons { flex-direction: column; }
            .action-buttons a { width: 100%; }
            .nav-profile { padding: 8px 15px; }
            .brand-wrapper { padding: 6px 12px; }
            .username { font-size: 1rem; }
        }

        @media (max-width: 480px) {
            .navbar { padding: 10px 0; }
            .nav-profile { gap: 10px; }
            .logo-img { height: 30px; }
        }

        select.form-control {
            border: 1px solid #ced4da;
        }
    </style>