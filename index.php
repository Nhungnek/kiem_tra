<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>

<body>
    <header>
        <nav class="navbar navbar-expand-lg bg-body-tertiary">
            <div class="container-fluid">
                <a class="navbar-brand" href="index.php">PHP Example</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavAltMarkup"
                    aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
                    <div class="navbar-nav">
                        <a class="nav-link active" aria-current="page" href="index.php">Home</a>
                    </div>
                </div>
            </div>
        </nav>
    </header>

    <div class="container my-3">
        <form class="row" method="POST" enctype="multipart/form-data">
            <div class="col">
                <div class="mb-3">
                    <input type="file" accept=".txt" class="form-control" name="file">
                </div>
                <button type="submit" class="btn btn-primary" name="submit">Import Database</button>
            </div>
        </form>

        <?php
        if (isset($_POST['submit'])) {
            if (isset($_FILES['file']) && $_FILES['file']['error'] == 0) {
                $fileTmpPath = $_FILES['file']['tmp_name'];
                $fileType = $_FILES['file']['type'];
        
                if ($fileType == 'text/plain') {
                    $server = "localhost";
                    $database = "db_tran_thi_hong_nhung";
                    $username = "root";
                    $password = "";
        
                    $conn = new mysqli($server, $username, $password, $database);
                    if ($conn->connect_error) {
                        die('Connection failed: ' . $conn->connect_error);
                    }
        
                    $successfulInserts = 0;
                    $failedInserts = 0;
        
                    $file = fopen($fileTmpPath, "r");
                    if ($file) {
                        while (($line = fgets($file)) !== false) {
                            $line = str_replace('"', '', $line);
                            list($title, $description, $imageUrl) = explode(",", trim($line));
        
                            $title = $conn->real_escape_string($title);
                            $description = $conn->real_escape_string($description);
                            $imageUrl = $conn->real_escape_string($imageUrl);
        
                            $checkQuery = "SELECT * FROM Course WHERE Title = '$title'";
                            $checkResult = $conn->query($checkQuery);
        
                            if ($checkResult->num_rows == 0) {
                                $insertQuery = "INSERT INTO Course (Title, Description, ImageUrl) VALUES ('$title', '$description', '$imageUrl')";
                                if ($conn->query($insertQuery) === TRUE) {
                                    $successfulInserts++;
                                } else {
                                    $failedInserts++;
                                }
                            } else {
                                $failedInserts++;
                            }
                        }
                        fclose($file);
                    }
        
                    echo "<div class='alert alert-info mt-2' role='alert'>";
                    echo "$successfulInserts records inserted successfully, $failedInserts records failed to insert.";
                    echo "</div>";
                    $conn->close();
                } else {
                    echo '<div class="alert alert-warning" role="alert">Please upload a .txt file!</div>';
                }
            } else {
                echo '<div class="alert alert-danger" role="alert">File upload error!</div>';
            }
        }
        
        ?>

        <hr>

        <?php
        $conn = new mysqli($server, $username, $password, $database);

        if ($conn->connect_error) {
            die('Connection failed: ' . $conn->connect_error);
        }

        $query = "SELECT * FROM Course";
        $result = $conn->query($query);

        echo '<div class="row row-cols-1 row-cols-md-2 g-4">';
        if ($result->num_rows > 0) {
            while ($course = $result->fetch_assoc()) {
                echo "<div class='col'>
                        <div class='card'>
                            <img src='" . htmlspecialchars($course['ImageUrl']) . "' class='card-img-top' alt='" . htmlspecialchars($course['Title']) . "'>
                            <div class='card-body'>
                                <h5 class='card-title'>" . htmlspecialchars($course['Title']) . "</h5>
                                <p class='card-text'>" . htmlspecialchars($course['Description']) . "</p>
                            </div>
                        </div>
                    </div>";
            }
        } else {
            echo "<p>No records found.</p>";
        }
        echo '</div>';

        $conn->close();
        ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>

</html>
