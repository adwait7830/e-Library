<?php
include ('db.php');
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (isset($_POST["id"])) {

        $stmt = $conn->prepare('SELECT title, description, author, cover, views FROM all_books WHERE id = ?');
        $stmt->bind_param('i',$_POST['id']);
        $stmt->execute();
        $stmt->bind_result($title,$description,$author,$cover, $views);
        $stmt->fetch();
        $stmt->close();
        
        $stmt3 = $conn->prepare('UPDATE all_books SET views = ? WHERE id = ?');
        $incrementedViews  = $views + 1;
        $stmt3->bind_param('ii',$incrementedViews,$_POST['id']);
        $stmt3->execute();
        $stmt3->close();
        $book = array(
            'title'=>$title,
            'author'=>$author,
            'description'=>$description,
            'cover'=>base64_encode($cover)
            
        );
        $jsonData = json_encode($book);
        header('Content-Type: application/json');
        echo $jsonData;
        
    } else {
        http_response_code(400); 
        echo "Error: 'id' parameter is missing in the request.";
    }

    if (isset($_POST['add-book'])) {

        $cover = addslashes(file_get_contents($_FILES['setCover']['tmp_name']));
        $title = $_POST['setTitle'];
        $author = $_POST['setAuthor'];
        $description = $_POST['setDescription'];
    
        $stmt = $conn->prepare('INSERT INTO all_books (cover, title, author, description) VALUES (?, ?, ?, ?)');
        $stmt->bind_param('bsss', $cover, $title, $author, $description);
    
        if ($stmt->execute()) {
          echo 'Book Added Successfully';
        } else {
          echo 'Error: ' . $stmt->error;
        }
      }
} else {
    http_response_code(405); 
    echo "Error: Invalid request method. Only POST requests are allowed.";
}
?>
