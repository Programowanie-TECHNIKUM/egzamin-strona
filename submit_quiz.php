<!DOCTYPE html>
<html lang="pl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Wyniki Quiz</title>
    <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
</head>

<body>
    <div class="container mt-4">

        <?php
        require_once 'connectdb.php';
        session_start();

        if (!isset($_SESSION['username']) || !isset($_SESSION['quiz_type'])) {
            echo '<div class="alert alert-danger" role="alert">';
            echo "Brak danych sesji. Proszę rozpocząć quiz ponownie.";
            echo '</div>';
            exit;
        }

        $userAnswers = array();

        foreach ($_POST as $questionNumber => $answer) {
            if (is_numeric($questionNumber)) {
                $userAnswers[$questionNumber] = $answer;
            }
        }

        // tworzenie JSONA z odpowiedziami uzytkownika
        $userAnswersJson = array();
        $c = mysqli_connect($host, $user, $password, $database);

        foreach ($userAnswers as $questionNr => $userAnswer) {
            $correctAnswer = getOkayAnswer($c, $_SESSION['quiz_type'], $questionNr);
            $questionText = getQuestionText($c, $_SESSION['quiz_type'], $questionNr);
            $userAnswerText = getAnswerById($c, $_SESSION['quiz_type'], $questionNr, $userAnswer);
            $correctAnswerText = getAnswerById($c, $_SESSION['quiz_type'], $questionNr, $correctAnswer);
            $isCorrect = ($userAnswer === $correctAnswer);

            $userAnswersJson[$questionNr] = array(
                'question_text' => $questionText,
                'user_answer' => $userAnswer,
                'user_answer_text' => $userAnswerText,
                'correct_answer' => $correctAnswer,
                'correct_answer_text' => $correctAnswerText,
                'is_correct' => $isCorrect
            );
        }

        // conwertowanie jSON w string
        $userAnswersJsonString = json_encode($userAnswersJson, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);

        echo '<div class="jumbotron jumbotron-fluid bg-primary text-white mb-4">';
        echo '<div class="container">';
        echo '<h1 class="display-4">Wyniki Quiz</h1>';
        echo '<p class="lead">Witaj, ' . htmlspecialchars($_SESSION['username']) . '! Oto Twoje wyniki quizu typu <strong>' . htmlspecialchars($_SESSION['quiz_type']) . '</strong>.</p>';
        echo '</div>';
        echo '</div>';

        $correctAnswers = 0;
        foreach ($userAnswersJson as $questionData) {
            if ($questionData['is_correct']) {
                $correctAnswers++;
            }
        }

        $percents = round(($correctAnswers / count($userAnswers)) * 100, 2);

        if ($percents >= 50) {
            $summaryColor = "alert-success";
        } else {
            $summaryColor = "alert-danger";
        }

        echo "<div class='alert " . $summaryColor . "' role='alert'>";
        echo "<h2>Podsumowanie:</h2>";
        echo "<h3>Wynik: " . $correctAnswers . " / " . count($userAnswers) . " poprawnych odpowiedzi</h3>";
        echo "<p>Procent poprawnych odpowiedzi: " . $percents . "%</p>";
        echo "</div>";

        function getQuestionText($c, $quizType, $questionNr)
        {
            $query = "SELECT Pytanie FROM " . $quizType . " WHERE Nr = " . intval($questionNr);
            $result = mysqli_query($c, $query);

            if ($result && $row = mysqli_fetch_assoc($result)) {
                return $row['Pytanie'];
            } else {
                return "Nieznane pytanie";
            }
        }

        function getAnswerById($c, $quizType, $questionNr, $letter)
        {
            $columnMap = array(
                'A' => 'Odp_a',
                'B' => 'Odp_b',
                'C' => 'Odp_c',
                'D' => 'Odp_d'
            );

            if (!array_key_exists($letter, $columnMap)) {
                return "Nieznana odpowiedź";
            }

            $query = "SELECT " . $columnMap[$letter] . " AS Answer FROM " . $quizType . " WHERE Nr = " . intval($questionNr);
            $result = mysqli_query($c, $query);

            if ($result && $row = mysqli_fetch_assoc($result)) {
                return $row['Answer'];
            } else {
                return "Brak odpowiedzi";
            }
        }

        function getOkayAnswer($c, $quizType, $questionNr)
        {
            $query = "SELECT Ok FROM " . $quizType . " WHERE Nr = " . intval($questionNr);
            $result = mysqli_query($c, $query);

            if ($result && $row = mysqli_fetch_assoc($result)) {
                return $row['Ok'];
            } else {
                return "Brak prawidlowej odpowiedzi";
            }
        }

        echo "<div class='card mt-4'>";
        echo "<div class='card-header'>";
        echo "<h3 class='mb-0'>Szczegółowe wyniki</h3>";
        echo "</div>";
        echo "<div class='card-body'>";
        echo "<div class='table-responsive'>";
        echo "<table class='table table-striped table-hover'>";
        echo "<thead class='thead-dark'>";
        echo "<tr>";
        echo "<th scope='col'>Nr pytania</th>";
        echo "<th scope='col'>Treść pytania</th>";
        echo "<th scope='col'>Twoja odpowiedź</th>";
        echo "<th scope='col'>Prawidłowa odpowiedź</th>";
        echo "<th scope='col'>Status</th>";
        echo "</tr>";
        echo "</thead>";
        echo "<tbody>";

        foreach ($userAnswersJson as $questionNr => $questionData) {
            $rowClass = $questionData['is_correct'] ? "table-success" : "table-danger";
            $statusIcon = $questionData['is_correct'] ?
                "<span class='badge badge-success'><i class='fas fa-check'></i> Poprawne</span>" :
                "<span class='badge badge-danger'><i class='fas fa-times'></i> Niepoprawne</span>";

            echo "<tr class='$rowClass'>";
            echo "<th scope='row'>" . htmlspecialchars($questionNr) . "</th>";
            echo "<td>" . htmlspecialchars($questionData['question_text']) . "</td>";
            echo "<td><strong>" . htmlspecialchars($questionData['user_answer']) . "</strong> - " . htmlspecialchars($questionData['user_answer_text']) . "</td>";
            echo "<td><strong>" . htmlspecialchars($questionData['correct_answer']) . "</strong> - " . htmlspecialchars($questionData['correct_answer_text']) . "</td>";
            echo "<td>" . $statusIcon . "</td>";
            echo "</tr>";
        }

        echo "</tbody></table>";
        echo "</div>";
        echo "</div>";
        echo "</div>";

        // Dodanie przycisku powrotu
        echo "<div class='text-center mt-4 mb-4'>";
        echo "<a href='index.php' class='btn btn-primary btn-lg'>";
        echo "<i class='fas fa-home'></i> Powrót do strony głównej";
        echo "</a>";
        echo "</div>";

        $timestamp = date('Y-m-d H:i:s');
        $stmt = $c->prepare("INSERT INTO Wyniki (Username, Quiz, Wyniki, Procenty, Wynik, Timestamp) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssiis", $_SESSION['username'], $_SESSION['quiz_type'], $userAnswersJsonString, $percents, $correctAnswers, $timestamp);
        $stmt->execute();
        mysqli_close($c);
        session_destroy();
        ?>

    </div>
</body>

</html>