<?php

require_once 'connectdb.php';

$ilePytan = 20;

session_start();
$_SESSION['quiz_type'] = $_POST['quiz_type'];
$_SESSION['username'] = $_POST['username'];

?>

<!DOCTYPE html>
<html lang="pl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quiz - <?php echo htmlspecialchars($_SESSION['quiz_type']); ?></title>
    <link rel="stylesheet" href="style.css">
</head>

<body class="bg-light">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card mt-4 shadow">
                    <div class="card-header bg-primary text-white">
                        <h2 class="mb-0">Quiz <?php echo htmlspecialchars($_SESSION['quiz_type']); ?></h2>
                        <p class="mb-0">Witaj, <?php echo htmlspecialchars($_SESSION['username']); ?>!</p>
                    </div>
                    <div class="card-body">
                        <form method='post' action='submit_quiz.php'>
                            <?php
                            function printQuestion($question)
                            {
                                if ($question['Zalacznik'] != NULL) {
                                    $zalacznikPath = './images/' . $_SESSION['quiz_type'] . '/' . $question['Zalacznik'];
                                } else {
                                    $zalacznikPath = '';
                                }


                                $questionId = htmlspecialchars($question['Nr']);
                                echo "<div class='mb-4 p-3 border rounded bg-white'>";
                                echo "<h5 class='text-primary mb-3'>Pytanie " . $questionId . "</h5>";
                                if ($zalacznikPath != '') {
                                    echo "<img src='" . $zalacznikPath . "' alt='Obrazek do pytania' class='img-fluid mb-3'>";
                                }
                                echo "<p class='fw-bold'>" . htmlspecialchars($question['Pytanie']) . "</p>";
                                echo "<div class='ms-3'>";
                                echo "<div class='form-check'>";
                                echo "<input class='form-check-input' type='radio' name='" . $questionId . "' value='A' id='q" . $questionId . "a' required>";
                                echo "<label class='form-check-label' for='q" . $questionId . "a'>" . htmlspecialchars($question['Odp_a']) . "</label>";
                                echo "</div>";
                                echo "<div class='form-check'>";
                                echo "<input class='form-check-input' type='radio' name='" . $questionId . "' value='B' id='q" . $questionId . "b' required>";
                                echo "<label class='form-check-label' for='q" . $questionId . "b'>" . htmlspecialchars($question['Odp_b']) . "</label>";
                                echo "</div>";
                                echo "<div class='form-check'>";
                                echo "<input class='form-check-input' type='radio' name='" . $questionId . "' value='C' id='q" . $questionId . "c' required>";
                                echo "<label class='form-check-label' for='q" . $questionId . "c'>" . htmlspecialchars($question['Odp_c']) . "</label>";
                                echo "</div>";
                                echo "<div class='form-check'>";
                                echo "<input class='form-check-input' type='radio' name='" . $questionId . "' value='D' id='q" . $questionId . "d' required>";
                                echo "<label class='form-check-label' for='q" . $questionId . "d'>" . htmlspecialchars($question['Odp_d']) . "</label>";
                                echo "</div>";
                                echo "</div>";
                                echo "</div>";
                            }

                            $c = mysqli_connect($host, $user, $password, $database);
                            $q = mysqli_query($c, "SELECT * FROM " . $_SESSION['quiz_type'] . " ORDER BY RAND() LIMIT " . $ilePytan);

                            while ($row = mysqli_fetch_assoc($q)) {
                                printQuestion($row);
                            }
                            ?>
                            <div class="text-center mt-4">
                                <button type='submit' class='btn btn-success btn-lg px-5'>Zakończ Quiz</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>