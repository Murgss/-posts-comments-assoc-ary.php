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

$sql = "
    SELECT
        p.id AS post_id,
        p.title AS post_title,
        p.content AS post_content,
        c.id AS comment_id,
        c.author AS comment_author,
        c.content AS comment_content
    FROM posts p
    LEFT JOIN comments c ON p.id = c.post_id
    ORDER BY p.id, c.id
";
$flatData = $pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);


$hierarchy = [];

foreach ($flatData as $row) {
    $postId = $row['post_id'];

    if (!isset($hierarchy[$postId])) {
        $hierarchy[$postId] = [
            'title' => $row['post_title'],
            'content' => $row['post_content'],
            'comments' => []
        ];
    }

    if (!empty($row['comment_id'])) {
        $hierarchy[$postId]['comments'][] = [
            'author' => $row['comment_author'],
            'content' => $row['comment_content']
        ];
    }
}










  ?>