<div id="page-explore" class="page">
    <div class="section-header">
        <h2>🔍 Zona Eksplorasi Interaktif</h2>
        <p>Ketuk objek di bawah ini untuk mengidentifikasi status hukumnya dalam syariat Islam.</p>
    </div>
    
    <div class="explore-game-container">
        <div class="explore-board">
            <?php 
            // Memastikan data $exploreProgress valid berupa array dan tidak kosong
            if (is_array($exploreProgress) && !empty($exploreProgress)): 
                foreach($exploreProgress as $item): 
                    // Mengamankan data dengan operator ?? agar tidak memicu error jika ada kolom kosong
                    $namaItem  = $item['nama_item'] ?? 'Tanpa Nama';
                    $deskripsi = $item['deskripsi'] ?? 'Tidak ada deskripsi.';
                    $hukum     = $item['hukum'] ?? 'Belum Diketahui';
                    $emoji     = $item['emoji'] ?? '❓';
                    $isTerbuka = isset($item['status_buka']) && $item['status_buka'];
            ?>
                <!-- Kartu Item Eksplorasi -->
                <div class="explore-card <?php echo $isTerbuka ? 'completed' : ''; ?>" 
                     onclick="showExploreDetail('<?php echo addslashes($namaItem); ?>', '<?php echo addslashes($deskripsi); ?>', '<?php echo addslashes($hukum); ?>', '<?php echo addslashes($emoji); ?>')">
                    
                    <span class="card-emoji"><?php echo $emoji; ?></span>
                    <span><?php echo htmlspecialchars($namaItem); ?></span>
                    <span class="card-label"><?php echo htmlspecialchars($hukum); ?></span>
                    
                    <?php if($isTerbuka): ?>
                        <i class="fas fa-check-circle text-warning card-check" style="color: var(--gold)"></i>
                    <?php endif; ?>
                </div>
            <?php 
                endforeach; 
            else: 
            ?>
                <!-- Tampilan Cadangan jika Data di Database Kosong atau Gagal Diambil -->
                <div style="grid-column: span 2; text-align: center; padding: 40px 20px; color: var(--text-soft);">
                    <i class="fas fa-folder-open" style="font-size: 40px; margin-bottom: 12px; display: block; opacity: 0.5;"></i>
                    <p>Ups, data eksplorasi tidak ditemukan atau gagal dimuat dari database.</p>
                </div>
            <?php endif; ?>
        </div>
        
        <!-- Panel Informasi Samping (Detail Deskripsi Hukum) -->
        <div class="explore-info-panel" id="explorePanelDefault">
            <div class="big-emoji">🧭</div>
            <h3>Pilih Item</h3>
            <p>Sentuh salah satu kartu makanan atau benda di sebelah kiri untuk melihat detail penjelasan hukumnya di sini.</p>
        </div>
    </div>
</div>