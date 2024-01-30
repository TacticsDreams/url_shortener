<?php
include('db.php');

if (isset($_GET['short'])) {
    $shortURL = $_GET['short'];
    try {
        $conn = connectDatabase();

        if ($conn) {
            $sql = "SELECT original_url FROM url_shortener WHERE short_url = :short";
            $stmt = $conn->prepare($sql);
            $stmt->execute([':short' => $shortURL]);
            $result = $stmt->fetchColumn();


            if($result) {
                $originalURL = $result;

                header("Location: ". $originalURL);
                exit();
            } else {
                echo "<p>URL introuvable. Retournez à la <a href='index.php'>page principale</a>.</p>";
            }
        }
    } catch (PDOException $e) {
        echo "Erreur PDO: " . $e->getMessage();
    }
}
?>