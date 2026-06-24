-- Create Database
CREATE DATABASE IF NOT EXISTS mceat_learning;
USE mceat_learning;

-- Users/Players table
CREATE TABLE IF NOT EXISTS players (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    display_name VARCHAR(100) DEFAULT 'Pejuang Halal',
    level INT DEFAULT 1,
    xp INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Materi table (editable)
CREATE TABLE IF NOT EXISTS materi (
    id VARCHAR(1) PRIMARY KEY,
    title VARCHAR(200) NOT NULL,
    icon VARCHAR(50) DEFAULT '📖',
    content TEXT NOT NULL,
    xp_reward INT DEFAULT 25,
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Game Pairs table (editable)
CREATE TABLE IF NOT EXISTS game_pairs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    match_id VARCHAR(10) NOT NULL,
    text_content VARCHAR(200) NOT NULL,
    category ENUM('kategori', 'contoh') NOT NULL,
    icon VARCHAR(50) DEFAULT '✅',
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Quiz Questions table (editable)
CREATE TABLE IF NOT EXISTS quiz_questions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    question TEXT NOT NULL,
    option_a VARCHAR(500) NOT NULL,
    option_b VARCHAR(500) NOT NULL,
    option_c VARCHAR(500) NOT NULL,
    option_d VARCHAR(500) NOT NULL,
    correct_answer TINYINT(1) NOT NULL COMMENT '0:A,1:B,2:C,3:D',
    xp_reward INT DEFAULT 10,
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Player Progress table
CREATE TABLE IF NOT EXISTS player_progress (
    id INT AUTO_INCREMENT PRIMARY KEY,
    player_id INT NOT NULL,
    materi_id VARCHAR(1),
    is_completed BOOLEAN DEFAULT FALSE,
    completed_at TIMESTAMP NULL,
    FOREIGN KEY (player_id) REFERENCES players(id) ON DELETE CASCADE
);

-- Player Game Scores
CREATE TABLE IF NOT EXISTS player_game_scores (
    id INT AUTO_INCREMENT PRIMARY KEY,
    player_id INT NOT NULL,
    score INT DEFAULT 0,
    attempts INT DEFAULT 0,
    combo INT DEFAULT 0,
    played_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (player_id) REFERENCES players(id) ON DELETE CASCADE
);

-- Player Quiz Scores
CREATE TABLE IF NOT EXISTS player_quiz_scores (
    id INT AUTO_INCREMENT PRIMARY KEY,
    player_id INT NOT NULL,
    score INT DEFAULT 0,
    total_questions INT DEFAULT 15,
    taken_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (player_id) REFERENCES players(id) ON DELETE CASCADE
);

-- Player Badges
CREATE TABLE IF NOT EXISTS player_badges (
    id INT AUTO_INCREMENT PRIMARY KEY,
    player_id INT NOT NULL,
    badge_id VARCHAR(50) NOT NULL,
    earned_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (player_id) REFERENCES players(id) ON DELETE CASCADE,
    UNIQUE KEY unique_badge (player_id, badge_id)
);

-- Daily Missions
CREATE TABLE IF NOT EXISTS daily_missions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    player_id INT NOT NULL,
    mission_type ENUM('read', 'explore', 'game', 'quiz') NOT NULL,
    is_completed BOOLEAN DEFAULT FALSE,
    completed_at TIMESTAMP NULL,
    mission_date DATE DEFAULT (CURRENT_DATE),
    FOREIGN KEY (player_id) REFERENCES players(id) ON DELETE CASCADE,
    UNIQUE KEY unique_daily_mission (player_id, mission_type, mission_date)
);

-- Explore Progress
CREATE TABLE IF NOT EXISTS explore_progress (
    id INT AUTO_INCREMENT PRIMARY KEY,
    player_id INT NOT NULL,
    explore_id VARCHAR(50) NOT NULL,
    is_explored BOOLEAN DEFAULT FALSE,
    explored_at TIMESTAMP NULL,
    FOREIGN KEY (player_id) REFERENCES players(id) ON DELETE CASCADE,
    UNIQUE KEY unique_explore (player_id, explore_id)
);

-- Insert default data for Materi
INSERT INTO materi (id, title, icon, content, xp_reward) VALUES
('A', 'Pengertian Halal & Haram', '📖', '<p><strong>Halal</strong> berasal dari bahasa Arab yang artinya <strong>"diperbolehkan"</strong> atau <strong>"dibolehkan"</strong>. Segala sesuatu yang halal boleh dilakukan atau dikonsumsi oleh umat Islam.</p><p><strong>Haram</strong> berarti <strong>"dilarang"</strong> atau <strong>"terlarang"</strong>. Segala sesuatu yang haram mendapat dosa jika dilakukan dan pahala jika ditinggalkan.</p><div class="rukun-grid"><div class="rukun-item halal">✅ HALAL: Diperbolehkan, mendapat pahala jika diniatkan ibadah</div><div class="rukun-item haram">❌ HARAM: Dilarang, mendapat dosa jika dilakukan</div></div><div class="info-box"><i class="fas fa-lightbulb"></i><span>Tahukah kamu? Dalam Islam, segala sesuatu pada dasarnya adalah HALAL, kecuali ada dalil yang mengharamkannya!</span></div>', 25),
('B', 'Makanan & Minuman Halal Haram', '🍽️', '<p><strong>Allah SWT berfirman dalam QS. Al-Baqarah: 168</strong> - "Wahai manusia! Makanlah dari (makanan) yang halal dan baik yang terdapat di bumi..."</p><div class="materi-list-title">✅ MAKANAN HALAL:</div><ul class="materi-list"><li><i class="fas fa-check green"></i> Semua hewan yang disembelih dengan menyebut nama Allah</li><li><i class="fas fa-check green"></i> Ikan dan hewan laut</li><li><i class="fas fa-check green"></i> Buah-buahan dan sayuran</li><li><i class="fas fa-check green"></i> Susu, telur dari hewan halal</li></ul><div class="materi-list-title">❌ MAKANAN HARAM:</div><ul class="materi-list"><li><i class="fas fa-times red"></i> Babi dan turunannya</li><li><i class="fas fa-times red"></i> Bangkai (hewan yang mati tidak disembelih)</li><li><i class="fas fa-times red"></i> Darah</li><li><i class="fas fa-times red"></i> Hewan yang disembelih bukan atas nama Allah</li><li><i class="fas fa-times red"></i> Khamr (minuman memabukkan)</li></ul>', 25),
('C', 'Perbuatan Halal & Haram', '💼', '<p>Tidak hanya makanan, <strong>perbuatan</strong> juga ada yang halal dan haram dalam Islam.</p><div class="materi-list-title">✅ PERBUATAN HALAL (Mendapat Pahala):</div><ul class="materi-list"><li><i class="fas fa-check green"></i> Shalat, puasa, zakat, haji</li><li><i class="fas fa-check green"></i> Berbuat baik kepada orang tua</li><li><i class="fas fa-check green"></i> Jujur dalam perkataan dan perbuatan</li><li><i class="fas fa-check green"></i> Bekerja dengan cara yang baik</li><li><i class="fas fa-check green"></i> Menolong sesama</li></ul><div class="materi-list-title">❌ PERBUATAN HARAM (Mendapat Dosa):</div><ul class="materi-list"><li><i class="fas fa-times red"></i> Berbohong dan menipu</li><li><i class="fas fa-times red"></i> Mencuri dan korupsi</li><li><i class="fas fa-times red"></i> Durhaka kepada orang tua</li><li><i class="fas fa-times red"></i> Memakan riba (bunga bank)</li><li><i class="fas fa-times red"></i> Berjudi dan mengonsumsi minuman keras</li><li><i class="fas fa-times red"></i> Berzina dan perbuatan keji lainnya</li></ul>', 25),
('D', 'Hikmah Halal & Bahaya Haram', '💖', '<p>Allah memerintahkan yang halal dan melarang yang haram karena <strong>kebaikan untuk manusia</strong>.</p><div class="love-steps"><div class="love-step"><span>✨</span> <strong>Hikmah Makanan Halal:</strong> Menjaga kesehatan fisik dan spiritual</div><div class="love-step"><span>✨</span> <strong>Hikmah Perbuatan Halal:</strong> Mendapat ketenangan hati dan keberkahan</div><div class="love-step"><span>⚠️</span> <strong>Bahaya Makanan Haram:</strong> Doa tidak dikabulkan, merusak kesehatan</div><div class="love-step"><span>⚠️</span> <strong>Bahaya Perbuatan Haram:</strong> Mendapat siksa dunia dan akhirat</div><div class="love-step"><span>🤲</span> <strong>Manfaat Menjauhi Haram:</strong> Dilindungi Allah, hidup berkah, masuk surga</div></div>', 30);

-- Insert Game Pairs
INSERT INTO game_pairs (match_id, text_content, category, icon) VALUES
('A', 'Makanan Halal', 'kategori', '✅'),
('B', 'Makanan Haram', 'kategori', '❌'),
('C', 'Perbuatan Halal', 'kategori', '🤲'),
('D', 'Perbuatan Haram', 'kategori', '⚠️'),
('E', 'Minuman Halal', 'kategori', '🥤'),
('F', 'Minuman Haram', 'kategori', '🚫'),
('A', 'Ayam Sembelih + Daging Sapi', 'contoh', '🍗'),
('B', 'Babi + Bangkai + Darah', 'contoh', '🐷'),
('C', 'Shalat + Puasa + Sedekah', 'contoh', '🕌'),
('D', 'Mencuri + Berbohong + Riba', 'contoh', '👮'),
('E', 'Air putih + Susu + Jus', 'contoh', '💧'),
('F', 'Khamr + Minuman Keras', 'contoh', '🍺');

-- Insert Quiz Questions
INSERT INTO quiz_questions (question, option_a, option_b, option_c, option_d, correct_answer) VALUES
('Apa arti kata "Halal" dalam bahasa Arab?', 'Dilarang', 'Diperbolehkan', 'Wajib', 'Sunah', 1),
('Apa arti kata "Haram" dalam bahasa Arab?', 'Diperbolehkan', 'Disunahkan', 'Dilarang', 'Dimakruhkan', 2),
('Hewan berikut ini yang HARAM dikonsumsi dalam Islam adalah...', 'Ayam', 'Sapi', 'Babi', 'Kambing', 2),
('Minuman yang HARAM dikonsumsi karena memabukkan disebut...', 'Air putih', 'Susu', 'Jus', 'Khamr', 3),
('Perbuatan berikut yang HALAL adalah...', 'Mencuri', 'Berbohong', 'Shalat', 'Durhaka kepada orang tua', 2),
('Perbuatan berikut yang HARAM adalah...', 'Belajar', 'Bekerja', 'Mencuri', 'Membaca Al-Qur''an', 2),
('Makanan yang berasal dari laut pada umumnya hukumnya...', 'Haram', 'Makruh', 'Halal', 'Syubhat', 2),
('Hewan yang disembelih tidak dengan menyebut nama Allah hukumnya...', 'Halal', 'Haram', 'Sunah', 'Mubah', 1),
('Riba dalam jual beli hukumnya...', 'Halal', 'Sunah', 'Haram', 'Mubah', 2),
('Bangkai hewan hukumnya...', 'Halal', 'Haram', 'Makruh', 'Sunah', 1),
('Darah hewan yang keluar saat disembelih hukumnya...', 'Halal', 'Sunah', 'Makruh', 'Haram', 3),
('Berikut ini yang termasuk makanan HALAL adalah...', 'Babi', 'Darah', 'Ayam yang disembelih secara syar''i', 'Bangkai', 2),
('Perbuatan menipu dalam berdagang hukumnya...', 'Haram', 'Halal', 'Sunah', 'Mubah', 0),
('Mengonsumsi makanan haram dapat menyebabkan...', 'Doa tidak dikabulkan', 'Mendapat pahala', 'Sehat selalu', 'Kaya raya', 0),
('Sertifikasi halal pada produk berguna untuk...', 'Menghalalkan yang haram', 'Menjamin kehalalan produk', 'Memperindah kemasan', 'Menaikkan harga', 1);