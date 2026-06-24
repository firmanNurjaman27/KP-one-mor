<?php
require_once 'config/database.php';
require_once 'includes/functions.php';

$player_id = getPlayerId();
$player = getPlayerData($player_id);
$completedMateri = getCompletedMateri($player_id);
$materiList = getAllMateri();
$gamePairs = getAllGamePairs();
$quizQuestions = getAllQuizQuestions();
$bestQuizScore = getBestQuizScore($player_id);
$playerBadges = getPlayerBadges($player_id);
$exploreProgress = getExploreProgress($player_id);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes">
    <title>MCEAT Learning- Petualangan Halal & Haram</title>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --green-deep: #0a5c36;
            --green-mid: #1a7a4a;
            --green-light: #28a360;
            --green-xlight: #4bbe80;
            --green-pale: #e8f5ee;
            --green-ultra: #f2faf5;
            --gold: #c9a84c;
            --gold-light: #f0d080;
            --white: #ffffff;
            --off-white: #f7fdf9;
            --text-dark: #0d2b1a;
            --text-mid: #2d5a3d;
            --text-soft: #5a8a6e;
            --border: #c8e6d0;
            --shadow: 0 4px 20px rgba(10,92,54,0.1);
            --radius: 16px;
        }
        
        * { margin: 0; padding: 0; box-sizing: border-box; }
        
        body {
            font-family: 'Nunito', sans-serif;
            background: var(--off-white);
            color: var(--text-dark);
            min-height: 100vh;
            position: relative;
            overflow-x: hidden;
        }

        .bg-animation { position: fixed; inset: 0; pointer-events: none; z-index: 0; }
        .orb { position: fixed; border-radius: 50%; filter: blur(60px); pointer-events: none; opacity: 0.3; }
        .orb-1 { width: 350px; height: 350px; background: rgba(40,163,96,0.3); top: -80px; right: -80px; }
        .orb-2 { width: 280px; height: 280px; background: rgba(201,168,76,0.25); bottom: -60px; left: -60px; }
        .orb-3 { width: 200px; height: 200px; background: rgba(40,163,96,0.2); top: 50%; left: 50%; transform: translate(-50%, -50%); }
        .grid-pattern {
            position: fixed; inset: 0;
            background-image:
                repeating-linear-gradient(0deg, rgba(10,92,54,0.02) 0, rgba(10,92,54,0.02) 1px, transparent 1px, transparent 40px),
                repeating-linear-gradient(90deg, rgba(10,92,54,0.02) 0, rgba(10,92,54,0.02) 1px, transparent 1px, transparent 40px);
        }

        .app-wrapper { position: relative; z-index: 1; min-height: 100vh; }
        .glass-header {
            background: linear-gradient(135deg, var(--green-deep) 0%, var(--green-mid) 100%);
            padding: 0 32px; height: 68px; display: flex; align-items: center; justify-content: space-between;
            position: sticky; top: 0; z-index: 100; box-shadow: 0 4px 24px rgba(10,92,54,0.35);
        }
        .header-left { display: flex; align-items: center; gap: 16px; }
        .logo-3d { display: flex; align-items: center; gap: 10px; color: var(--white); }
        .logo-emoji { width: 40px; height: 40px; background: linear-gradient(135deg, var(--gold), var(--gold-light)); border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 20px; }
        .logo-text { font-size: 18px; font-weight: 900; letter-spacing: 0.5px; }
        .logo-text .highlight { color: var(--gold-light); font-size: 12px; display: block; font-weight: 400; margin-top: -2px; }
        .mceat-badge { background: rgba(255,255,255,0.15); border: 1px solid rgba(255,255,255,0.25); color: var(--white); padding: 5px 12px; border-radius: 20px; font-size: 11px; font-weight: 700; }
        .admin-link { color: var(--white); text-decoration: none; font-size: 12px; font-weight: 700; background: rgba(255,255,255,0.12); border: 1px solid rgba(255,255,255,0.2); padding: 6px 14px; border-radius: 20px; transition: all 0.2s; }
        .admin-link:hover { background: rgba(255,255,255,0.25); }
        .header-right { display: flex; align-items: center; gap: 10px; }
        .player-card { display: flex; align-items: center; gap: 12px; background: rgba(255,255,255,0.12); border: 1px solid rgba(255,255,255,0.2); padding: 8px 16px; border-radius: 30px; color: var(--white); }
        .player-avatar { width: 40px; height: 40px; background: linear-gradient(135deg, var(--gold), var(--gold-light)); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 18px; flex-shrink: 0; }
        .player-info { display: flex; flex-direction: column; gap: 2px; }
        .player-name { font-size: 14px; font-weight: 800; }
        .level-container { display: flex; align-items: center; gap: 6px; }
        .level-tag { font-size: 10px; background: rgba(255,255,255,0.2); padding: 2px 8px; border-radius: 10px; font-weight: 700; }
        .xp-bar-bg { width: 60px; height: 4px; background: rgba(255,255,255,0.2); border-radius: 2px; overflow: hidden; }
        .xp-bar-fill { height: 100%; background: var(--gold-light); border-radius: 2px; transition: width 0.5s; }
        .xp-counter { font-size: 12px; font-weight: 700; display: flex; align-items: center; gap: 4px; }

        .nav-menu { background: var(--white); border-bottom: 1.5px solid var(--border); padding: 0 32px; display: flex; gap: 4px; position: sticky; top: 68px; z-index: 99; box-shadow: 0 2px 12px rgba(10,92,54,0.08); overflow-x: auto; -webkit-overflow-scrolling: touch; }
        .nav-item { display: flex; align-items: center; gap: 8px; padding: 12px 18px; border: none; background: transparent; color: var(--text-mid); font-family: 'Nunito', sans-serif; font-size: 13px; font-weight: 700; cursor: pointer; border-bottom: 3px solid transparent; transition: all 0.2s; position: relative; white-space: nowrap; }
        .nav-item:hover { color: var(--green-deep); background: var(--green-pale); }
        .nav-item.active { color: var(--green-deep); border-bottom-color: var(--green-deep); }
        .nav-item i { font-size: 14px; color: var(--text-soft); transition: color 0.2s; }
        .nav-item.active i { color: var(--green-mid); }

        .main-content { padding: 30px; max-width: 1200px; margin: 0 auto; }
        .page { display: none; }
        .page.active { display: block; }

        .section-header { display: flex; align-items: flex-start; flex-direction: column; gap: 4px; margin-bottom: 28px; padding-bottom: 18px; border-bottom: 2px solid var(--border); }
        .section-header h2 { font-size: 22px; font-weight: 900; color: var(--text-dark); }
        .section-header p { font-size: 13px; color: var(--text-soft); }

        /* Style Dash / Hero */
        .hero-modern { background: linear-gradient(135deg, var(--white) 0%, var(--green-ultra) 100%); border: 1.5px solid var(--border); border-radius: var(--radius); padding: 40px; margin-bottom: 28px; position: relative; overflow: hidden; }
        .hero-modern::before { content: ''; position: absolute; top: 0; left: 0; right: 0; height: 5px; background: linear-gradient(90deg, var(--green-deep), var(--green-light), var(--gold)); }
        .hero-badge { display: inline-block; background: var(--green-pale); color: var(--green-deep); padding: 6px 16px; border-radius: 20px; font-size: 12px; font-weight: 800; margin-bottom: 16px; }
        .hero-title { font-size: 32px; font-weight: 900; color: var(--text-dark); margin-bottom: 12px; line-height: 1.3; }
        .gradient-text { background: linear-gradient(135deg, var(--green-deep), var(--green-light)); -webkit-background-clip: text; -webkit-text-fill-color: transparent; }
        .hero-desc { font-size: 15px; color: var(--text-soft); margin-bottom: 24px; max-width: 500px; }
        .cta-glow { display: inline-flex; align-items: center; gap: 10px; padding: 14px 28px; background: linear-gradient(135deg, var(--green-deep), var(--green-mid)); border: none; border-radius: 12px; color: var(--white); font-family: 'Nunito', sans-serif; font-size: 15px; font-weight: 800; cursor: pointer; transition: all 0.2s; text-decoration: none; }
        .cta-glow:hover { transform: translateY(-2px); box-shadow: 0 8px 25px rgba(10,92,54,0.35); }

        /* Style Misi & Progres */
        .daily-mission { background: var(--white); border: 1.5px solid var(--border); border-radius: var(--radius); padding: 24px; margin-bottom: 28px; }
        .mission-header { display: flex; align-items: center; gap: 10px; margin-bottom: 20px; }
        .mission-header h3 { font-size: 16px; font-weight: 800; color: var(--text-dark); }
        .reward-badge { background: var(--green-pale); color: var(--green-deep); padding: 4px 12px; border-radius: 20px; font-size: 11px; font-weight: 800; }
        .mission-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 12px; }
        .mission-card { background: var(--green-ultra); border: 1.5px solid var(--border); border-radius: 12px; padding: 16px; display: flex; align-items: center; gap: 12px; transition: all 0.2s; }
        .mission-card:hover { border-color: var(--green-light); transform: translateY(-2px); }
        .mission-icon { font-size: 28px; flex-shrink: 0; }
        .mission-text { flex: 1; font-size: 13px; font-weight: 700; color: var(--text-dark); }
        .progress-card { background: var(--white); border: 1.5px solid var(--border); border-radius: var(--radius); padding: 24px; margin-bottom: 28px; }
        .progress-card h4 { font-size: 16px; font-weight: 800; color: var(--text-dark); margin-bottom: 16px; }
        .global-progress { display: flex; align-items: center; gap: 24px; }
        .progress-circle-large { width: 100px; height: 100px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 24px; font-weight: 900; color: var(--green-deep); flex-shrink: 0; }
        .progress-details { display: flex; flex-direction: column; gap: 8px; }
        .progress-stat { font-size: 13px; font-weight: 700; color: var(--text-mid); }

        /* Style Materi */
        .materi-grid { display: grid; gap: 16px; }
        .materi-card-modern { background: var(--white); border: 1.5px solid var(--border); border-radius: var(--radius); overflow: hidden; transition: all 0.2s; }
        .materi-header { display: flex; align-items: center; gap: 12px; padding: 16px 20px; cursor: pointer; }
        .materi-number { width: 36px; height: 36px; background: linear-gradient(135deg, var(--green-deep), var(--green-mid)); border-radius: 10px; display: flex; align-items: center; justify-content: center; color: var(--white); font-size: 14px; font-weight: 900; flex-shrink: 0; }
        .materi-icon { font-size: 28px; flex-shrink: 0; }
        .materi-header h3 { flex: 1; font-size: 15px; font-weight: 800; color: var(--text-dark); }
        .expand-btn { background: var(--green-pale); border: none; width: 32px; height: 32px; border-radius: 50%; cursor: pointer; color: var(--green-deep); font-size: 14px; display: flex; align-items: center; justify-content: center; }
        .materi-body { display: none; padding: 0 20px 20px; border-top: 1.5px solid var(--border); }
        .materi-card-modern.expanded .materi-body { display: block; }
        .materi-content { padding: 16px 0; font-size: 14px; line-height: 1.8; color: var(--text-mid); }
        .btn-complete { display: inline-flex; align-items: center; gap: 8px; padding: 10px 20px; background: linear-gradient(135deg, var(--green-deep), var(--green-mid)); border: none; border-radius: 10px; color: var(--white); font-family: 'Nunito', sans-serif; font-size: 13px; font-weight: 700; cursor: pointer; transition: all 0.2s; }
        .btn-complete:hover { transform: translateY(-2px); box-shadow: 0 6px 20px rgba(10,92,54,0.3); }

        /* Style Eksplorasi */
        .explore-game-container { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }
        .explore-board { background: var(--white); border: 1.5px solid var(--border); border-radius: var(--radius); padding: 24px; display: grid; grid-template-columns: 1fr 1fr; gap: 12px; }
        .explore-card { padding: 24px 16px; background: var(--green-ultra); border: 2px solid var(--border); border-radius: 12px; text-align: center; cursor: pointer; transition: all 0.3s; display: flex; flex-direction: column; align-items: center; gap: 8px; font-weight: 700; font-size: 14px; color: var(--text-dark); position: relative; overflow: hidden; }
        .explore-card:hover { border-color: var(--green-light); transform: translateY(-3px); box-shadow: 0 8px 20px rgba(10,92,54,0.15); background: var(--white); }
        .explore-card.completed { border-color: var(--gold); background: #fffdf5; }
        .explore-card .card-emoji { font-size: 40px; }
        .explore-card .card-label { font-size: 12px; text-transform: uppercase; letter-spacing: 0.5px; color: var(--text-soft); }
        .explore-card .card-check { position: absolute; top: 8px; right: 8px; font-size: 18px; }
        .explore-info-panel { background: var(--white); border: 1.5px solid var(--border); border-radius: var(--radius); padding: 24px; text-align: center; display: flex; flex-direction: column; justify-content: center; }
        .explore-info-panel .big-emoji { font-size: 64px; margin-bottom: 12px; }
        .explore-info-panel h3 { font-size: 20px; font-weight: 900; margin-bottom: 8px; }
        .explore-info-panel p { font-size: 14px; color: var(--text-soft); line-height: 1.6; }
        .explore-info-panel .xp-badge { display: inline-block; margin-top: 12px; padding: 8px 20px; background: var(--green-pale); color: var(--green-deep); border-radius: 20px; font-weight: 800; font-size: 13px; }

        /* Style Game Pairs */
        .game-wrapper { background: var(--white); border: 1.5px solid var(--border); border-radius: var(--radius); padding: 24px; }
        .game-status { display: flex; gap: 16px; margin-bottom: 20px; flex-wrap: wrap; }
        .game-status > div { background: var(--green-pale); padding: 8px 16px; border-radius: 20px; font-size: 13px; font-weight: 700; color: var(--green-deep); }
        .game-board { display: grid; grid-template-columns: repeat(3, 1fr); gap: 12px; margin-bottom: 20px; }
        .game-card { padding: 24px 16px; background: var(--green-ultra); border: 2px solid var(--border); border-radius: 12px; text-align: center; cursor: pointer; font-weight: 700; font-size: 14px; transition: all 0.2s; min-height: 80px; display: flex; align-items: center; justify-content: center; }
        .game-card:hover { border-color: var(--green-light); background: var(--white); }
        .game-card.selected { border-color: var(--green-deep); background: var(--green-pale); box-shadow: 0 0 0 3px rgba(10,92,54,0.2); }
        .game-card.matched { border-color: var(--gold); background: #fff8e1; cursor: default; opacity: 0.8; }
        .game-feedback { text-align: center; margin-bottom: 16px; font-weight: 700; font-size: 14px; min-height: 24px; }
        .game-feedback.success { color: var(--green-deep); }
        .game-feedback.error { color: #c53030; }
        .game-reset { display: inline-flex; align-items: center; gap: 8px; padding: 10px 24px; background: var(--green-pale); border: 1.5px solid var(--border); border-radius: 10px; color: var(--green-deep); font-family: 'Nunito', sans-serif; font-size: 13px; font-weight: 700; cursor: pointer; transition: all 0.2s; }
        .game-reset:hover { background: var(--green-deep); color: var(--white); }

        /* Style Kuis */
        .quiz-wrapper { background: var(--white); border: 1.5px solid var(--border); border-radius: var(--radius); padding: 24px; }
        .quiz-header { margin-bottom: 20px; }
        .quiz-progress-text { font-size: 13px; font-weight: 700; color: var(--text-mid); margin-bottom: 8px; }
        .quiz-progress-bar { height: 6px; background: var(--green-pale); border-radius: 3px; overflow: hidden; margin-bottom: 8px; }
        .quiz-progress-fill { height: 100%; background: linear-gradient(90deg, var(--green-deep), var(--green-light)); border-radius: 3px; transition: width 0.3s; }
        .quiz-card { padding: 20px; background: var(--green-ultra); border-radius: 12px; margin-bottom: 16px; }
        .quiz-question { font-size: 16px; font-weight: 800; margin-bottom: 16px; color: var(--text-dark); }
        .quiz-options { display: grid; gap: 10px; }
        .quiz-option { padding: 14px 18px; background: var(--white); border: 2px solid var(--border); border-radius: 10px; cursor: pointer; font-weight: 600; font-size: 14px; transition: all 0.2s; text-align: left; }
        .quiz-option:hover { border-color: var(--green-light); background: var(--green-pale); }
        .quiz-option.correct { border-color: var(--green-deep) !important; background: #d4edda !important; pointer-events: none; }
        .quiz-option.wrong { border-color: #e53e3e !important; background: #fff0f0 !important; pointer-events: none; }
        .quiz-option.disabled { pointer-events: none; }
        .quiz-next { display: inline-flex; align-items: center; gap: 8px; padding: 10px 24px; background: linear-gradient(135deg, var(--green-deep), var(--green-mid)); border: none; border-radius: 10px; color: var(--white); font-family: 'Nunito', sans-serif; font-size: 13px; font-weight: 700; cursor: pointer; transition: all 0.2s; }
        .quiz-next:hover { transform: translateY(-2px); box-shadow: 0 6px 20px rgba(10,92,54,0.3); }
        .quiz-result { text-align: center; padding: 24px; background: var(--green-ultra); border-radius: 12px; margin-bottom: 16px; }
        .quiz-result h3 { font-size: 20px; font-weight: 900; margin-bottom: 8px; }
        .quiz-result .score-big { font-size: 48px; font-weight: 900; color: var(--green-deep); }

        /* Style Lencana */
        .stat-summary { display: flex; gap: 16px; margin-bottom: 24px; }
        .stat-summary-card { background: var(--white); border: 1.5px solid var(--border); border-radius: 12px; padding: 20px 24px; text-align: center; flex: 1; }
        .stat-summary-card i { font-size: 24px; color: var(--gold); margin-bottom: 8px; display: block; }
        .stat-summary-card span { display: block; font-size: 24px; font-weight: 900; color: var(--green-deep); }
        .stat-summary-card small { font-size: 11px; color: var(--text-soft); text-transform: uppercase; font-weight: 700; }
        .badges-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(140px, 1fr)); gap: 12px; }
        .badge-card { background: var(--white); border: 1.5px solid var(--border); border-radius: 12px; padding: 20px; text-align: center; opacity: 0.5; transition: all 0.2s; }
        .badge-card.earned { border-color: var(--gold); background: #fffdf5; opacity: 1; }
        .badge-card .badge-icon { font-size: 36px; margin-bottom: 8px; }
        .badge-card .badge-name { font-weight: 800; font-size: 13px; color: var(--text-dark); }
        .badge-card .badge-desc { font-size: 11px; color: var(--text-soft); margin-top: 4px; }

        /* Modal */
        .modal { display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.5); z-index: 200; justify-content: center; align-items: center; }
        .modal.show { display: flex; }
        .modal-content { background: var(--white); border-radius: var(--radius); padding: 32px; text-align: center; max-width: 400px; width: 90%; }
        .modal-icon { font-size: 48px; margin-bottom: 12px; }
        .modal-content h3 { font-size: 20px; font-weight: 900; margin-bottom: 8px; }
        .modal-content p { font-size: 14px; color: var(--text-soft); margin-bottom: 20px; }
        .modal-close { padding: 10px 24px; background: linear-gradient(135deg, var(--green-deep), var(--green-mid)); border: none; border-radius: 10px; color: var(--white); font-family: 'Nunito', sans-serif; font-size: 14px; font-weight: 800; cursor: pointer; transition: all 0.2s; }
        .modal-close:hover { transform: translateY(-2px); box-shadow: 0 6px 20px rgba(10,92,54,0.3); }

        @media (max-width: 768px) {
            .glass-header { padding: 0 12px; flex-wrap: wrap; height: auto; padding-top: 8px; padding-bottom: 8px; }
            .main-content { padding: 16px; }
            .nav-menu { padding: 0 8px; gap: 0; }
            .nav-item { padding: 10px 12px; font-size: 11px; }
            .nav-item span { display: none; }
            .hero-modern { padding: 24px; }
            .hero-title { font-size: 22px; }
            .explore-game-container { grid-template-columns: 1fr; }
            .explore-board { grid-template-columns: 1fr 1fr; }
            .game-board { grid-template-columns: repeat(2, 1fr); }
            .mission-grid { grid-template-columns: 1fr 1fr; }
            .player-info, .xp-counter { display: none; }
        }

                /* Mengatur pembungkus logo agar memiliki batas ukuran yang ideal */
        .logo-3d {
            display: flex;
            align-items: center;
            gap: 10px; /* Jarak antara logo dan teks */
            max-height: 50px; /* Batasi tinggi maksimal pembungkus di navbar */
        }

        /* Mengatur gambar logo agar responsif */
        .logo-3d img {
            height: 100%;       /* Mengikuti tinggi pembungkusnya */
            width: auto;        /* Lebar otomatis menyesuaikan proporsi gambar agar tidak gepeng */
            max-height: 40px;   /* Atur ukuran tinggi maksimal logo yang Anda inginkan */
            object-fit: contain;/* Memastikan gambar termuat sempurna tanpa terpotong */
        }
    </style>
</head>
<body>
    <div class="bg-animation">
        <div class="orb orb-1"></div>
        <div class="orb orb-2"></div>
        <div class="orb orb-3"></div>
        <div class="grid-pattern"></div>
    </div>

    <div class="app-wrapper">
        <header class="glass-header">
            <div class="header-left">
                <div class="logo-3d">
                   <img src="asset/img/logo.png" alt="Logo SDN Cibanjaran">
                    <span class="logo-text">SDN <span class="highlight">Cibanjaran</span></span>
                </div>
                <div class="mceat-badge">🎓 Halal & Haram</div>
                <?php if (isset($_SESSION['is_admin']) && $_SESSION['is_admin']): ?>
                    <a href="admin/" class="admin-link">⚙️ Admin Panel</a>
                <?php endif; ?>
            </div>
            <div class="header-right">
                <div class="player-card">
                    <div class="player-avatar"><i class="fas fa-user-astronaut"></i></div>
                    <div class="player-info">
                        <div class="player-name" id="displayName"><?php echo htmlspecialchars($player['display_name']); ?></div>
                        <div class="level-container">
                            <span class="level-tag">Lv <span id="levelValue"><?php echo $player['level']; ?></span></span>
                            <div class="xp-bar-bg"><div class="xp-bar-fill" id="xpBarFill" style="width: <?php echo ($player['xp'] % 100); ?>%"></div></div>
                        </div>
                    </div>
                    <div class="xp-counter">
                        <i class="fas fa-star" style="color: var(--gold-light);"></i>
                        <span id="xpValue"><?php echo $player['xp']; ?></span>&nbsp;XP
                    </div>
                </div>
            </div>
        </header>

        <!-- Menu Navigasi Tabs -->
        <nav class="nav-menu">
            <button class="nav-item active" data-target="page-dashboard"><i class="fas fa-th-large"></i> <span>Beranda</span></button>
            <button class="nav-item" data-target="page-materi"><i class="fas fa-book-open"></i> <span>Materi</span></button>
            <button class="nav-item" data-target="page-explore"><i class="fas fa-compass"></i> <span>Eksplorasi</span></button>
            <button class="nav-item" data-target="page-game"><i class="fas fa-gamepad"></i> <span>Game Pasangan</span></button>
            <button class="nav-item" data-target="page-quiz"><i class="fas fa-graduation-cap"></i> <span>Kuis Evaluasi</span></button>
            <button class="nav-item" data-target="page-badges"><i class="fas fa-trophy"></i> <span>Pencapaian</span></button>
        </nav>

        <!-- Pemuatan Halaman secara Modular Dinamis -->
        <main class="main-content">
            <?php 
                include 'pages/dashboard.php'; 
                include 'pages/materi.php'; 
                include 'pages/explore.php'; 
                include 'pages/game.php'; 
                include 'pages/quiz.php'; 
                include 'pages/badges.php'; 
            ?>
        </main>
    </div>

    <!-- Modal Popup Global -->
    <div class="modal" id="globalRewardModal">
        <div class="modal-content">
            <div class="modal-icon" id="modalIcon">🎉</div>
            <h3 id="modalTitle">Luar Biasa!</h3>
            <p id="modalMessage">Tantangan berhasil dipecahkan!</p>
            <button class="modal-close" onclick="closeRewardModal()">Lanjutkan Petualangan</button>
        </div>
    </div>

    <!-- Script Utama (Engine Aplikasi) -->
    <script>
        const rawGamePairs = <?php echo json_encode($gamePairs); ?>;
        const rawQuizQuestions = <?php echo json_encode($quizQuestions); ?>;
        
        // Router Tab Navigator
        document.querySelectorAll('.nav-item').forEach(button => {
            button.addEventListener('click', () => {
                document.querySelectorAll('.nav-item').forEach(b => b.classList.remove('active'));
                document.querySelectorAll('.page').forEach(p => p.classList.remove('active'));
                
                button.classList.add('active');
                const targetPage = button.getAttribute('data-target');
                document.getElementById(targetPage).classList.add('active');
                
                if(targetPage === 'page-game') initMatchingGame();
            });
        });

        function switchPage(pageId) {
            const targetBtn = document.querySelector(`[data-target="${pageId}"]`);
            if(targetBtn) targetBtn.click();
        }

        // JS Logic untuk Materi
        function toggleMateri(id) {
            const card = document.getElementById(`materi-${id}`);
            card.classList.toggle('expanded');
            const icon = card.querySelector('.expand-btn i');
            icon.className = card.classList.contains('expanded') ? 'fas fa-chevron-up' : 'fas fa-chevron-down';
        }

        function completeMateriAction(materiId) {
            fetch('actions/update_progress.php', {
                method: 'POST',
                headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                body: `action=complete_materi&materi_id=${materiId}`
            }).then(() => {
                showModal('📖 Materi Diselesaikan!', 'Selamat! +50 EXP diperoleh.', '📚');
                setTimeout(() => location.reload(), 2000);
            }).catch(() => {
                showModal('Progres Berhasil!', 'Materi ditandai selesai (Local Mode).', '✔️');
            });
        }

        // JS Logic untuk Eksplorasi
        function showExploreDetail(name, desc, hukum, emoji) {
            const panel = document.getElementById('explorePanelDefault');
            let hukumColor = hukum.toLowerCase() === 'halal' ? '#0a5c36' : '#c53030';
            panel.innerHTML = `
                <div class="big-emoji">${emoji}</div>
                <h3>${name}</h3>
                <span style="display:inline-block; padding: 4px 14px; background:${hukumColor}; color:#fff; border-radius:20px; font-size:12px; font-weight:800; margin-bottom:12px;">${hukum.toUpperCase()}</span>
                <p>${desc}</p>
                <div class="xp-badge">✓ Teridentifikasi</div>
            `;
        }

        // JS Logic untuk Matching Game
        let selectedCards = [];
        let gameScore = 0;
        function initMatchingGame() {
            const grid = document.getElementById('gameBoardGrid');
            grid.innerHTML = ''; selectedCards = []; gameScore = 0;
            document.getElementById('gameScoreDisplay').innerText = gameScore;
            
            if(!rawGamePairs || rawGamePairs.length === 0) {
                grid.innerHTML = '<p style="grid-column: span 3; text-align:center; color:var(--text-soft)">Belum ada data pasangan game.</p>';
                return;
            }

            let cardsData = [];
            rawGamePairs.slice(0, 6).forEach((pair, idx) => {
                cardsData.push({ id: idx, type: 'item', text: pair.item_name });
                cardsData.push({ id: idx, type: 'match', text: pair.cocokan_text });
            });
            cardsData.sort(() => Math.random() - 0.5);

            cardsData.forEach((data, index) => {
                const cardEl = document.createElement('div');
                cardEl.className = 'game-card';
                cardEl.innerText = data.text;
                cardEl.dataset.matchId = data.id;
                cardEl.onclick = () => handleCardClick(cardEl);
                grid.appendChild(cardEl);
            });
        }

        function handleCardClick(card) {
            if(card.classList.contains('matched') || card.classList.contains('selected')) return;
            card.classList.add('selected');
            selectedCards.push(card);
            
            if(selectedCards.length === 2) {
                const feedback = document.getElementById('gameFeedback');
                if(selectedCards[0].dataset.matchId === selectedCards[1].dataset.matchId) {
                    selectedCards.forEach(c => { c.classList.remove('selected'); c.classList.add('matched'); });
                    gameScore += 20;
                    document.getElementById('gameScoreDisplay').innerText = gameScore;
                    feedback.className = "game-feedback success";
                    feedback.innerText = "✓ Pasangan Tepat! (+20 Poin)";
                    if(document.querySelectorAll('.game-card.matched').length === document.querySelectorAll('.game-card').length) {
                        showModal('🎉 Sempurna!', 'Seluruh pasangan hukum telah terjawab!', '🏆');
                    }
                } else {
                    feedback.className = "game-feedback error";
                    feedback.innerText = "❌ Kurang Tepat, Coba Lagi!";
                    const temp = [...selectedCards];
                    setTimeout(() => temp.forEach(c => c.classList.remove('selected')), 800);
                }
                selectedCards = [];
            }
        }

        // JS Logic untuk Kuis
        let currentQuestionIdx = 0; let quizScore = 0;
        function startQuizEngine() {
            if(!rawQuizQuestions || rawQuizQuestions.length === 0) return alert('Pertanyaan kuis kosong.');
            document.getElementById('quizIntro').style.display = 'none';
            document.getElementById('quizPlayArea').style.display = 'block';
            currentQuestionIdx = 0; quizScore = 0;
            renderQuizQuestion();
        }

        function renderQuizQuestion() {
            document.getElementById('btnNextQuiz').style.display = 'none';
            const total = rawQuizQuestions.length;
            document.getElementById('totalQCount').innerText = total;
            document.getElementById('currentQIndex').innerText = currentQuestionIdx + 1;
            document.getElementById('quizBarFill').style.width = `${((currentQuestionIdx) / total) * 100}%`;

            const currentQ = rawQuizQuestions[currentQuestionIdx];
            document.getElementById('quizQuestionText').innerText = currentQ.pertanyaan;
            const container = document.getElementById('quizOptionsContainer');
            container.innerHTML = '';

            [{k:'A', t:currentQ.opsi_a}, {k:'B', t:currentQ.opsi_b}, {k:'C', t:currentQ.opsi_c}, {k:'D', t:currentQ.opsi_d}].forEach(opt => {
                const btn = document.createElement('button');
                btn.className = 'quiz-option';
                btn.innerText = `${opt.k}. ${opt.t}`;
                btn.onclick = () => checkQuizAnswer(btn, opt.k, currentQ.jawaban_benar);
                container.appendChild(btn);
            });
        }

        function checkQuizAnswer(selectedBtn, chosenKey, correctKey) {
            const allOptions = document.querySelectorAll('.quiz-option');
            allOptions.forEach(opt => opt.classList.add('disabled'));

            if(chosenKey === correctKey) {
                selectedBtn.classList.add('correct');
                quizScore += Math.round(100 / rawQuizQuestions.length);
            } else {
                selectedBtn.classList.add('wrong');
                allOptions.forEach(opt => { if(opt.innerText.startsWith(correctKey)) opt.classList.add('correct'); });
            }
            document.getElementById('btnNextQuiz').style.display = 'inline-flex';
        }

        function nextQuestion() {
            currentQuestionIdx++;
            if(currentQuestionIdx < rawQuizQuestions.length) {
                renderQuizQuestion();
            } else {
                document.getElementById('quizPlayArea').style.display = 'none';
                document.getElementById('quizResultArea').style.display = 'block';
                document.getElementById('finalScoreDisplay').innerText = quizScore > 100 ? 100 : quizScore;
                navigator.sendBeacon('actions/save_score.php', `score=${quizScore}`);
            }
        }

        // Modal Utility
        function showModal(title, msg, emoji = '🎉') {
            document.getElementById('modalIcon').innerText = emoji;
            document.getElementById('modalTitle').innerText = title;
            document.getElementById('modalMessage').innerText = msg;
            document.getElementById('globalRewardModal').classList.add('show');
        }
        function closeRewardModal() { document.getElementById('globalRewardModal').classList.remove('show'); }
    </script>
</body>
</html>