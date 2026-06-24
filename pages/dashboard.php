<div id="page-dashboard" class="page active">
    <div class="hero-modern">
        <span class="hero-badge">🌟 Petualangan Dimulai</span>
        <h1 class="hero-title">Halo, <span class="gradient-text"><?php echo htmlspecialchars($player['display_name']); ?></span>!<br>Yuk Belajar Halal & Haram</h1>
        <p class="hero-desc">Pahami syariat dengan cara yang seru. Kumpulkan EXP, raih lencana penghargaan, dan jadilah Penjelajah Bijak!</p>
        <button class="cta-glow" onclick="switchPage('page-materi')">
            Mulai Belajar Sekarang <i class="fas fa-arrow-right"></i>
        </button>
    </div>

    <div class="daily-mission">
        <div class="mission-header">
            <h3>🎯 Misi Harian Anda</h3>
            <span class="reward-badge">+100 EXP Bonus</span>
        </div>
        <div class="mission-grid">
            <div class="mission-card">
                <span class="mission-icon">📖</span>
                <div class="mission-text">Baca 1 Materi Baru Hari Ini</div>
                <span class="mission-check text-success"><i class="fas fa-circle-notch"></i></span>
            </div>
            <div class="mission-card">
                <span class="mission-icon">🎮</span>
                <div class="mission-text">Mainkan Game Mencocokkan</div>
                <span class="mission-check text-success"><i class="fas fa-circle-notch"></i></span>
            </div>
        </div>
    </div>

    <div class="progress-card">
        <h4>📊 Status Progres Petualangan</h4>
        <div class="global-progress">
            <?php 
                $totalMateri = count($materiList) ?: 1;
                $doneMateri = count($completedMateri);
                $pct = round(($doneMateri / $totalMateri) * 100);
            ?>
            <div class="progress-circle-large" style="background: conic-gradient(var(--green-light) <?php echo $pct; ?>%, var(--green-pale) <?php echo $pct; ?>%);">
                <?php echo $pct; ?>%
            </div>
            <div class="progress-details">
                <div class="progress-stat">📖 Materi Terbaca: <strong><?php echo $doneMateri; ?> / <?php echo $totalMateri; ?></strong></div>
                <div class="progress-stat">🏆 Skor Kuis Terbaik: <strong><?php echo $bestQuizScore; ?>/100</strong></div>
                <div class="progress-stat">🏅 Lencana Dikoleksi: <strong><?php echo count($playerBadges); ?> Earned</strong></div>
            </div>
        </div>
    </div>
</div>