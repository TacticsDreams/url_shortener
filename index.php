<?php

include("db.php");

function generateShortURL() {
    $characters = "0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
    $shortURL = substr(str_shuffle($characters), 0, 8);
    return $shortURL;
}

if (isset($_POST['submit'])) {
    $originURL = $_POST['url'];

    try {
        $conn = connectDatabase();
        if ($conn) {
            if (filter_var($originURL, FILTER_VALIDATE_URL)) {
                $sql = "SELECT short_url FROM url_shortener
                        WHERE original_URL = :link";
                $stmt = $conn->prepare($sql);
                $stmt->execute([':link' => $originURL]);
                $result = $stmt->fetchColumn();

                if ($result) {
                    $shortURL = $result;
                } else {
                    $shortURL = generateShortURL();

                    $sql = "INSERT INTO url_shortener (original_url, short_url) VALUES (:origin, :short)";
                    $stmt = $conn->prepare($sql);
                    $stmt->execute([':origin' => $originURL, ':short' => $shortURL]);
                }
            } else {
                echo "URL Invalide!";
            }
        }
    } catch (PDOException $e) {
        echo "Erreur PDO: " . $e->getMessage();
    }
}

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>URL Shortener</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            height: 100%;
            overflow: auto;
        }
        h1 {
            text-align: center;
            position: relative;
        }
        section {
            display: block;
            padding: 2em 4em;
            margin: auto;
        }
        .section__text {
            text-align: center;
        }
        form {
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
            padding: 1em;
            background-color: #6cc4d0;
            width: 80%;
            margin: auto;
            box-shadow: 0 4px 8px rgba(0,0,0,0.3);
        }
        form > label {
            margin: 1em 0;
            font-weight: bold;
        }
        form > input {
            font-size: 20px;
            padding: 1em;
            margin: 1em 0;
            border-radius: 1em;
            border: none;
            width: 50%;
        }
        form > button {
            margin: 2em 0;
            padding: 1em;
            border-radius: 1em;
            border: none;
            box-shadow: 0 4px 8px rgba(0,0,0,0.3);
            transition: box-shadow .3s;
        }
        form > button:hover {
            cursor: pointer;
            box-shadow: 0 8px 16px rgba(0,0,0,0.3);
        }
        .output {
            text-align: center;
        }
    </style>
</head>
<body>
    <section>
        <h1>URL Shortener</h1>
        <p class="section__text">Si vous avez besoin de raccourir un URL pour n'importe quelle raison, cet outil est pour vous!</p>
        <p class="section__text">Entrez une URL dans le champ ci-dessous, cliquez sur le bouton, et voilà!</p>
        <p class="section__text">(Evitez les Rick Rolls, s'il vous plaît...)</p>
        <form method="post" action="">
            <label for="url">Entrez l'URL à raccourcir:</label>
            <input type="text" name="url" id="url" placeholder="Entrez ici l'URL à raccourcir..." required>
            <button type="submit" name="submit">Raccourcir l'URL</button>
        </form>
        <?php if (isset($shortURL)) { 
            $currentPageUrl = 'http://' . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"];
            $urlOutput = '<a href="./redirect.php?short='.$shortURL.'" target="_blank">'. $currentPageUrl . $shortURL .'</a>';?>
            <p class="output">URL Raccourci: <?php echo $urlOutput; ?></p>
    <?php } ?>
    </section>
</body>
</html>