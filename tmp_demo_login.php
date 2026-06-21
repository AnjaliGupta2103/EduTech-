<?php
$pdo = new PDO('mysql:host=localhost;dbname=edupulsedb;charset=utf8mb4', 'root', '');
$u = 'studentdemo3';
$p = 'Student@123';
$hash = password_hash($p, PASSWORD_DEFAULT);
$stmt = $pdo->prepare('INSERT INTO student (username, password, first_name, last_name, email, date_of_birth, date_of_joined, status, profile_img) VALUES (?, ?, ?, ?, ?, ?, CURRENT_DATE(), ?, ?)');
$stmt->execute([$u, $hash, 'Demo', 'Student3', 'demo3@student.com', '2000-01-03', 'Active', 'default.jpg']);
$row = $pdo->prepare('SELECT username, password FROM student WHERE username = ?');
$row->execute([$u]);
$data = $row->fetch(PDO::FETCH_ASSOC);

echo 'stored_user=' . $data['username'] . PHP_EOL;
echo 'verify=' . (password_verify($p, $data['password']) ? 'ok' : 'fail') . PHP_EOL;
echo 'hash=' . $data['password'] . PHP_EOL;
