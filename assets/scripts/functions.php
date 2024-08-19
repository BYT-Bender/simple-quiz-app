<?php

    if (!isset($_SESSION['username']) || !isset($_SESSION['user_id'])) {
        header('Location: login.php');
        exit;
    }

    $session_id = session_id();
    $user_id = $_SESSION['user_id'];

    // Fetch total points and rank
    function fetchUserStats($conn, $userId) {
        // Validate the connection
        if (!$conn instanceof mysqli) {
            throw new InvalidArgumentException('Invalid database connection.');
        }

        $totalPoints = 0;
        $rank = null;

        // Fetch total points
        $pointsQuery = "SELECT COALESCE(SUM(score), 0) AS total_points FROM scores WHERE user_id = ?";
        if ($stmt = $conn->prepare($pointsQuery)) {
            $stmt->bind_param("i", $userId);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($row = $result->fetch_assoc()) {
                $totalPoints = $row['total_points'];
            }
            $stmt->close();
        } else {
            error_log("Failed to prepare statement for fetching total points: " . $conn->error);
        }

        // Fetch rank
        $rankQuery = "
            SELECT rank FROM (
                SELECT user_id, SUM(score) AS total_points, 
                RANK() OVER (ORDER BY SUM(score) DESC) AS rank
                FROM scores
                GROUP BY user_id
            ) AS ranking
            WHERE user_id = ?";
        if ($stmt = $conn->prepare($rankQuery)) {
            $stmt->bind_param("i", $userId);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($row = $result->fetch_assoc()) {
                $rank = $row['rank'];
            }
            $stmt->close();
        } else {
            error_log("Failed to prepare statement for fetching rank: " . $conn->error);
        }
        
        return ['totalPoints' => $totalPoints, 'rank' => $rank !== null ? $rank : 'N/A'];
    }

    
    // Function to convert duration to human-readable format
    function formatDuration($duration) {
        $parsed = date_parse($duration);
        
        $hours = $parsed['hour'];
        $minutes = $parsed['minute'];
        $seconds = $parsed['second'];

        $result = [];
        if ($hours > 0) {
            $result[] = $hours . ' ' . ($hours == 1 ? 'hour' : 'hours');
        }
        if ($minutes > 0) {
            $result[] = $minutes . ' ' . ($minutes == 1 ? 'minute' : 'minutes');
        }
        if ($seconds > 0 && $hours == 0 && $minutes == 0) { // Display seconds only if there are no hours or minutes
            $result[] = $seconds . ' ' . ($seconds == 1 ? 'second' : 'seconds');
        }
        
        return implode(', ', $result);
    }
?>
