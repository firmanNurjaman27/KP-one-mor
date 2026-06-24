<?php
header('Content-Type: application/json');
require_once '../config/database.php';
require_once '../includes/functions.php';

$data = json_decode(file_get_contents('php://input'), true);
$action = $data['action'] ?? '';
$player_id = $data['player_id'] ?? getPlayerId();

$database = new Database();
$db = $database->getConnection();

$response = ['success' => false];

switch ($action) {
    case 'add_xp':
        addXP($player_id, $data['amount'], $data['source']);
        $response = ['success' => true];
        break;
        
    case 'complete_materi':
        $result = completeMateri($player_id, $data['materi_id'], $data['xp_reward']);
        $response = ['success' => $result];
        break;
        
    case 'add_badge':
        addBadge($player_id, $data['badge_id']);
        $response = ['success' => true];
        break;
        
    case 'update_game_score':
        updateGameScore($player_id, $data['score'], $data['attempts'], $data['combo']);
        $response = ['success' => true];
        break;
        
    case 'reset_game':
        // No need to delete, just track new games
        $response = ['success' => true];
        break;
        
    case 'update_quiz_score':
        updateQuizScore($player_id, $data['score']);
        $response = ['success' => true];
        break;
        
    case 'daily_mission':
        updateDailyMission($player_id, $data['mission_type']);
        $response = ['success' => true];
        break;
        
    case 'explore':
        updateExploreProgress($player_id, $data['explore_id']);
        $response = ['success' => true];
        break;
        
    default:
        $response = ['success' => false, 'error' => 'Unknown action'];
}

echo json_encode($response);
?>