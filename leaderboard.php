<?php
    session_start();
    include 'assets/scripts/config.php';
    include 'assets/scripts/functions.php';

    // Fetch user stats
    $userStats = ['totalPoints' => 0, 'rank' => 'N/A'];
    $userStats = fetchUserStats($conn, $user_id);

    // Fetch leaderboard
    $leaderboardQuery = "
        SELECT users.username, user_points.total_points, 
        RANK() OVER (ORDER BY user_points.total_points DESC) AS rank
        FROM (
            SELECT user_id, SUM(score) AS total_points
            FROM scores
            GROUP BY user_id
        ) AS user_points
        JOIN users ON user_points.user_id = users.id
        ORDER BY rank ASC";

    $stmt = $conn->prepare($leaderboardQuery);
    $stmt->execute();
    $leaderboardResult = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Leaderboard</title>
    <link rel="stylesheet" href="assets/stylesheets/style.css">
</head>
<body>    
    <header>
        <h1>Leaderboard</h1>
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
        <table>
            <tr>
                <th>Rank</th>
                <th>Username</th>
                <th>Points</th>
            </tr>
            <?php if ($leaderboardResult->num_rows > 0): ?>
                <?php while ($row = $leaderboardResult->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $row['rank']; ?></td>
                        <td><?php echo htmlspecialchars($row['username']); ?></td>
                        <td><?php echo number_format($row['total_points']); ?></td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="3">No data available.</td>
                </tr>
            <?php endif; ?>
        </table>
    </main>
</body>
</html>

<?php $conn->close(); ?>