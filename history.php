<?php
    session_start();
    require_once 'assets/scripts/config.php';
    require_once 'assets/scripts/functions.php';

    // Fetch user stats
    $userStats = ['totalPoints' => 0, 'rank' => 'N/A'];
    $userStats = fetchUserStats($conn, $user_id);

    // Fetch quiz history
    $query = "
        SELECT s.id, q.title, s.score, s.total_questions, s.correct_answers, s.quiz_date
        FROM scores s
        JOIN quiz q ON s.quiz_id = q.id
        WHERE s.user_id = ?
        ORDER BY s.quiz_date DESC
    ";
    
    if ($stmt = $conn->prepare($query)) {
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
    } else {
        error_log("Failed to prepare statement for fetching quiz history: " . $conn->error);
        $result = [];
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Quiz History</title>
    <link rel="stylesheet" href="assets/stylesheets/style.css">
</head>
<body>
    <header>
        <h1>Quiz History</h1>
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
        <?php if (isset($result) && $result->num_rows > 0): ?>
            <table>
                <tr>
                    <th>Quiz</th>
                    <th>Correct Answers</th>
                    <th>Score</th>
                    <th>Date</th>
                </tr>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['title']); ?></td>
                        <td><?php echo htmlspecialchars($row['score'] . '/' . $row['total_questions']); ?></td>
                        <td><?php echo htmlspecialchars($row['correct_answers']); ?></td>
                        <td><?php echo htmlspecialchars($row['quiz_date']); ?></td>
                    </tr>
                <?php endwhile; ?>
            </table>
        <?php else: ?>
            <p>No quiz attempts found.</p>
        <?php endif; ?>
    </main>
</body>
</html>

<?php
    if (isset($stmt)) {
        $stmt->close();
    }
    $conn->close();
?>