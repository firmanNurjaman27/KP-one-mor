<?php
session_start();
require_once '../config/database.php';
require_once '../includes/functions.php';

if (!isset($_SESSION['is_admin'])) {
    header('Location: index.php');
    exit;
}

$database = new Database();
$db = $database->getConnection();

// Handle Add
if (isset($_POST['add_materi'])) {
    $id = $_POST['id'];
    $title = $_POST['title'];
    $icon = $_POST['icon'];
    $content = $_POST['content'];
    $xp_reward = $_POST['xp_reward'];
    
    $query = "INSERT INTO materi (id, title, icon, content, xp_reward) VALUES (:id, :title, :icon, :content, :xp_reward)";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':id', $id);
    $stmt->bindParam(':title', $title);
    $stmt->bindParam(':icon', $icon);
    $stmt->bindParam(':content', $content);
    $stmt->bindParam(':xp_reward', $xp_reward);
    $stmt->execute();
    
    header('Location: index.php');
    exit;
}

// Handle Edit
if (isset($_GET['edit'])) {
    $id = $_GET['edit'];
    $query = "SELECT * FROM materi WHERE id = :id";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    $materi = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $title = $_POST['title'];
        $icon = $_POST['icon'];
        $content = $_POST['content'];
        $xp_reward = $_POST['xp_reward'];
        
        $query = "UPDATE materi SET title = :title, icon = :icon, content = :content, xp_reward = :xp_reward WHERE id = :id";
        $stmt = $db->prepare($query);
        $stmt->bindParam(':title', $title);
        $stmt->bindParam(':icon', $icon);
        $stmt->bindParam(':content', $content);
        $stmt->bindParam(':xp_reward', $xp_reward);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        
        header('Location: index.php');
        exit;
    }
    ?>
    <!DOCTYPE html>
    <html>
    <head><title>Edit Materi</title><link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet"><style>body{font-family:'Poppins',sans-serif;background:#1E1E2F;color:white;padding:20px;}.form-container{max-width:800px;margin:0 auto;background:rgba(255,255,255,0.1);padding:30px;border-radius:20px;}input,textarea{width:100%;padding:10px;margin:10px 0;border-radius:10px;border:none;}button{background:#6C63FF;border:none;padding:10px 20px;border-radius:10px;color:white;cursor:pointer;}</style></head>
    <body>
        <div class="form-container">
            <h2>Edit Materi: <?php echo $materi['id']; ?></h2>
            <form method="post">
                <input type="text" name="title" value="<?php echo htmlspecialchars($materi['title']); ?>" required>
                <input type="text" name="icon" value="<?php echo $materi['icon']; ?>">
                <textarea name="content" rows="10" required><?php echo htmlspecialchars($materi['content']); ?></textarea>
                <input type="number" name="xp_reward" value="<?php echo $materi['xp_reward']; ?>">
                <button type="submit">Simpan Perubahan</button>
                <a href="index.php" style="color:white;margin-left:15px;">Batal</a>
            </form>
        </div>
    </body>
    </html>
    <?php
    exit;
}

// Handle Delete
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $query = "DELETE FROM materi WHERE id = :id";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    header('Location: index.php');
    exit;
}

header('Location: index.php');
?>