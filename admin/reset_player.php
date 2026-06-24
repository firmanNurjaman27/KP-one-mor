<?php
// Cek apakah session sudah aktif sebelum memulai session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once '../config/database.php';
require_once '../includes/functions.php';

if (!isset($_SESSION['is_admin'])) { 
    header('Location: index.php'); 
    exit; 
}

$database = new Database();
$db = $database->getConnection();

if (isset($_GET['id'])) {
    $player_id = $_GET['id'];
    
    try {
        // Mulai transaction
        $db->beginTransaction();
        
        // Reset player progress
        $stmt = $db->prepare("DELETE FROM player_progress WHERE player_id = :player_id");
        $stmt->execute([':player_id' => $player_id]);
        
        $stmt = $db->prepare("DELETE FROM player_game_scores WHERE player_id = :player_id");
        $stmt->execute([':player_id' => $player_id]);
        
        $stmt = $db->prepare("DELETE FROM player_quiz_scores WHERE player_id = :player_id");
        $stmt->execute([':player_id' => $player_id]);
        
        $stmt = $db->prepare("DELETE FROM player_badges WHERE player_id = :player_id");
        $stmt->execute([':player_id' => $player_id]);
        
        $stmt = $db->prepare("DELETE FROM daily_missions WHERE player_id = :player_id");
        $stmt->execute([':player_id' => $player_id]);
        
        $stmt = $db->prepare("DELETE FROM explore_progress WHERE player_id = :player_id");
        $stmt->execute([':player_id' => $player_id]);
        
        // Reset player XP dan Level
        $stmt = $db->prepare("UPDATE players SET xp = 0, level = 1 WHERE id = :player_id");
        $stmt->execute([':player_id' => $player_id]);
        
        // Commit transaction
        $db->commit();
        
        $_SESSION['admin_message'] = "Progress pemain berhasil direset!";
    } catch (Exception $e) {
        $db->rollBack();
        $_SESSION['admin_error'] = "Gagal mereset progress: " . $e->getMessage();
    }
}

header('Location: index.php#playersSection');
exit;
?>