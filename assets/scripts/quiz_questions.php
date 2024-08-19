<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($quiz['title']); ?></title>
    <link rel="stylesheet" href="assets/stylesheets/style.css">
    <script src="assets/scripts/question_options.js" defer></script>
    <?php    
        if ($quizStarted) {
    ?>
        <script>
            let startTime = <?php echo $_SESSION['quiz_start_time']; ?>;
            let duration = <?php echo $durationInSeconds; ?>;
        </script>
        <script src="assets/scripts/countdown.js" defer></script>
    <?php
        }
    ?>
</head>
<body>
    <header>
        <?php if (!$quizStarted): ?>
            <h1>Let's Play!</h1>
            <div class="ranking">
                <a href="leaderboard.php">
                    <div class="item">
                        <div class="image-wrapper">
                            <img src="assets/media/images/trophy.png" alt="Icon">
                        </div>
                        <span class="title">Ranking</span>
                        <span class="value"><?php echo htmlspecialchars($userStats['rank']); ?></span>
                    </div>
                </a>
                <a href="history.php">
                    <div class="item">
                        <div class="image-wrapper">
                            <img src="assets/media/images/coin.png" alt="Icon">
                        </div>
                        <span class="title">Points</span>
                        <span class="value"><?php echo number_format($userStats['totalPoints']); ?></span>
                    </div>
                </a>
            </div>
            <div class="profile-wrapper">
                <div class="profile-image">
                    <img src="assets/media/images/profile-pic.jpg" alt="Profile Picture">
                </div>
                <div class="profile-option">
                    <a href="assets/scripts/logout.php">
                        <div class="item logout">Logout</div>
                    </a>
                </div>
            </div>
        <?php else: ?>
            <h1><?php echo htmlspecialchars($quiz['title']); ?></h1>
            <div class="timer">
                <div class="time-part">
                    <span class="title">Hours</span>
                    <div class="value">00</div>
                </div>
                <div class="time-part">
                    <span class="title">Minutes</span>
                    <div class="value">00</div>
                </div>
                <div class="time-part">
                    <span class="title">Seconds</span>
                    <div class="value">00</div>
                </div>
            </div>
        <?php endif; ?>
    </header>
    <?php if (!$quizStarted): ?>
        <main>
            <div class="quiz-info">
                <h1><?php echo htmlspecialchars($quiz['title']); ?></h1>
                <p><?php echo htmlspecialchars($quiz['description']); ?></p>
                <div class="row">
                    <span class="title">Category</span>
                    <span class="value"><?php echo htmlspecialchars($categoryName); ?></span>
                </div>
                <div class="row">
                    <span class="title">Total number of Questions</span>
                    <span class="value"><?php echo htmlspecialchars($totalQuestions); ?></span>
                </div>
                <div class="row">
                    <span class="title">Time Limit</span>
                    <span class="value"><?php echo htmlspecialchars($durationInWords); ?></span>
                </div>
                <div class="action-menu">
                    <a href="quiz.php?id=<?php echo $quizId; ?>&start=1">
                        <button class="submit">Start Quiz</button>
                    </a>
                </div>
            </div>
        </main>
    <?php else: ?>
        <main class="quiz-page">
            <form class="form-question" method="post">
                <div class="question-wrapper">
                    <div class="question">Q<?php echo $currentQuestion; ?>. <?php echo htmlspecialchars($questions[$currentQuestion - 1]['question']); ?></div>
                    <div class="option-wrapper">
                        <?php foreach ($questions[$currentQuestion - 1]['options'] as $option): ?>
                            <div class="option">
                                <input type="radio" name="answer" value="<?php echo htmlspecialchars($option); ?>" <?php echo isset($_SESSION['answers'][$currentQuestion]) && $_SESSION['answers'][$currentQuestion] == $option ? 'checked' : ''; ?>>
                                <span><?php echo htmlspecialchars($option); ?></span>
                            </div>
                        <?php endforeach; ?>
                        <input type="hidden" name="question_number" value="<?php echo $currentQuestion; ?>">
                    </div>
                </div>
                <div class="action-menu">
                    <button type="submit" name="action" value="previous">Previous</button>
                    <button type="submit" name="action" value="clear">Clear Response</button>
                    <button type="submit" name="action" value="mark" class="review">Mark for review</button>
                    <?php if ($currentQuestion == $totalQuestions): ?>
                        <button type="submit" name="action" value="submit" class="submit">Submit</button>
                    <?php else: ?>
                        <button type="submit" name="action" value="save" class="submit">Save & Next</button>
                    <?php endif; ?>
                </div>
            </form>
            <div class="question-tracker">
                <h2>Question Tracker</h2>
                <div class="question-number-wrapper">
                    <?php for ($i = 1; $i <= $totalQuestions; $i++): ?>
                        <div class="question-number" data-status="<?php echo ($currentQuestion == $i) ? 'current' : $questionStatus[$i]; ?>">
                            <?php echo $i; ?>
                        </div>
                    <?php endfor; ?>
                </div>
            </div>
        </main>
    <?php endif; ?>
</body>
</html>

<?php exit; ?>