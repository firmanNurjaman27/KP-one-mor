<?php
session_start();
require_once '../config/database.php';

if (!isset($_SESSION['is_admin'])) {
    header('Location: index.php');
    exit;
}

$database = new Database();
$db = $database->getConnection();

// Handle bulk game
if (isset($_POST['add_bulk_game']) && isset($_POST['bulk_game'])) {
    $stmt = $db->prepare("INSERT INTO game_pairs (match_id, text_content, category, icon) VALUES (?, ?, ?, ?)");
    
    $inserted = 0;
    foreach ($_POST['bulk_game'] as $game) {
        if (!empty($game['match_id']) && !empty($game['text_content'])) {
            $stmt->execute([
                $game['match_id'],
                $game['text_content'],
                $game['category'],
                $game['icon'] ?? '✅'
            ]);
            $inserted++;
        }
    }
    
    $_SESSION['admin_message'] = "$inserted pair game berhasil ditambahkan!";
    header('Location: index.php#game');
    exit;
}

// Handle single game
if (isset($_POST['add_game'])) {
    $stmt = $db->prepare("INSERT INTO game_pairs (match_id, text_content, category, icon) VALUES (?, ?, ?, ?)");
    $stmt->execute([
        $_POST['match_id'],
        $_POST['text_content'],
        $_POST['category'],
        $_POST['icon'] ?? '✅'
    ]);
    $_SESSION['admin_message'] = "Game pair berhasil ditambahkan!";
    header('Location: index.php#game');
    exit;
}

// Handle edit game
if (isset($_POST['update_game'])) {
    $stmt = $db->prepare("UPDATE game_pairs SET match_id=?, text_content=?, category=?, icon=? WHERE id=?");
    $stmt->execute([
        $_POST['match_id'],
        $_POST['text_content'],
        $_POST['category'],
        $_POST['icon'],
        $_POST['id']
    ]);
    $_SESSION['admin_message'] = "Game pair berhasil diupdate!";
    header('Location: index.php#game');
    exit;
}

// Handle delete
if (isset($_GET['delete'])) {
    $stmt = $db->prepare("DELETE FROM game_pairs WHERE id=?");
    $stmt->execute([$_GET['delete']]);
    $_SESSION['admin_message'] = "Game pair berhasil dihapus!";
    header('Location: index.php#game');
    exit;
}

// Show edit form
if (isset($_GET['edit'])) {
    $stmt = $db->prepare("SELECT * FROM game_pairs WHERE id=?");
    $stmt->execute([$_GET['edit']]);
    $game = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($game):
    ?>
    <!DOCTYPE html>
    <html lang="id">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Edit Game Pair — MCEAT Learning</title>
        <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700;800;900&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
        <style>
            :root {
                --green-deep: #0a5c36;
                --green-mid: #1a7a4a;
                --green-light: #28a360;
                --green-pale: #e8f5ee;
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
            * { margin: 0; padding: 0; box-sizing: border-box; }
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
            .edit-card {
                background: var(--white);
                border: 1.5px solid var(--border);
                border-radius: var(--radius);
                width: 100%;
                max-width: 600px;
                box-shadow: 0 10px 40px rgba(10,92,54,0.15);
                overflow: hidden;
            }
            .edit-card-header {
                background: linear-gradient(135deg, var(--green-deep), var(--green-mid));
                padding: 18px 24px;
                color: var(--white);
                display: flex;
                align-items: center;
                gap: 10px;
            }
            .edit-card-header h3 { font-size: 16px; font-weight: 800; }
            .edit-card-body { padding: 24px; }
            .form-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 14px; }
            .form-group { display: flex; flex-direction: column; gap: 6px; }
            .form-group.span-2 { grid-column: span 2; }
            .form-group label {
                font-size: 12px;
                font-weight: 800;
                text-transform: uppercase;
                letter-spacing: 0.8px;
                color: var(--text-mid);
            }
            .form-group input,
            .form-group textarea,
            .form-group select {
                padding: 10px 14px;
                border: 2px solid var(--border);
                border-radius: 10px;
                font-family: 'Nunito', sans-serif;
                font-size: 14px;
                font-weight: 600;
                color: var(--text-dark);
                background: var(--off-white);
                transition: all 0.2s;
                outline: none;
            }
            .form-group input:focus,
            .form-group textarea:focus,
            .form-group select:focus {
                border-color: var(--green-light);
                background: var(--white);
                box-shadow: 0 0 0 4px rgba(40,163,96,0.1);
            }
            .form-group textarea { resize: vertical; min-height: 80px; }
            .btn-submit {
                display: inline-flex;
                align-items: center;
                gap: 8px;
                padding: 11px 26px;
                background: linear-gradient(135deg, var(--green-deep), var(--green-mid));
                border: none;
                border-radius: 10px;
                color: var(--white);
                font-family: 'Nunito', sans-serif;
                font-size: 14px;
                font-weight: 800;
                cursor: pointer;
                transition: all 0.2s;
                margin-top: 16px;
            }
            .btn-submit:hover { transform: translateY(-2px); box-shadow: 0 6px 20px rgba(10,92,54,0.3); }
            .btn-back {
                display: inline-flex;
                align-items: center;
                gap: 8px;
                padding: 11px 20px;
                background: var(--green-pale);
                border: 1.5px solid var(--border);
                border-radius: 10px;
                color: var(--green-deep);
                font-family: 'Nunito', sans-serif;
                font-size: 14px;
                font-weight: 700;
                cursor: pointer;
                text-decoration: none;
                transition: all 0.2s;
                margin-top: 16px;
                margin-left: 10px;
            }
            .btn-back:hover { background: var(--green-deep); color: var(--white); }
            @media (max-width: 600px) {
                .form-grid { grid-template-columns: 1fr; }
                .form-group.span-2 { grid-column: span 1; }
            }
        </style>
    </head>
    <body>
        <div class="edit-card">
            <div class="edit-card-header">
                <i class="fas fa-edit" style="color:var(--gold-light)"></i>
                <h3>Edit Game Pair #<?php echo $game['id']; ?></h3>
            </div>
            <div class="edit-card-body">
                <form method="post">
                    <input type="hidden" name="id" value="<?php echo $game['id']; ?>">
                    <div class="form-grid">
                        <div class="form-group">
                            <label>Match ID</label>
                            <input type="text" name="match_id" value="<?php echo htmlspecialchars($game['match_id']); ?>" required>
                        </div>
                        <div class="form-group">
                            <label>Text Content</label>
                            <input type="text" name="text_content" value="<?php echo htmlspecialchars($game['text_content']); ?>" required>
                        </div>
                        <div class="form-group">
                            <label>Kategori</label>
                            <select name="category">
                                <option value="kategori" <?php echo $game['category'] == 'kategori' ? 'selected' : ''; ?>>Kategori</option>
                                <option value="contoh" <?php echo $game['category'] == 'contoh' ? 'selected' : ''; ?>>Contoh</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Icon</label>
                            <input type="text" name="icon" value="<?php echo htmlspecialchars($game['icon']); ?>">
                        </div>
                    </div>
                    <button type="submit" name="update_game" class="btn-submit"><i class="fas fa-save"></i> Update Pair</button>
                    <a href="index.php#game" class="btn-back"><i class="fas fa-arrow-left"></i> Kembali</a>
                </form>
            </div>
        </div>
    </body>
    </html>
    <?php
    endif;
    exit;
}

header('Location: index.php#game');
exit;
?>