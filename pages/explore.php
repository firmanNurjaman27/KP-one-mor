<div id="page-explore" class="page">
    <div class="section-header">
        <h2>🔍 Zona Eksplorasi Interaktif</h2>
        <p>Ketuk objek di bawah ini untuk mengidentifikasi status hukumnya dalam syariat Islam.</p>
    </div>
    <div class="explore-game-container">
        <div class="explore-board">
            <?php foreach($exploreProgress as $item): ?>
                <div class="explore-card <?php echo $item['status_buka'] ? 'completed' : ''; ?>" 
                     onclick="showExploreDetail('<?php echo addslashes($item['nama_item']); ?>', '<?php echo addslashes($item['deskripsi']); ?>', '<?php echo $item['hukum']; ?>', '<?php echo $item['emoji']; ?>')">
                    <span class="card-emoji"><?php echo $item['emoji']; ?></span>
                    <span><?php echo htmlspecialchars($item['nama_item']); ?></span>
                    <span class="card-label"><?php echo htmlspecialchars($item['hukum']); ?></span>
                    <?php if($item['status_buka']): ?>
                        <i class="fas fa-check-circle text-warning card-check" style="color:var(--gold)"></i>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>
        <div class="explore-info-panel" id="explorePanelDefault">
            <div class="big-emoji">🧭</div>
            <h3>Pilih Item</h3>
            <p>Sentuh salah satu kartu makanan atau benda di sebelah kiri untuk melihat detail penjelasan hukumnya di sini.</p>
        </div>
    </div>
</div>