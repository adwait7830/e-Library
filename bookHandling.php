<?php
include('db.php');

function convertToImageUrl($title)
{
    $title = preg_replace("/[^a-zA-Z0-9\s\-]/", "", $title);
    $title = str_replace(" ", "-", $title);
    $title = strtolower($title);

    return $title;
}
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $requestData = json_decode(file_get_contents('php://input'), true);

    if (isset($requestData['showById'])) {

        $stmt = $conn->prepare('SELECT title, description, author, cover, views FROM all_books WHERE id = ?');
        $stmt->bind_param('i', $requestData['showById']);
        $stmt->execute();
        $stmt->bind_result($title, $description, $author, $cover, $views);
        $stmt->fetch();
        $stmt->close();

        $stmt3 = $conn->prepare('UPDATE all_books SET views = ? WHERE id = ?');
        $incrementedViews  = $views + 1;
        $stmt3->bind_param('ii', $incrementedViews, $requestData['showById']);
        $stmt3->execute();
        $stmt3->close();
        $book = array(
            'title' => $title,
            'author' => $author,
            'description' => $description,
            'cover' => $cover

        );
        $jsonData = json_encode($book);
        header('Content-Type: application/json');
        echo $jsonData;
    }

    if (isset($_POST['addBook'])) {

        $cover = $_FILES['setCover'];
        $title = $_POST['setTitle'];
        $author = $_POST['setAuthor'];
        $description = $_POST['setDescription'];

        $target_dir = 'cover/';
        $cover_name = convertToImageUrl($title) . "." . pathinfo($cover["name"], PATHINFO_EXTENSION);
        $target_file = $target_dir . $cover_name;
        move_uploaded_file($cover["tmp_name"], $target_file);

        $stmt = $conn->prepare('INSERT INTO all_books (cover, title, author, description) VALUES (?, ?, ?, ?)');
        $stmt->bind_param('ssss', $target_file, $title, $author, $description);

        if ($stmt->execute()) {
            $stmt->close();
        } else {
            echo 'Error: ' . $stmt->error;
        }
    }

    if (isset($_POST['editBook'])) {
        $id = $_POST['id'];
        $cover = $_FILES['editCover'];
        $title = $_POST['editTitle'];
        $author = $_POST['editAuthor'];
        $description = $_POST['editDescription'];

        if ($cover["error"] !== 4) {
            $sql = "SELECT cover FROM all_books WHERE id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $stmt->bind_result($imageName);

            $stmt->fetch();
            $stmt->close();

            $imagePath = "cover/" . $imageName;
            unlink($imagePath);

            $target_dir = 'cover/';
            $cover_name = convertToImageUrl($title) . "." . pathinfo($cover["name"], PATHINFO_EXTENSION);
            $target_file = $target_dir . $cover_name;
            move_uploaded_file($cover["tmp_name"], $target_file);

            $stmt = $conn->prepare('UPDATE all_books SET cover = ?, title = ?, author = ?, description = ? WHERE id = ?');
            $stmt->bind_param('ssssi', $target_file, $title, $author, $description, $id);
        }else{

            $stmt = $conn->prepare('UPDATE all_books SET title = ?, author = ?, description = ? WHERE id = ?');
            $stmt->bind_param('sssi', $title, $author, $description, $id);

        }

        if ($stmt->execute()) {
            $stmt->close();
        } else {
            echo 'Error: ' . $stmt->error;
        }
    }



    if (isset($requestData['dltById'])) {

        $idToDelete = $requestData['dltById'];

        $sql = "SELECT cover FROM all_books WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $idToDelete);
        $stmt->execute();
        $stmt->bind_result($imageName);

        $stmt->fetch();
        $stmt->close();

        $imagePath = "cover/" . $imageName;
        unlink($imagePath);

        $deleteSql = "DELETE FROM all_books WHERE id = ?";
        $stmt = $conn->prepare($deleteSql);
        $stmt->bind_param("i", $idToDelete);
        $stmt->execute();
        $stmt->close();
    }
} else {
    http_response_code(405);
    header("Location:logged.php");
}
