<?php
    session_start();
    include 'assets/scripts/config.php';
    include 'assets/scripts/functions.php'; 

    // Fetch user stats
    $userStats = ['totalPoints' => 0, 'rank' => 'N/A'];
    $userStats = fetchUserStats($conn, $user_id);

    // Fetch quiz details
    $quizId = isset($_GET['id']) ? (int)$_GET['id'] : 0;

    $quizQuery = "SELECT * FROM quiz WHERE id = ?";
    $stmt = $conn->prepare($quizQuery);
    $stmt->bind_param("i", $quizId);
    $stmt->execute();
    $quizResult = $stmt->get_result();

    if ($quizResult->num_rows === 0) {
        echo "Quiz not found.";
        exit;
    }

    $quiz = $quizResult->fetch_assoc();
    $questionsFile = 'assets/questions/' . $quiz['json_file'];

    if (!file_exists($questionsFile)) {
        echo "Questions file not found.";
        exit;
    }

    // Load questions from JSON file
    $questions = json_decode(file_get_contents($questionsFile), true);
    $totalQuestions = count($questions);
    $currentQuestion = isset($_GET['q']) ? (int)$_GET['q'] : 1;
    $currentQuestion = max(1, min($currentQuestion, $totalQuestions));

    $questionStatus = $_SESSION['question_status'] ?? array_fill(1, $totalQuestions, 'unvisited');
    $quizStarted = isset($_GET['start']) && $_GET['start'] == 1;
    $quizFinished = isset($_GET['finish']) && $_GET['finish'] == 1;

    if ($quizStarted && !isset($_SESSION['quiz_start_time'])) {
        $_SESSION['quiz_start_time'] = time();
    }

    $categoryId = $quiz['category'];

    // Fetch category name
    $categoryQuery = "SELECT name FROM categories WHERE id = ?";
    $stmt = $conn->prepare($categoryQuery);
    $stmt->bind_param("i", $categoryId);
    $stmt->execute();
    $stmt->bind_result($categoryName);
    $stmt->fetch();
    $stmt->close();

    $duration = $quiz['duration'];
    $durationInSeconds = strtotime($duration) - strtotime('TODAY');
    $durationInWords = formatDuration($quiz['duration']);

    if ($quizStarted) {
        $startTime = $_SESSION['quiz_start_time'];
        $currentTime = time();
        $timeRemaining = $durationInSeconds - ($currentTime - $startTime);

        if ($timeRemaining <= 0) {
            header("Location: quiz.php?id=$quizId&finish=1");
            exit;
        }
    }

    // Store answers temporary
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $action = $_POST['action'] ?? '';
        $questionNumber = (int)$_POST['question_number'];
        $userAnswer = $_POST['answer'] ?? '';

        $stmt = $conn->prepare("INSERT INTO temp_answers (user_id, quiz_id, question_number, answer, session_id)
                                VALUES (?, ?, ?, ?, ?)
                                ON DUPLICATE KEY UPDATE answer = VALUES(answer)");
        $stmt->bind_param("iiiss", $user_id, $quizId, $questionNumber, $userAnswer, $session_id);
        $stmt->execute();
        $stmt->close();

        switch ($action) {
            case 'clear':
                $questionStatus[$questionNumber] = 'unvisited';
                unset($_SESSION['answers'][$questionNumber]);
                break;
            case 'save':
                if (!empty($userAnswer)) {
                    $questionStatus[$questionNumber] = 'answered';
                    $_SESSION['answers'][$questionNumber] = $userAnswer;
                } else {
                    $questionStatus[$questionNumber] = 'not-attempted';
                }
                break;
            case 'submit':
                if (!empty($userAnswer)) {
                    $questionStatus[$questionNumber] = 'answered';
                    $_SESSION['answers'][$questionNumber] = $userAnswer;
                } else {
                    $questionStatus[$questionNumber] = 'not-attempted';
                }
                header("Location: quiz.php?id=$quizId&finish=1");
                exit;
            case 'mark':
                $questionStatus[$questionNumber] = 'review';
                $_SESSION['answers'][$questionNumber] = $userAnswer;
                break;
        }        

        $_SESSION['question_status'] = $questionStatus;

        // Question Navigation
        if ($action === 'save' || $action === 'mark') {
            $currentQuestion = min($currentQuestion + 1, $totalQuestions);
        } elseif ($action === 'previous') {
            $currentQuestion = max($currentQuestion - 1, 1);
        }

        header("Location: quiz.php?id=$quizId&start=1&q=$currentQuestion");
        exit;
    }

    if (isset($_GET['finish'])) {
        include 'assets/scripts/quiz_complete.php';
    } else {
        include 'assets/scripts/quiz_questions.php';
    }
?>