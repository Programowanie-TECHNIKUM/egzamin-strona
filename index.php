<?php
session_start();
?>

<!DOCTYPE html>
<html lang="pl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Egzamin - Strona Główna</title>
    <link rel="stylesheet" href="style.css">
</head>

<body class="bg-light">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-5">
                <div class="card mt-5 shadow">
                    <div class="card-body p-4">
                        <h1 class="card-title text-center mb-4 text-primary">Egzamin</h1>
                        <form method="post" action="./quiz.php">
                            <div class="mb-3">
                                <label for="username" class="form-label">Imię i nazwisko</label>
                                <input type="text" id="username" name="username" class="form-control"
                                    placeholder="Podaj swoje imię i nazwisko" required />
                            </div>
                            <div class="mb-4">
                                <label for="quiz_type" class="form-label">Wybierz typ egzaminu</label>
                                <select name="quiz_type" id="quiz_type" class="form-select">
                                    <option value="inf03">INF03</option>
                                    <option value="inf04">INF04</option>
                                </select>
                            </div>
                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary btn-lg">
                                    Rozpocznij Egzamin
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>