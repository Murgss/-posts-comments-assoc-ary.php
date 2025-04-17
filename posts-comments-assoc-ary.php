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
echo "<ul>";
foreach ($hierarchy as $post) {
    echo "<li><strong>{$post['title']}</strong><br>{$post['content']}";

    if (!empty($post['comments'])) {
        echo "<ul>";
        foreach ($post['comments'] as $comment) {
            echo "<li><em>{$comment['author']}:</em> {$comment['content']}</li>";
        }
        echo "</ul>";
    }
    echo "</li>";
}
echo "</ul>";

class Comment {
    public string $author;
    public string $content;

    public function __construct($author, $content) {
        $this->author = $author;
        $this->content = $content;
    }

    public function render(): string {
        return "<li><em>{$this->author}:</em> {$this->content}</li>";
    }
}

class Post {
    public string $title;
    public string $content;
    public array $comments = [];

    public function __construct($title, $content) {
        $this->title = $title;
        $this->content = $content;
    }

    public function addComment(Comment $comment) {
        $this->comments[] = $comment;
    }

    public function render(): string {
        $html = "<li><strong>{$this->title}</strong><br>{$this->content}";

        if (!empty($this->comments)) {
            $html .= "<ul>";
            foreach ($this->comments as $comment) {
                $html .= $comment->render();
            }
            $html .= "</ul>";
        }

        $html .= "</li>";
        return $html;
    }
}










  ?>