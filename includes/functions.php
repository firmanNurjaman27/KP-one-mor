<?php
// includes/functions.php

// Cek apakah session sudah aktif sebelum memulai session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function getPlayerId() {
    if (!isset($_SESSION['player_id'])) {
        $database = new Database();
        $db = $database->getConnection();
        
        $query = "INSERT INTO players (username, display_name) VALUES (:username, :display_name)";
        $stmt = $db->prepare($query);
        $username = 'player_' . uniqid();
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':display_name', $username);
        $stmt->execute();
        
        $_SESSION['player_id'] = $db->lastInsertId();
    }
    return $_SESSION['player_id'];
}

function getPlayerData($player_id) {
    $database = new Database();
    $db = $database->getConnection();
    
    $query = "SELECT * FROM players WHERE id = :id";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':id', $player_id);
    $stmt->execute();
    
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function getCompletedMateri($player_id) {
    $database = new Database();
    $db = $database->getConnection();
    
    $query = "SELECT materi_id FROM player_progress WHERE player_id = :player_id AND is_completed = 1";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':player_id', $player_id);
    $stmt->execute();
    
    $completed = [];
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $completed[$row['materi_id']] = true;
    }
    return $completed;
}

function getAllMateri() {
    $database = new Database();
    $db = $database->getConnection();
    
    $query = "SELECT * FROM materi WHERE is_active = 1 ORDER BY id";
    $stmt = $db->prepare($query);
    $stmt->execute();
    
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getAllGamePairs() {
    $database = new Database();
    $db = $database->getConnection();
    
    $query = "SELECT * FROM game_pairs WHERE is_active = 1";
    $stmt = $db->prepare($query);
    $stmt->execute();
    
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getAllQuizQuestions() {
    $database = new Database();
    $db = $database->getConnection();
    
    $query = "SELECT * FROM quiz_questions WHERE is_active = 1 ORDER BY id";
    $stmt = $db->prepare($query);
    $stmt->execute();
    
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function addXP($player_id, $amount, $source) {
    $database = new Database();
    $db = $database->getConnection();
    
    // Update player XP
    $query = "UPDATE players SET xp = xp + :amount WHERE id = :id";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':amount', $amount);
    $stmt->bindParam(':id', $player_id);
    $stmt->execute();
    
    // Update level
    $query = "UPDATE players SET level = FLOOR(xp / 100) + 1 WHERE id = :id";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':id', $player_id);
    $stmt->execute();
    
    return true;
}

function completeMateri($player_id, $materi_id, $xp_reward) {
    $database = new Database();
    $db = $database->getConnection();
    
    // Check if already completed
    $check = "SELECT * FROM player_progress WHERE player_id = :player_id AND materi_id = :materi_id";
    $stmt = $db->prepare($check);
    $stmt->bindParam(':player_id', $player_id);
    $stmt->bindParam(':materi_id', $materi_id);
    $stmt->execute();
    
    if ($stmt->rowCount() == 0) {
        $query = "INSERT INTO player_progress (player_id, materi_id, is_completed, completed_at) 
                  VALUES (:player_id, :materi_id, 1, NOW())";
        $stmt = $db->prepare($query);
        $stmt->bindParam(':player_id', $player_id);
        $stmt->bindParam(':materi_id', $materi_id);
        $stmt->execute();
        
        addXP($player_id, $xp_reward, "Materi $materi_id");
        return true;
    }
    return false;
}

function updateGameScore($player_id, $score, $attempts, $combo) {
    $database = new Database();
    $db = $database->getConnection();
    
    $query = "INSERT INTO player_game_scores (player_id, score, attempts, combo) 
              VALUES (:player_id, :score, :attempts, :combo)";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':player_id', $player_id);
    $stmt->bindParam(':score', $score);
    $stmt->bindParam(':attempts', $attempts);
    $stmt->bindParam(':combo', $combo);
    $stmt->execute();
    
    return true;
}

function updateQuizScore($player_id, $score) {
    $database = new Database();
    $db = $database->getConnection();
    
    $query = "INSERT INTO player_quiz_scores (player_id, score, total_questions) 
              VALUES (:player_id, :score, 15)";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':player_id', $player_id);
    $stmt->bindParam(':score', $score);
    $stmt->execute();
    
    return true;
}

function getBestQuizScore($player_id) {
    $database = new Database();
    $db = $database->getConnection();
    
    $query = "SELECT MAX(score) as best_score FROM player_quiz_scores WHERE player_id = :player_id";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':player_id', $player_id);
    $stmt->execute();
    
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    return $result['best_score'] ?: 0;
}

function getPlayerBadges($player_id) {
    $database = new Database();
    $db = $database->getConnection();
    
    $query = "SELECT badge_id FROM player_badges WHERE player_id = :player_id";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':player_id', $player_id);
    $stmt->execute();
    
    $badges = [];
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $badges[] = $row['badge_id'];
    }
    return $badges;
}

function addBadge($player_id, $badge_id) {
    $database = new Database();
    $db = $database->getConnection();
    
    $query = "INSERT IGNORE INTO player_badges (player_id, badge_id) VALUES (:player_id, :badge_id)";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':player_id', $player_id);
    $stmt->bindParam(':badge_id', $badge_id);
    $stmt->execute();
}

function updateDailyMission($player_id, $mission_type) {
    $database = new Database();
    $db = $database->getConnection();
    
    $query = "INSERT INTO daily_missions (player_id, mission_type, is_completed, completed_at, mission_date) 
              VALUES (:player_id, :mission_type, 1, NOW(), CURDATE())
              ON DUPLICATE KEY UPDATE is_completed = 1, completed_at = NOW()";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':player_id', $player_id);
    $stmt->bindParam(':mission_type', $mission_type);
    $stmt->execute();
}

function getDailyMissionStatus($player_id, $mission_type) {
    $database = new Database();
    $db = $database->getConnection();
    
    $query = "SELECT is_completed FROM daily_missions 
              WHERE player_id = :player_id AND mission_type = :mission_type AND mission_date = CURDATE()";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':player_id', $player_id);
    $stmt->bindParam(':mission_type', $mission_type);
    $stmt->execute();
    
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    return $result ? $result['is_completed'] : false;
}

function updateExploreProgress($player_id, $explore_id) {
    $database = new Database();
    $db = $database->getConnection();
    
    $query = "INSERT INTO explore_progress (player_id, explore_id, is_explored, explored_at) 
              VALUES (:player_id, :explore_id, 1, NOW())
              ON DUPLICATE KEY UPDATE is_explored = 1, explored_at = NOW()";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':player_id', $player_id);
    $stmt->bindParam(':explore_id', $explore_id);
    $stmt->execute();
}

function getExploreProgress($player_id) {
    $database = new Database();
    $db = $database->getConnection();
    
    $query = "SELECT explore_id FROM explore_progress WHERE player_id = :player_id AND is_explored = 1";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':player_id', $player_id);
    $stmt->execute();
    
    $explored = [];
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $explored[$row['explore_id']] = true;
    }
    return $explored;
}
?>