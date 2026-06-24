<div id="page-materi" class="page">
    <div class="section-header">
        <h2>📚 Modul Pembelajaran</h2>
        <p>Klik pada judul materi untuk membuka isi pelajaran dan tekan tombol selesai untuk mengklaim EXP.</p>
    </div>
    <div class="materi-grid">
        <?php foreach($materiList as $index => $materi): 
            $isCompleted = in_array($materi['id'], $completedMateri);
        ?>
            <div class="materi-card-modern" id="materi-<?php echo $materi['id']; ?>">
                <div class="materi-header" onclick="toggleMateri(<?php echo $materi['id']; ?>)">
                    <div class="materi-number"><?php echo $index + 1; ?></div>
                    <span class="materi-icon"><?php echo $materi['icon'] ?? '📖'; ?></span>
                    <h3><?php echo htmlspecialchars($materi['judul']); ?></h3>
                    <button class="expand-btn"><i class="fas fa-chevron-down"></i></button>
                </div>
                <div class="materi-body">
                    <div class="materi-content">
                        <?php echo nl2br(htmlspecialchars($materi['isi'])); ?>
                    </div>
                    <?php if(!$isCompleted): ?>
                        <button class="btn-complete" onclick="completeMateriAction(<?php echo $materi['id']; ?>)">
                            <i class="fas fa-check-circle"></i> Tandai Sudah Selesai (+50 EXP)
                        </button>
                    <?php else: ?>
                        <span class="badge bg-success text-white p-2" style="border-radius:8px; background:var(--green-deep); color:white; display:inline-block;"><i class="fas fa-check"></i> Selesai Dibaca</span>
                    <?php endif; ?>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>