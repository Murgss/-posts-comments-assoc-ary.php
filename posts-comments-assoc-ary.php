<?php
// create database blog_17042025;
// use blog_17042025;
// create user sandis_17042025@localhost identified by 'password';
// grant all privileges  on blog_17042025.* to sandis_17042025@localhost;

$host = 'localhost';
$dbname = 'blog_17042025';
$username = 'sandis_17042025';
$password = 'password';

try {
    $conn = new PDO("mysql:host=$servername;dbname=myDB", $username, $password);
    // set the PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "Connected successfully";
  } catch(PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
  }

$stmt = $pdo->query("SELECT * FROM posts");
$posts = $stmt->fetchAll(PDO::FETCH_ASSOC);

  ?>