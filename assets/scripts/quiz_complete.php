<?php
    // Calculate score
    $score = 0;
    $correctAnswers = 0;

    foreach ($questions as $index => $question) {
        $qNum = $index + 1;
        if (isset($_SESSION['answers'][$qNum]) && isset($question['correct_answer'])) {
            if ($_SESSION['answers'][$qNum] == $question['correct_answer']) {
                $correctAnswers++;
                $score++;
            }
        }
    }

    // Store final score
    $stmt = $conn->prepare("INSERT INTO scores (user_id, quiz_id, score, total_questions, correct_answers)
                            VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("iiiii", $user_id, $quizId, $score, $totalQuestions, $correctAnswers);
    $stmt->execute();

    // Clean up temp_answers
    $stmt = $conn->prepare("DELETE FROM temp_answers WHERE user_id = ? AND session_id = ?");
    $stmt->bind_param("is", $user_id, $session_id);
    $stmt->execute();

    $_SESSION['question_status'] = array_fill(1, $totalQuestions, 'unvisited');
    unset($_SESSION['answers'], $_SESSION['quiz_start_time'],);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quiz Completed</title>
    <link rel="stylesheet" href="assets/stylesheets/style.css">
    <script src="assets/scripts/library/confetti.js"></script>
    <script src="assets/scripts/confetti.js" defer></script>
</head>
<body>
    <main class="celebrate">
        <h1><?php echo htmlspecialchars($quiz['title']); ?></h1>
        <div class="celebrate-wrapper">
            <div class="image-wrapper">
                <img src="assets/media/images/rewards.png" alt="">
            </div>
            <h1>Congratulations!</h1>
            <h2>Quiz Completed</h2>
            <div class="score">You got <?php echo $score; ?> out of <?php echo $totalQuestions; ?> question correct!</div>
        </div>
        <a href="index.php">
            <button class="back-btn">Return to home</button>
        </a>
    </main>
</body>
</html>

<?php exit; ?>