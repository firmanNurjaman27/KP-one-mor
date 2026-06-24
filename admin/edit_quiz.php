<?php
session_start();
require_once '../config/database.php';

// Cek apakah user sudah login sebagai admin
if (!isset($_SESSION['is_admin'])) {
    header('Location: index.php');
    exit;
}

$database = new Database();
$db = $database->getConnection();

// ========== HANDLE BULK QUIZ INSERT ==========
if (isset($_POST['add_bulk_quiz']) && isset($_POST['bulk_quiz'])) {
    $stmt = $db->prepare("INSERT INTO quiz_questions (question, option_a, option_b, option_c, option_d, correct_answer, xp_reward) VALUES (?, ?, ?, ?, ?, ?, ?)");
    
    $inserted = 0;
    $errors = 0;
    
    foreach ($_POST['bulk_quiz'] as $index => $quiz) {
        // Validasi data tidak kosong
        if (!empty($quiz['question']) && !empty($quiz['option_a']) && !empty($quiz['option_b']) && !empty($quiz['option_c']) && !empty($quiz['option_d'])) {
            try {
                $stmt->execute([
                    trim($quiz['question']),
                    trim($quiz['option_a']),
                    trim($quiz['option_b']),
                    trim($quiz['option_c']),
                    trim($quiz['option_d']),
                    intval($quiz['correct_answer']),
                    intval($quiz['xp_reward'] ?? 10)
                ]);
                $inserted++;
            } catch (PDOException $e) {
                $errors++;
            }
        }
    }
    
    if ($inserted > 0) {
        $_SESSION['admin_message'] = "Berhasil menambahkan $inserted soal quiz!";
        if ($errors > 0) {
            $_SESSION['admin_message'] .= " ($errors soal gagal ditambahkan)";
        }
    } else {
        $_SESSION['admin_error'] = "Gagal menambahkan soal. Pastikan semua field diisi dengan benar.";
    }
    
    header('Location: index.php#quiz');
    exit;
}

// ========== HANDLE SINGLE QUIZ INSERT ==========
if (isset($_POST['add_quiz'])) {
    $question = trim($_POST['question'] ?? '');
    $option_a = trim($_POST['option_a'] ?? '');
    $option_b = trim($_POST['option_b'] ?? '');
    $option_c = trim($_POST['option_c'] ?? '');
    $option_d = trim($_POST['option_d'] ?? '');
    $correct_answer = intval($_POST['correct_answer'] ?? 0);
    $xp_reward = intval($_POST['xp_reward'] ?? 10);
    
    // Validasi
    if (empty($question) || empty($option_a) || empty($option_b) || empty($option_c) || empty($option_d)) {
        $_SESSION['admin_error'] = "Semua field harus diisi!";
        header('Location: index.php#quiz');
        exit;
    }
    
    try {
        $stmt = $db->prepare("INSERT INTO quiz_questions (question, option_a, option_b, option_c, option_d, correct_answer, xp_reward) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$question, $option_a, $option_b, $option_c, $option_d, $correct_answer, $xp_reward]);
        
        $_SESSION['admin_message'] = "Soal quiz berhasil ditambahkan!";
    } catch (PDOException $e) {
        $_SESSION['admin_error'] = "Gagal menambahkan soal: " . $e->getMessage();
    }
    
    header('Location: index.php#quiz');
    exit;
}

// ========== HANDLE UPDATE QUIZ ==========
if (isset($_POST['update_quiz'])) {
    $id = intval($_POST['id'] ?? 0);
    $question = trim($_POST['question'] ?? '');
    $option_a = trim($_POST['option_a'] ?? '');
    $option_b = trim($_POST['option_b'] ?? '');
    $option_c = trim($_POST['option_c'] ?? '');
    $option_d = trim($_POST['option_d'] ?? '');
    $correct_answer = intval($_POST['correct_answer'] ?? 0);
    $xp_reward = intval($_POST['xp_reward'] ?? 10);
    
    if ($id <= 0) {
        $_SESSION['admin_error'] = "ID soal tidak valid!";
        header('Location: index.php#quiz');
        exit;
    }
    
    try {
        $stmt = $db->prepare("UPDATE quiz_questions SET question = ?, option_a = ?, option_b = ?, option_c = ?, option_d = ?, correct_answer = ?, xp_reward = ? WHERE id = ?");
        $stmt->execute([$question, $option_a, $option_b, $option_c, $option_d, $correct_answer, $xp_reward, $id]);
        
        $_SESSION['admin_message'] = "Soal quiz berhasil diupdate!";
    } catch (PDOException $e) {
        $_SESSION['admin_error'] = "Gagal mengupdate soal: " . $e->getMessage();
    }
    
    header('Location: index.php#quiz');
    exit;
}

// ========== HANDLE DELETE QUIZ ==========
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    
    if ($id <= 0) {
        $_SESSION['admin_error'] = "ID soal tidak valid!";
        header('Location: index.php#quiz');
        exit;
    }
    
    try {
        // Cek apakah soal ada
        $checkStmt = $db->prepare("SELECT id FROM quiz_questions WHERE id = ?");
        $checkStmt->execute([$id]);
        
        if ($checkStmt->rowCount() > 0) {
            $stmt = $db->prepare("DELETE FROM quiz_questions WHERE id = ?");
            $stmt->execute([$id]);
            $_SESSION['admin_message'] = "Soal quiz berhasil dihapus!";
        } else {
            $_SESSION['admin_error'] = "Soal tidak ditemukan!";
        }
    } catch (PDOException $e) {
        $_SESSION['admin_error'] = "Gagal menghapus soal: " . $e->getMessage();
    }
    
    header('Location: index.php#quiz');
    exit;
}

// ========== SHOW EDIT FORM ==========
if (isset($_GET['edit'])) {
    $id = intval($_GET['edit']);
    
    if ($id <= 0) {
        $_SESSION['admin_error'] = "ID soal tidak valid!";
        header('Location: index.php#quiz');
        exit;
    }
    
    $stmt = $db->prepare("SELECT * FROM quiz_questions WHERE id = ?");
    $stmt->execute([$id]);
    $quiz = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$quiz) {
        $_SESSION['admin_error'] = "Soal tidak ditemukan!";
        header('Location: index.php#quiz');
        exit;
    }
    ?>
    <!DOCTYPE html>
    <html lang="id">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Edit Quiz #<?php echo $quiz['id']; ?> — MCEAT Learning</title>
        <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700;800;900&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
        <style>
            :root {
                --green-deep: #0a5c36;
                --green-mid: #1a7a4a;
                --green-light: #28a360;
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
                --radius: 16px;
            }
            
            * {
                margin: 0;
                padding: 0;
                box-sizing: border-box;
            }
            
            body {
                font-family: 'Nunito', sans-serif;
                background: var(--off-white);
                color: var(--text-dark);
                min-height: 100vh;
                display: flex;
                justify-content: center;
                align-items: center;
                padding: 20px;
            }
            
            .edit-container {
                width: 100%;
                max-width: 800px;
            }
            
            .edit-card {
                background: var(--white);
                border: 1.5px solid var(--border);
                border-radius: var(--radius);
                box-shadow: 0 10px 40px rgba(10,92,54,0.15);
                overflow: hidden;
            }
            
            .edit-card-header {
                background: linear-gradient(135deg, var(--green-deep), var(--green-mid));
                padding: 20px 28px;
                color: var(--white);
                display: flex;
                align-items: center;
                gap: 12px;
            }
            
            .edit-card-header .icon-wrap {
                width: 40px;
                height: 40px;
                background: rgba(255,255,255,0.2);
                border-radius: 10px;
                display: flex;
                align-items: center;
                justify-content: center;
                font-size: 18px;
            }
            
            .edit-card-header .header-text h3 {
                font-size: 18px;
                font-weight: 900;
                margin-bottom: 2px;
            }
            
            .edit-card-header .header-text p {
                font-size: 12px;
                opacity: 0.8;
                font-weight: 400;
            }
            
            .edit-card-body {
                padding: 28px;
            }
            
            .form-grid {
                display: grid;
                grid-template-columns: 1fr 1fr;
                gap: 16px;
            }
            
            .form-group {
                display: flex;
                flex-direction: column;
                gap: 8px;
            }
            
            .form-group.span-2 {
                grid-column: span 2;
            }
            
            .form-group label {
                font-size: 12px;
                font-weight: 800;
                text-transform: uppercase;
                letter-spacing: 1px;
                color: var(--text-mid);
            }
            
            .form-group input,
            .form-group textarea,
            .form-group select {
                padding: 12px 16px;
                border: 2px solid var(--border);
                border-radius: 12px;
                font-family: 'Nunito', sans-serif;
                font-size: 14px;
                font-weight: 600;
                color: var(--text-dark);
                background: var(--off-white);
                transition: all 0.25s;
                outline: none;
            }
            
            .form-group input:focus,
            .form-group textarea:focus,
            .form-group select:focus {
                border-color: var(--green-light);
                background: var(--white);
                box-shadow: 0 0 0 4px rgba(40,163,96,0.1);
            }
            
            .form-group textarea {
                resize: vertical;
                min-height: 100px;
            }
            
            .form-group select {
                cursor: pointer;
            }
            
            .button-group {
                display: flex;
                gap: 12px;
                margin-top: 24px;
            }
            
            .btn-submit {
                display: inline-flex;
                align-items: center;
                gap: 8px;
                padding: 12px 28px;
                background: linear-gradient(135deg, var(--green-deep), var(--green-mid));
                border: none;
                border-radius: 12px;
                color: var(--white);
                font-family: 'Nunito', sans-serif;
                font-size: 14px;
                font-weight: 800;
                cursor: pointer;
                transition: all 0.2s;
                text-decoration: none;
            }
            
            .btn-submit:hover {
                transform: translateY(-2px);
                box-shadow: 0 8px 25px rgba(10,92,54,0.3);
            }
            
            .btn-back {
                display: inline-flex;
                align-items: center;
                gap: 8px;
                padding: 12px 24px;
                background: var(--green-pale);
                border: 1.5px solid var(--border);
                border-radius: 12px;
                color: var(--green-deep);
                font-family: 'Nunito', sans-serif;
                font-size: 14px;
                font-weight: 700;
                cursor: pointer;
                text-decoration: none;
                transition: all 0.2s;
            }
            
            .btn-back:hover {
                background: var(--green-deep);
                color: var(--white);
            }
            
            .alert {
                padding: 14px 18px;
                border-radius: 12px;
                margin-bottom: 20px;
                font-size: 13.5px;
                font-weight: 700;
                display: flex;
                align-items: center;
                gap: 10px;
            }
            
            .alert-info {
                background: #d4edda;
                color: var(--green-deep);
                border: 1.5px solid #a8d5b5;
            }
            
            @media (max-width: 600px) {
                .form-grid {
                    grid-template-columns: 1fr;
                }
                .form-group.span-2 {
                    grid-column: span 1;
                }
                .button-group {
                    flex-direction: column;
                }
                .btn-submit, .btn-back {
                    width: 100%;
                    justify-content: center;
                }
            }
        </style>
    </head>
    <body>
        <div class="edit-container">
            <div class="edit-card">
                <div class="edit-card-header">
                    <div class="icon-wrap">
                        <i class="fas fa-edit"></i>
                    </div>
                    <div class="header-text">
                        <h3>Edit Soal Quiz #<?php echo $quiz['id']; ?></h3>
                        <p>Update pertanyaan dan jawaban</p>
                    </div>
                </div>
                <div class="edit-card-body">
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i>
                        Anda sedang mengedit soal dengan ID: <strong><?php echo $quiz['id']; ?></strong>
                    </div>
                    
                    <form method="post" action="edit_quiz.php">
                        <input type="hidden" name="id" value="<?php echo $quiz['id']; ?>">
                        
                        <div class="form-grid">
                            <div class="form-group span-2">
                                <label>Pertanyaan</label>
                                <textarea name="question" rows="4" placeholder="Tulis pertanyaan di sini..." required><?php echo htmlspecialchars($quiz['question']); ?></textarea>
                            </div>
                            
                            <div class="form-group">
                                <label>Opsi A</label>
                                <input type="text" name="option_a" value="<?php echo htmlspecialchars($quiz['option_a']); ?>" placeholder="Jawaban A" required>
                            </div>
                            
                            <div class="form-group">
                                <label>Opsi B</label>
                                <input type="text" name="option_b" value="<?php echo htmlspecialchars($quiz['option_b']); ?>" placeholder="Jawaban B" required>
                            </div>
                            
                            <div class="form-group">
                                <label>Opsi C</label>
                                <input type="text" name="option_c" value="<?php echo htmlspecialchars($quiz['option_c']); ?>" placeholder="Jawaban C" required>
                            </div>
                            
                            <div class="form-group">
                                <label>Opsi D</label>
                                <input type="text" name="option_d" value="<?php echo htmlspecialchars($quiz['option_d']); ?>" placeholder="Jawaban D" required>
                            </div>
                            
                            <div class="form-group">
                                <label>Jawaban Benar</label>
                                <select name="correct_answer" required>
                                    <option value="0" <?php echo $quiz['correct_answer'] == 0 ? 'selected' : ''; ?>>A</option>
                                    <option value="1" <?php echo $quiz['correct_answer'] == 1 ? 'selected' : ''; ?>>B</option>
                                    <option value="2" <?php echo $quiz['correct_answer'] == 2 ? 'selected' : ''; ?>>C</option>
                                    <option value="3" <?php echo $quiz['correct_answer'] == 3 ? 'selected' : ''; ?>>D</option>
                                </select>
                            </div>
                            
                            <div class="form-group">
                                <label>XP Reward</label>
                                <input type="number" name="xp_reward" value="<?php echo $quiz['xp_reward']; ?>" min="1" max="100" placeholder="Poin XP" required>
                            </div>
                        </div>
                        
                        <div class="button-group">
                            <button type="submit" name="update_quiz" class="btn-submit">
                                <i class="fas fa-save"></i> Update Soal
                            </button>
                            <a href="index.php#quiz" class="btn-back">
                                <i class="fas fa-arrow-left"></i> Kembali ke Panel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </body>
    </html>
    <?php
    exit;
}

// Jika tidak ada aksi yang dikenali, redirect ke index
header('Location: index.php#quiz');
exit;
?>