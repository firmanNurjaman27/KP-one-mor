<div id="page-badges" class="page">
    <div class="section-header">
        <h2>🏅 Ruang Penghargaan Lencana</h2>
        <p>Selesaikan berbagai tantangan interaktif untuk membuka seluruh lencana eksklusif.</p>
    </div>
    <div class="stat-summary">
        <div class="stat-summary-card">
            <i class="fas fa-bolt"></i>
            <span><?php echo $player['xp']; ?></span>
            <small>Total EXP</small>
        </div>
        <div class="stat-summary-card">
            <i class="fas fa-award"></i>
            <span><?php echo count($playerBadges); ?></span>
            <small>Lencana Diperoleh</small>
        </div>
    </div>
    <div class="badges-grid">
        <div class="badge-card earned">
            <div class="badge-icon">🏁</div>
            <div class="badge-name">Langkah Awal</div>
            <div class="badge-desc">Membuat akun petualang MCEAT.</div>
        </div>
        <div class="badge-card <?php echo (count($completedMateri) >= 3) ? 'earned' : ''; ?>">
            <div class="badge-icon">📚</div>
            <div class="badge-name">Kolektor Ilmu</div>
            <div class="badge-desc">Menyelesaikan minimal 3 materi pelajaran.</div>
        </div>
        <div class="badge-card <?php echo ($bestQuizScore >= 80) ? 'earned' : ''; ?>">
            <div class="badge-icon">🎓</div>
            <div class="badge-name">Mumtaz / Genius</div>
            <div class="badge-desc">Mendapat nilai di atas 80 pada sesi kuis.</div>
        </div>
    </div>
</div>