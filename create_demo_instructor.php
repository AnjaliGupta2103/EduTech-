<?php
$pdo = new PDO('mysql:host=localhost;dbname=EduPulseDB;charset=utf8', 'root', '');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$username = 'demo_instructor';
$password = password_hash('Instructor123!', PASSWORD_DEFAULT);
$first = 'Demo';
$last = 'Instructor';
$email = 'demo.instructor@edupulse.com';
$dateOfBirth = '1995-01-15';
$status = 'Active';
$profileImg = 'default.jpg';

$sql = 'INSERT INTO instructor (username, password, first_name, last_name, email, date_of_birth, date_of_joined, status, profile_img) VALUES (?, ?, ?, ?, ?, ?, CURDATE(), ?, ?)';
$stmt = $pdo->prepare($sql);
$res = $stmt->execute([$username, $password, $first, $last, $email, $dateOfBirth, $status, $profileImg]);

echo $res ? 'INSERT_OK' : 'INSERT_FAIL';
