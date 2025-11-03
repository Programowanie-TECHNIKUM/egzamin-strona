<?php
require_once '../connectdb.php';

session_start();
if (!isset($_SESSION['admin_username'])) {
    header("Location: index.php");
    exit;
}

$c = mysqli_connect($host, $user, $password, $database);

function getPhotoFromNumber($id, $quiz)
{
    global $c;
    $q = mysqli_query($c, "SELECT Zalacznik FROM $quiz WHERE Nr = '" . mysqli_real_escape_string($c, $id) . "'");
    if ($row = mysqli_fetch_assoc($q)) {
        return $row['Zalacznik'];
    } else {
        return null;
    }
}


?>

<!DOCTYPE html>
<html lang="pl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel Administracyjny - Quiz</title>
    <link rel="stylesheet" href="../bootstrap/css/bootstrap.min.css">
</head>

<body class="bg-light">

    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="#">
                <i class="fas fa-graduation-cap"></i> Panel Administracyjny Quiz
            </a>
            <div class="navbar-nav ms-auto">
                <span class="navbar-text me-3">
                    Witaj, <?php echo htmlspecialchars($_SESSION['admin_username']); ?>!
                </span>
                <a href="logout.php" class="btn btn-outline-light btn-sm">
                    Wyloguj się
                </a>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <div class="row mb-4">
            <div class="col-12">
                <h1 class="display-4 text-center mb-0">Dashboard Administratora</h1>
                <p class="text-center text-muted">Zarządzaj wynikami quizów i monitoruj postępy użytkowników</p>
            </div>
        </div>

        <div class="bg-light p-4 rounded mb-4">
            <h4 class="mb-3">
                <i class="fas fa-user-search"></i> Wybierz użytkownika
            </h4>
            <form method="get" class="row g-3">
                <div class="col-md-8">
                    <select name="user_select" class="form-select form-select-lg" required>
                        <option value="">-- Wybierz użytkownika --</option>
                        <?php
                        $users = mysqli_query($c, "SELECT DISTINCT Username FROM Wyniki ORDER BY Username");
                        while ($row = mysqli_fetch_assoc($users)) {
                            $selected = (isset($_GET['user_select']) && $_GET['user_select'] === $row['Username']) ? 'selected' : '';
                            echo "<option value='" . htmlspecialchars($row['Username']) . "' $selected>" . htmlspecialchars($row['Username']) . "</option>";
                        }
                        ?>
                    </select>
                </div>
                <div class="col-md-4">
                    <button type="submit" class="btn btn-primary btn-lg w-100">
                        <i class="fas fa-search"></i> Pokaż wyniki
                    </button>
                </div>
            </form>
        </div>

        <?php
        if (isset($_GET['user_select']) && !empty($_GET['user_select'])) {
            $selectedUser = $_GET['user_select'];
            $results = mysqli_query($c, "SELECT * FROM Wyniki WHERE Username = '" . mysqli_real_escape_string($c, $selectedUser) . "' ORDER BY timestamp DESC");
            $totalExams = mysqli_num_rows($results);
            $passedExams = mysqli_num_rows(mysqli_query($c, "SELECT * FROM Wyniki WHERE Username = '" . mysqli_real_escape_string($c, $selectedUser) . "' AND Procenty >= 50"));

            if ($totalExams > 0) {
                ?>
                <div class="row mb-4">
                    <div class="col-12">
                        <div class="card bg-primary text-white">
                            <div class="card-body text-center">
                                <h3 class="card-title">
                                    <i class="fas fa-user"></i> <?php echo htmlspecialchars($selectedUser); ?>
                                </h3>
                                <div class="row">
                                    <div class="col-md-4">
                                        <h2 class="display-4"><?php echo $totalExams; ?></h2>
                                        <p class="mb-0">Łączna liczba prób</p>
                                    </div>
                                    <div class="col-md-4">
                                        <h2 class="display-4"><?php echo $passedExams; ?></h2>
                                        <p class="mb-0">Zdanych egzaminów</p>
                                    </div>
                                    <div class="col-md-4">
                                        <h2 class="display-4"><?php echo round(($passedExams / $totalExams) * 100, 1); ?>%</h2>
                                        <p class="mb-0">Wskaźnik zdawalności</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <h3 class="mb-4">
                    <i class="fas fa-clipboard-list"></i> Historia egzaminów
                </h3>

                <?php
                mysqli_data_seek($results, 0);
                while ($row = mysqli_fetch_assoc($results)) {
                    $cardClass = $row['Procenty'] >= 50 ? 'border-success' : 'border-danger';
                    $badgeClass = $row['Procenty'] >= 50 ? 'bg-success' : 'bg-danger';
                    $statusText = $row['Procenty'] >= 50 ? 'ZDANY' : 'NIEZDANY';
                    ?>
                    <div class="card mb-3 <?php echo $cardClass; ?> border-start border-4">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">
                                <i class="fas fa-quiz"></i> Quiz: <?php echo htmlspecialchars($row['Quiz']); ?>
                            </h5>
                            <span class="badge <?php echo $badgeClass; ?> fs-6"><?php echo $statusText; ?></span>
                        </div>
                        <div class="card-body">
                            <div class="row mb-3">
                                <div class="col-md-3">
                                    <strong><i class="fas fa-calendar"></i> Data i godzina:</strong><br>
                                    <span class="text-muted"><?php echo date('d.m.Y H:i', strtotime($row['timestamp'])); ?></span>
                                </div>
                                <div class="col-md-3">
                                    <strong><i class="fas fa-percentage"></i> Procenty:</strong><br>
                                    <span class="text-muted"><?php echo htmlspecialchars($row['Procenty']); ?>%</span>
                                </div>
                                <div class="col-md-3">
                                    <strong><i class="fas fa-check-circle"></i> Poprawne odpowiedzi:</strong><br>
                                    <span class="text-muted"><?php echo htmlspecialchars($row['Wynik']); ?></span>
                                </div>
                                <div class="col-md-3">
                                    <div class="progress">
                                        <div class="progress-bar <?php echo $row['Procenty'] >= 50 ? 'bg-success' : 'bg-danger'; ?>"
                                            style="width: <?php echo $row['Procenty']; ?>%">
                                            <?php echo $row['Procenty']; ?>%
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="table-responsive">
                                <h6><i class="fas fa-list"></i> Szczegółowe odpowiedzi:</h6>
                                <table class="table table-sm table-striped">
                                    <thead class="table-dark">
                                        <tr>
                                            <th>Nr</th>
                                            <th>Pytanie</th>
                                            <th>Odpowiedź użytkownika</th>
                                            <th>Prawidłowa odpowiedź</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $userAnswersJson = json_decode($row['Wyniki'], true);
                                        if ($userAnswersJson) {
                                            foreach ($userAnswersJson as $questionNr => $questionData) {
                                                $statusIcon = $questionData['is_correct'] ?
                                                    '<i class="fas fa-check-circle text-success"></i>' :
                                                    '<i class="fas fa-times-circle text-danger"></i>';
                                                ?>
                                                <tr class="<?php echo $questionData['is_correct'] ? 'table-success' : 'table-danger'; ?>">
                                                    <td><?php echo htmlspecialchars($questionNr); ?></td>
                                                    <td class="text-truncate" style="max-width: 200px;"
                                                        title="<?php echo htmlspecialchars($questionData['question_text']); ?>">
                                                        <?php echo htmlspecialchars(substr($questionData['question_text'], 0, 50)) . '...'; ?>
                                                        <?php
                                                        $zalacznik = getPhotoFromNumber($questionNr, $row['Quiz']);
                                                        if ($zalacznik != null) {
                                                            $zalacznikPath = '../images/' . $row['Quiz'] . '/' . $zalacznik;
                                                            echo "<br><img src='" . $zalacznikPath . "' alt='Obrazek do pytania' class='img-fluid mt-2' style='max-width: 100px;'/>";
                                                        }


                                                        ?>

                                                    </td>
                                                    <td>
                                                        <strong><?php echo htmlspecialchars($questionData['user_answer']); ?></strong><br>
                                                        <small
                                                            class="text-muted"><?php echo htmlspecialchars($questionData['user_answer_text']); ?></small>
                                                    </td>
                                                    <td>
                                                        <strong><?php echo htmlspecialchars($questionData['correct_answer']); ?></strong><br>
                                                        <small
                                                            class="text-muted"><?php echo htmlspecialchars($questionData['correct_answer_text']); ?></small>
                                                    </td>
                                                    <td class="text-center"><?php echo $statusIcon; ?></td>
                                                </tr>
                                                <?php
                                            }
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <?php
                }
            } else {
                ?>
                <div class="alert alert-info" role="alert">
                    <i class="fas fa-info-circle"></i> Wybrany użytkownik nie ma jeszcze żadnych wyników egzaminów.
                </div>
                <?php
            }
        }
        ?>
    </div>

    <script src="../bootstrap/js/bootstrap.bundle.min.js"></script>
</body>

</html>