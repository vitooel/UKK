<?php
include('config.php');

$id = $_GET['id'];
$query = "DELETE FROM tasks WHERE id = $id";
mysqli_query($mysqli, $query);

header('Location: index.php');
exit();
