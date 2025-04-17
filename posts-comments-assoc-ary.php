<?php
// create database blog_17042025;
// use blog_17042025;
// create user sandis_17042025@localhost identified by 'password';
// grant all privileges  on blog_17042025.* to sandis_17042025@localhost;

$servername = 'localhost';
$dbname = 'blog_17042025';
$username = 'sandis_17042025';
$password = 'password';

try {
    $conn = new PDO("mysql:host=$servername;dbname=blog_17042025", $username, $password);
    // set the PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "Connected successfully";
  } catch(PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
  }


  $sql = "SELECT userId FROM Posts_table";
  $result = $conn->query($sql);
  
//   if ($result->num_rows > 0) {
//     // output data of each row
//     while($row = $result->fetch_assoc()) {
//       echo "id: " . $row["userId"]. "<br>";
//     }
//   } else {
//     echo "0 results";
//   }
//   $conn->close();


$hierarchy = [];
while ($row = mysqli_fetch_assoc($result)) {
    $postId = $row['post_id'];

    if (!isset($hierarchicalData[$postId])) {
        $hierarchicalData[$postId] = [
            'id' => $row['postId'],
            'title' => $row['title'],
            'content' => $row['content'],
            'comments' => []
        ];
    }

    if ($row['comment_id'] !== null) {
        $hierarchicalData[$postId]['comments'][] = [
            'id' => $row['comment_id'],
            'text' => $row['comment_text']
        ];
    }
}

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

$posts = [];

foreach ($flatData as $row) {
    $postId = $row['post_id'];

    if (!isset($posts[$postId])) {
        $posts[$postId] = new Post($row['post_title'], $row['post_content']);
    }

    if (!empty($row['comment_id'])) {
        $comment = new Comment($row['comment_author'], $row['comment_content']);
        $posts[$postId]->addComment($comment);
    }
}

// Renderējam
echo "<ul>";
foreach ($posts as $post) {
    echo $post->render();
}
echo "</ul>";










  ?>