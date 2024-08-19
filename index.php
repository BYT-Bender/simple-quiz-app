<?php
    session_start();
    require_once 'assets/scripts/config.php';
    require_once 'assets/scripts/functions.php';

    // Fetch user stats
    $userStats = ['totalPoints' => 0, 'rank' => 'N/A'];
    $userStats = fetchUserStats($conn, $user_id);

    // Fetch categories
    $categoryQuery = "SELECT id, name FROM categories";
    if ($categoryResult = $conn->query($categoryQuery)) {
        // Fetch quizzes based on selected category
        $selectedCategory = isset($_GET['category']) ? (int)$_GET['category'] : 0;
        $quizQuery = "SELECT * FROM quiz";

        if ($selectedCategory > 0) {
            $quizQuery .= " WHERE category = ?";
        }
        if ($stmt = $conn->prepare($quizQuery)) {
            if ($selectedCategory > 0) {
                $stmt->bind_param("i", $selectedCategory);
            }
            $stmt->execute();
            $quizResult = $stmt->get_result();
        } else {
            error_log("Failed to prepare statement for fetching quizzes: " . $conn->error);
            $quizResult = [];
        }
    } else {
        error_log("Failed to fetch categories: " . $conn->error);
        $categoryResult = [];
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quiz App</title>
    <link rel="stylesheet" href="assets/stylesheets/style.css">
    <script src="assets/scripts/search.js" defer></script>
</head>
<body>
    <header>
        <div class="greeting">
            <?php if (isset($_SESSION['username'])): ?>
                <h1>Hi, <?php echo htmlspecialchars($_SESSION['username']); ?></h1>
                <p>Let's make this day productive</p>
            <?php else: ?>
                <p>Please <a href="login.php">login</a> to start a quiz.</p>
            <?php endif; ?>
        </div>
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
    </header>
    <main>
        <div class="quiz-wrapper">
            <div class="quiz-filter">
                <div class="search-bar">
                    <input id="search-input" type="text" value="" placeholder="Search for a quiz" onkeyup="filterQuizzes()">
                </div>
                <div class="category">
                    <form method="get" action="">
                        <select name="category" id="category" onchange="this.form.submit()">
                            <option value="">-- All Categories --</option>
                            <?php while ($category = $categoryResult->fetch_assoc()): ?>
                                <option value="<?php echo htmlspecialchars($category['id']); ?>" <?php echo ($selectedCategory == $category['id']) ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($category['name']); ?>
                                </option>
                            <?php endwhile; ?>
                        </select>
                    </form>
                </div>
            </div>
            <div class="quiz-container">
                <?php if ($quizResult->num_rows > 0): ?>
                    <?php while ($quiz = $quizResult->fetch_assoc()): ?>
                        <?php $jsonFilePath = 'assets/questions/' . $quiz['json_file'];

                            if (file_exists($jsonFilePath) && is_readable($jsonFilePath)) {
                                $jsonContent = file_get_contents($jsonFilePath);
                                $questionsArray = json_decode($jsonContent, true);
                                $numQuestions = is_array($questionsArray) ? count($questionsArray) : 0;
                            } else {
                                $numQuestions = 0;
                            }

                            $duration = formatDuration($quiz['duration']);
                        ?>
                        <a href="quiz.php?id=<?php echo htmlspecialchars($quiz['id']); ?>" class="quiz">
                            <h2><?php echo htmlspecialchars($quiz['title']); ?></h2>
                            <p><?php echo htmlspecialchars($quiz['description']); ?></p>
                            <div class="quiz-details">
                                <span><?php echo htmlspecialchars($numQuestions . ' Questions'); ?></span>
                                <span><?php echo htmlspecialchars($duration); ?></span>
                            </div>
                        </a>
                    <?php endwhile; ?>
                <?php else: ?>
                    <p>No quizzes available for the selected category.</p>
                <?php endif; ?>
            </div>
        </div>
    </main>
</body>
</html>

<?php $conn->close(); ?>
