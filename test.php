<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    var_dump($_POST); // Dump the $_POST array for debugging purposes
    exit; // Terminate further processing for debugging purposes
}
?>