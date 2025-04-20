<?php
$mysqli = mysqli_connect('localhost', 'root', '', 'todolist');
mysqli_select_db($mysqli, 'todolist') or die("database tidak ditemukan");
