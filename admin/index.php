<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once '../config/database.php';
require_once '../includes/functions.php';

if (isset($_GET['logout'])) {
    session_destroy();
    header('Location: index.php');
    exit;
}

if (!isset($_SESSION['is_admin'])) {
    if (isset($_POST['admin_login'])) {
        $username = $_POST['username'] ?? '';
        $password = $_POST['password'] ?? '';
        $valid_username = 'admin';
        $valid_password = 'admin123';
        if ($username === $valid_username && $password === $valid_password) {
            $_SESSION['is_admin'] = true;
            $_SESSION['admin_username'] = $username;
            header('Location: index.php');
            exit;
        } else {
            $error = "Username atau password salah!";
        }
    }
    ?>
    <!DOCTYPE html>
    <html lang="id">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Admin Login — MCEAT Learning</title>
        <link href="https://fonts.googleapis.com/css2?family=Lateef:wght@400;700&family=Nunito:wght@400;600;700;800&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
        <style>
            :root {
                --green-deep: #0a5c36;
                --green-mid: #1a7a4a;
                --green-light: #28a360;
                --green-pale: #d4edda;
                --gold: #c9a84c;
                --gold-light: #f0d080;
                --white: #ffffff;
                --off-white: #f8fdf9;
                --text-dark: #0d2b1a;
                --text-mid: #2d5a3d;
            }
            * { margin: 0; padding: 0; box-sizing: border-box; }
            body {
                font-family: 'Nunito', sans-serif;
                background: var(--green-deep);
                min-height: 100vh;
                display: flex;
                justify-content: center;
                align-items: center;
                position: relative;
                overflow: hidden;
            }
            /* Geometric Islamic pattern background */
            body::before {
                content: '';
                position: fixed;
                inset: 0;
                background-image:
                    repeating-linear-gradient(45deg, rgba(255,255,255,0.03) 0, rgba(255,255,255,0.03) 1px, transparent 1px, transparent 50%),
                    repeating-linear-gradient(-45deg, rgba(255,255,255,0.03) 0, rgba(255,255,255,0.03) 1px, transparent 1px, transparent 50%);
                background-size: 30px 30px;
                pointer-events: none;
            }
            .orb {
                position: fixed;
                border-radius: 50%;
                filter: blur(80px);
                pointer-events: none;
            }
            .orb-1 { width: 400px; height: 400px; background: rgba(40,163,96,0.25); top: -100px; right: -100px; }
            .orb-2 { width: 300px; height: 300px; background: rgba(201,168,76,0.15); bottom: -80px; left: -80px; }
            .login-wrap {
                position: relative;
                z-index: 10;
                width: 100%;
                max-width: 440px;
                padding: 20px;
            }
            /* Arabic crescent + star motif */
            .brand-top {
                text-align: center;
                margin-bottom: 28px;
            }
            .brand-top .emblem {
                display: inline-flex;
                align-items: center;
                justify-content: center;
                width: 80px;
                height: 80px;
                background: linear-gradient(135deg, var(--gold), var(--gold-light));
                border-radius: 50%;
                font-size: 36px;
                margin-bottom: 12px;
                box-shadow: 0 8px 30px rgba(201,168,76,0.4);
                animation: pulse-gold 3s ease-in-out infinite;
            }
            @keyframes pulse-gold {
                0%, 100% { box-shadow: 0 8px 30px rgba(201,168,76,0.4); }
                50% { box-shadow: 0 8px 50px rgba(201,168,76,0.7); }
            }
            .brand-top h1 {
                font-family: 'Nunito', sans-serif;
                font-size: 22px;
                font-weight: 800;
                color: var(--white);
                letter-spacing: 1px;
            }
            .brand-top .subtitle {
                font-size: 12px;
                color: rgba(255,255,255,0.55);
                margin-top: 4px;
                letter-spacing: 2px;
                text-transform: uppercase;
            }
            .login-card {
                background: var(--white);
                border-radius: 28px;
                padding: 40px 36px;
                box-shadow: 0 30px 80px rgba(0,0,0,0.3);
                position: relative;
                overflow: hidden;
            }
            .login-card::before {
                content: '';
                position: absolute;
                top: 0; left: 0; right: 0;
                height: 5px;
                background: linear-gradient(90deg, var(--green-deep), var(--green-light), var(--gold));
            }
            .login-card h2 {
                font-size: 20px;
                font-weight: 800;
                color: var(--text-dark);
                margin-bottom: 6px;
            }
            .login-card p {
                font-size: 13px;
                color: var(--text-mid);
                margin-bottom: 28px;
                opacity: 0.8;
            }
            .field-label {
                display: block;
                font-size: 12px;
                font-weight: 700;
                color: var(--text-mid);
                text-transform: uppercase;
                letter-spacing: 1px;
                margin-bottom: 8px;
            }
            .input-wrap {
                position: relative;
                margin-bottom: 20px;
            }
            .input-wrap i {
                position: absolute;
                left: 16px;
                top: 50%;
                transform: translateY(-50%);
                color: var(--green-mid);
                font-size: 14px;
            }
            .input-wrap input {
                width: 100%;
                padding: 13px 16px 13px 44px;
                border: 2px solid #e8f5ec;
                border-radius: 14px;
                font-family: 'Nunito', sans-serif;
                font-size: 14px;
                font-weight: 600;
                color: var(--text-dark);
                background: var(--off-white);
                transition: all 0.25s;
                outline: none;
            }
            .input-wrap input:focus {
                border-color: var(--green-light);
                background: #fff;
                box-shadow: 0 0 0 4px rgba(40,163,96,0.1);
            }
            .login-btn {
                width: 100%;
                padding: 14px;
                background: linear-gradient(135deg, var(--green-deep), var(--green-mid));
                border: none;
                border-radius: 14px;
                color: var(--white);
                font-family: 'Nunito', sans-serif;
                font-size: 15px;
                font-weight: 800;
                cursor: pointer;
                transition: all 0.3s;
                letter-spacing: 0.5px;
                margin-top: 6px;
            }
            .login-btn:hover {
                transform: translateY(-2px);
                box-shadow: 0 8px 25px rgba(10,92,54,0.4);
            }
            .login-btn:active { transform: translateY(0); }
            .error-box {
                background: #fff0f0;
                border: 1.5px solid #ffb3b3;
                border-left: 4px solid #e53e3e;
                border-radius: 10px;
                padding: 12px 16px;
                margin-bottom: 20px;
                font-size: 13px;
                color: #c53030;
                display: flex;
                align-items: center;
                gap: 8px;
            }
            .back-link {
                display: block;
                text-align: center;
                margin-top: 22px;
                color: rgba(255,255,255,0.6);
                font-size: 13px;
                text-decoration: none;
                transition: color 0.2s;
            }
            .back-link:hover { color: var(--white); }
            .divider {
                text-align: center;
                margin: 20px 0;
                position: relative;
                color: #9bbea9;
                font-size: 12px;
            }
            .divider::before, .divider::after {
                content: '';
                position: absolute;
                top: 50%;
                width: 40%;
                height: 1px;
                background: #e0ede5;
            }
            .divider::before { left: 0; }
            .divider::after { right: 0; }
        </style>
    </head>
    <body>
        <div class="orb orb-1"></div>
        <div class="orb orb-2"></div>
        <div class="login-wrap">
            <div class="brand-top">
                <div class="emblem"></div>
                <h1>Halaman Admin</h1>
                <p class="subtitle">Panel Administrator</p>
            </div>
            <div class="login-card">
                <h2>Selamat Datang, Admin 👋</h2>
                <p>Masuk untuk mengelola konten pembelajaran</p>
                <?php if (isset($error)): ?>
                <div class="error-box"><i class="fas fa-exclamation-circle"></i> <?php echo $error; ?></div>
                <?php endif; ?>
                <form method="post">
                    <label class="field-label">Username</label>
                    <div class="input-wrap">
                        <i class="fas fa-user"></i>
                        <input type="text" name="username" placeholder="Masukkan username" required autocomplete="username">
                    </div>
                    <label class="field-label">Password</label>
                    <div class="input-wrap">
                        <i class="fas fa-lock"></i>
                        <input type="password" name="password" placeholder="Masukkan password" required autocomplete="current-password">
                    </div>
                    <button type="submit" name="admin_login" class="login-btn">
                        <i class="fas fa-sign-in-alt"></i> Masuk ke Panel
                    </button>
                </form>
            </div>
            <a href="../index.php" class="back-link"><i class="fas fa-arrow-left"></i> Kembali ke Website</a>
        </div>
    </body>
    </html>
    <?php
    exit;
}

$database = new Database();
$db = $database->getConnection();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel — MCEAT Learning</title>
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
        }

        /* ===== TOPBAR ===== */
        .topbar {
            background: linear-gradient(135deg, var(--green-deep) 0%, var(--green-mid) 100%);
            padding: 0 32px;
            height: 68px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: 100;
            box-shadow: 0 4px 24px rgba(10,92,54,0.35);
        }
        .topbar-brand {
            display: flex;
            align-items: center;
            gap: 12px;
            color: var(--white);
        }
        .topbar-brand .brand-icon {
            width: 40px;
            height: 40px;
            background: linear-gradient(135deg, var(--gold), var(--gold-light));
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
        }
        .topbar-brand h1 {
            font-size: 18px;
            font-weight: 900;
            letter-spacing: 0.5px;
        }
        .topbar-brand span {
            font-size: 11px;
            opacity: 0.65;
            display: block;
            font-weight: 400;
            margin-top: -2px;
        }
        .topbar-right {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .admin-badge {
            background: rgba(255,255,255,0.15);
            border: 1px solid rgba(255,255,255,0.25);
            color: var(--white);
            padding: 7px 16px;
            border-radius: 30px;
            font-size: 13px;
            font-weight: 700;
        }
        .topbar-btn {
            background: rgba(255,255,255,0.12);
            border: 1px solid rgba(255,255,255,0.2);
            color: var(--white);
            padding: 7px 16px;
            border-radius: 30px;
            font-size: 13px;
            font-weight: 700;
            cursor: pointer;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 7px;
            transition: all 0.2s;
        }
        .topbar-btn:hover { background: rgba(255,255,255,0.25); }

        /* ===== LAYOUT ===== */
        .layout {
            display: flex;
            min-height: calc(100vh - 68px);
        }

        /* ===== SIDEBAR ===== */
        .sidebar {
            width: 220px;
            background: var(--white);
            border-right: 1.5px solid var(--border);
            padding: 24px 14px;
            flex-shrink: 0;
            position: sticky;
            top: 68px;
            height: calc(100vh - 68px);
            overflow-y: auto;
        }
        .sidebar-section-label {
            font-size: 10px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            color: var(--text-soft);
            padding: 0 10px;
            margin-bottom: 8px;
            margin-top: 20px;
        }
        .sidebar-section-label:first-child { margin-top: 0; }
        .nav-btn {
            display: flex;
            align-items: center;
            gap: 10px;
            width: 100%;
            padding: 10px 14px;
            border: none;
            border-radius: 12px;
            background: transparent;
            color: var(--text-mid);
            font-family: 'Nunito', sans-serif;
            font-size: 13.5px;
            font-weight: 700;
            cursor: pointer;
            text-align: left;
            transition: all 0.2s;
            margin-bottom: 2px;
        }
        .nav-btn i {
            width: 18px;
            text-align: center;
            font-size: 14px;
            color: var(--text-soft);
            transition: color 0.2s;
        }
        .nav-btn:hover {
            background: var(--green-pale);
            color: var(--green-deep);
        }
        .nav-btn:hover i { color: var(--green-mid); }
        .nav-btn.active {
            background: linear-gradient(135deg, var(--green-deep), var(--green-mid));
            color: var(--white);
            box-shadow: 0 4px 14px rgba(10,92,54,0.3);
        }
        .nav-btn.active i { color: var(--gold-light); }

        /* ===== MAIN CONTENT ===== */
        .main {
            flex: 1;
            padding: 30px;
            min-width: 0;
        }
        .admin-section { display: none; }
        .admin-section.active { display: block; }

        .page-header {
            display: flex;
            align-items: center;
            gap: 14px;
            margin-bottom: 28px;
            padding-bottom: 18px;
            border-bottom: 2px solid var(--border);
        }
        .page-header .icon-wrap {
            width: 46px;
            height: 46px;
            background: linear-gradient(135deg, var(--green-deep), var(--green-mid));
            border-radius: 13px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--white);
            font-size: 20px;
            flex-shrink: 0;
        }
        .page-header h2 {
            font-size: 22px;
            font-weight: 900;
            color: var(--text-dark);
        }
        .page-header p { font-size: 13px; color: var(--text-soft); margin-top: 2px; }

        /* ===== STATS GRID ===== */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(165px, 1fr));
            gap: 16px;
            margin-bottom: 30px;
        }
        .stat-card {
            background: var(--white);
            border: 1.5px solid var(--border);
            border-radius: var(--radius);
            padding: 22px 18px;
            text-align: center;
            transition: transform 0.2s, box-shadow 0.2s;
            position: relative;
            overflow: hidden;
        }
        .stat-card::before {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0;
            height: 4px;
            background: linear-gradient(90deg, var(--green-deep), var(--green-light));
        }
        .stat-card:hover { transform: translateY(-3px); box-shadow: 0 10px 30px rgba(10,92,54,0.12); }
        .stat-card .stat-icon {
            width: 52px;
            height: 52px;
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 22px;
            margin: 0 auto 12px;
            background: var(--green-pale);
            color: var(--green-deep);
        }
        .stat-card .number {
            font-size: 30px;
            font-weight: 900;
            color: var(--green-deep);
            line-height: 1;
            margin-bottom: 5px;
        }
        .stat-card .label { font-size: 12px; color: var(--text-soft); font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; }

        /* ===== CARD ===== */
        .card {
            background: var(--white);
            border: 1.5px solid var(--border);
            border-radius: var(--radius);
            overflow: hidden;
            margin-bottom: 24px;
        }
        .card-header {
            padding: 16px 22px;
            background: linear-gradient(135deg, var(--green-deep), var(--green-mid));
            color: var(--white);
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .card-header h3 { font-size: 15px; font-weight: 800; }
        .card-header i { color: var(--gold-light); }
        .card-body { padding: 22px; }

        /* ===== FORM ===== */
        .form-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 14px; }
        .form-grid.cols-1 { grid-template-columns: 1fr; }
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
        .form-group textarea { resize: vertical; min-height: 100px; }
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
            margin-top: 4px;
        }
        .btn-submit:hover { transform: translateY(-2px); box-shadow: 0 6px 20px rgba(10,92,54,0.3); }

        /* ===== TABLE ===== */
        .table-wrap { overflow-x: auto; }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        thead tr {
            background: var(--green-pale);
        }
        thead th {
            padding: 12px 16px;
            text-align: left;
            font-size: 11px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            color: var(--text-mid);
            white-space: nowrap;
        }
        tbody tr {
            border-bottom: 1.5px solid var(--border);
            transition: background 0.15s;
        }
        tbody tr:last-child { border-bottom: none; }
        tbody tr:hover { background: var(--green-ultra); }
        tbody td {
            padding: 12px 16px;
            font-size: 13.5px;
            color: var(--text-dark);
            font-weight: 600;
        }
        .td-id {
            font-weight: 900;
            color: var(--green-deep);
            font-size: 13px;
        }
        .td-rank {
            font-size: 18px;
            font-weight: 900;
            color: var(--gold);
        }

        /* ===== ACTION BUTTONS ===== */
        .btn-edit, .btn-delete, .btn-reset {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 5px 13px;
            border: none;
            border-radius: 7px;
            font-family: 'Nunito', sans-serif;
            font-size: 12px;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.15s;
        }
        .btn-edit { background: var(--green-pale); color: var(--green-deep); border: 1.5px solid var(--border); }
        .btn-edit:hover { background: var(--green-deep); color: var(--white); }
        .btn-delete { background: #fff0f0; color: #c53030; border: 1.5px solid #ffc9c9; }
        .btn-delete:hover { background: #e53e3e; color: var(--white); }
        .btn-reset { background: #fff8e1; color: #9a6700; border: 1.5px solid #ffe082; }
        .btn-reset:hover { background: var(--gold); color: var(--white); }

        /* ===== BADGE ===== */
        .badge {
            display: inline-block;
            padding: 3px 10px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 800;
        }
        .badge-green { background: var(--green-pale); color: var(--green-deep); }
        .badge-gold { background: #fff8e1; color: #9a6700; }

        /* ===== NOTIFICATION ===== */
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
        .alert-success { background: #d4edda; color: var(--green-deep); border: 1.5px solid #a8d5b5; }
        .alert-error { background: #fff0f0; color: #c53030; border: 1.5px solid #ffc9c9; }

        /* ===== LEADERBOARD ===== */
        .rank-1 td:first-child { color: #f6c90e; }
        .rank-2 td:first-child { color: #adb5bd; }
        .rank-3 td:first-child { color: #cd7f32; }

        /* ===== QUICK ACTIONS ===== */
        .quick-actions {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(140px, 1fr));
            gap: 14px;
            margin-bottom: 28px;
        }
        .quick-card {
            background: var(--white);
            border: 1.5px solid var(--border);
            border-radius: var(--radius);
            padding: 20px 16px;
            text-align: center;
            cursor: pointer;
            transition: all 0.2s;
        }
        .quick-card:hover {
            background: var(--green-deep);
            border-color: var(--green-deep);
            transform: translateY(-3px);
            box-shadow: 0 10px 28px rgba(10,92,54,0.25);
        }
        .quick-card:hover .qc-icon, .quick-card:hover .qc-label { color: var(--white); }
        .qc-icon { font-size: 26px; color: var(--green-mid); margin-bottom: 8px; transition: color 0.2s; }
        .qc-label { font-size: 12px; font-weight: 800; color: var(--text-mid); transition: color 0.2s; }

        /* ===== BULK INPUT STYLES ===== */
        .bulk-input-container {
            display: flex;
            flex-direction: column;
            gap: 16px;
        }
        .bulk-item {
            background: var(--green-ultra);
            border: 1.5px solid var(--border);
            border-radius: 12px;
            padding: 16px;
            position: relative;
        }
        .bulk-item-header {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 12px;
        }
        .bulk-item-number {
            background: var(--green-deep);
            color: var(--white);
            width: 28px;
            height: 28px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 12px;
            font-weight: 800;
        }
        .bulk-item-title {
            font-size: 13px;
            font-weight: 800;
            color: var(--text-dark);
        }
        .bulk-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 10px;
        }
        .bulk-grid-2 { grid-template-columns: 1fr 1fr; }
        .bulk-grid-3 { grid-template-columns: 1fr 1fr 1fr; }
        .tab-container {
            display: flex;
            gap: 8px;
            margin-bottom: 20px;
            border-bottom: 2px solid var(--border);
            padding-bottom: 0;
        }
        .tab-btn {
            padding: 10px 20px;
            border: none;
            background: transparent;
            font-family: 'Nunito', sans-serif;
            font-size: 13px;
            font-weight: 700;
            color: var(--text-soft);
            cursor: pointer;
            border-bottom: 3px solid transparent;
            transition: all 0.2s;
            margin-bottom: -2px;
        }
        .tab-btn.active {
            color: var(--green-deep);
            border-bottom-color: var(--green-deep);
        }
        .tab-btn:hover {
            color: var(--green-mid);
        }
        .tab-content {
            display: none;
        }
        .tab-content.active {
            display: block;
        }
        .bulk-actions {
            display: flex;
            gap: 10px;
            align-items: center;
            margin-top: 16px;
        }
        .btn-add-bulk {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 8px 16px;
            background: var(--green-pale);
            border: 1.5px solid var(--border);
            border-radius: 8px;
            color: var(--green-deep);
            font-family: 'Nunito', sans-serif;
            font-size: 12px;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.2s;
        }
        .btn-add-bulk:hover {
            background: var(--green-deep);
            color: var(--white);
        }

        @media (max-width: 768px) {
            .sidebar { display: none; }
            .main { padding: 16px; }
            .form-grid { grid-template-columns: 1fr; }
            .form-group.span-2 { grid-column: span 1; }
            .topbar { padding: 0 16px; }
            .topbar-brand h1 { font-size: 15px; }
            .bulk-grid-2, .bulk-grid-3 { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>

<!-- TOP BAR -->
<div class="topbar">
    <div class="topbar-brand">
        <div class="brand-icon"></div>
        <div>
            <h1>Halaman admin</h1>
            <span>SDN CIBANJARAN </span>
        </div>
    </div>
    <div class="topbar-right">
        <div class="admin-badge"><i class="fas fa-user-check"></i> <?php echo htmlspecialchars($_SESSION['admin_username'] ?? 'Admin'); ?></div>
        <a href="?logout=1" class="topbar-btn" onclick="return confirm('Logout dari panel admin?')"><i class="fas fa-sign-out-alt"></i> Logout</a>
        <a href="../index.php" class="topbar-btn"><i class="fas fa-home"></i> Website</a>
    </div>
</div>

<div class="layout">

<!-- SIDEBAR -->
<aside class="sidebar">
    <div class="sidebar-section-label">Ikhtisar</div>
    <button class="nav-btn active" data-section="dashboard"><i class="fas fa-chart-pie"></i> Dashboard</button>

    <div class="sidebar-section-label">Konten</div>
    <button class="nav-btn" data-section="materi"><i class="fas fa-book-open"></i> Upload Materi</button>
    <button class="nav-btn" data-section="quiz"><i class="fas fa-question-circle"></i> Upload Soal</button>
    <button class="nav-btn" data-section="game"><i class="fas fa-puzzle-piece"></i> Edit Game</button>

    <div class="sidebar-section-label">Manajemen</div>
    <button class="nav-btn" data-section="players"><i class="fas fa-users"></i> Data Pemain</button>
</aside>

<!-- MAIN -->
<main class="main">

<?php
if (isset($_SESSION['admin_message'])) {
    echo '<div class="alert alert-success"><i class="fas fa-check-circle"></i> ' . $_SESSION['admin_message'] . '</div>';
    unset($_SESSION['admin_message']);
}
if (isset($_SESSION['admin_error'])) {
    echo '<div class="alert alert-error"><i class="fas fa-exclamation-circle"></i> ' . $_SESSION['admin_error'] . '</div>';
    unset($_SESSION['admin_error']);
}
?>

<!-- ========== DASHBOARD ========== -->
<div id="dashboardSection" class="admin-section active">
    <div class="page-header">
        <div class="icon-wrap"><i class="fas fa-chart-pie"></i></div>
        <div>
            <h2>Dashboard</h2>
            <p>Ringkasan keseluruhan platform MCEAT Learning</p>
        </div>
    </div>

    <?php
    $stmt = $db->query("SELECT COUNT(*) as total FROM players");
    $totalPlayers = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
    $stmt = $db->query("SELECT SUM(xp) as totalXP FROM players");
    $totalXP = $stmt->fetch(PDO::FETCH_ASSOC)['totalXP'] ?? 0;
    $stmt = $db->query("SELECT COUNT(*) as c FROM materi");
    $totalMateri = $stmt->fetch(PDO::FETCH_ASSOC)['c'];
    $stmt = $db->query("SELECT COUNT(*) as c FROM quiz_questions");
    $totalQuiz = $stmt->fetch(PDO::FETCH_ASSOC)['c'];
    $stmt = $db->query("SELECT COUNT(*) as c FROM game_pairs");
    $totalGame = $stmt->fetch(PDO::FETCH_ASSOC)['c'];
    $stmt = $db->query("SELECT AVG(level) as avg FROM players");
    $avgLevel = round($stmt->fetch(PDO::FETCH_ASSOC)['avg'] ?? 0, 1);
    ?>

    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon"><i class="fas fa-users"></i></div>
            <div class="number"><?php echo $totalPlayers; ?></div>
            <div class="label">Total Pemain</div>
        </div>
        <div class="stat-card">
            <div class="stat-icon"><i class="fas fa-star"></i></div>
            <div class="number"><?php echo number_format($totalXP); ?></div>
            <div class="label">Total XP</div>
        </div>
        <div class="stat-card">
            <div class="stat-icon"><i class="fas fa-book-open"></i></div>
            <div class="number"><?php echo $totalMateri; ?></div>
            <div class="label">Materi</div>
        </div>
        <div class="stat-card">
            <div class="stat-icon"><i class="fas fa-question-circle"></i></div>
            <div class="number"><?php echo $totalQuiz; ?></div>
            <div class="label">Soal Quiz</div>
        </div>
        <div class="stat-card">
            <div class="stat-icon"><i class="fas fa-puzzle-piece"></i></div>
            <div class="number"><?php echo $totalGame; ?></div>
            <div class="label">Game Pairs</div>
        </div>
        <div class="stat-card">
            <div class="stat-icon"><i class="fas fa-layer-group"></i></div>
            <div class="number"><?php echo $avgLevel; ?></div>
            <div class="label">Rata-rata Level</div>
        </div>
    </div>

    <div class="quick-actions">
        <div class="quick-card" onclick="switchTab('materi')">
            <div class="qc-icon"><i class="fas fa-book-open"></i></div>
            <div class="qc-label">Upload Materi</div>
        </div>
        <div class="quick-card" onclick="switchTab('quiz')">
            <div class="qc-icon"><i class="fas fa-question-circle"></i></div>
            <div class="qc-label">Upload Soal</div>
        </div>
        <div class="quick-card" onclick="switchTab('game')">
            <div class="qc-icon"><i class="fas fa-puzzle-piece"></i></div>
            <div class="qc-label">Edit Game</div>
        </div>
        <div class="quick-card" onclick="switchTab('players')">
            <div class="qc-icon"><i class="fas fa-users"></i></div>
            <div class="qc-label">Data Pemain</div>
        </div>
    </div>

    <div class="card">
        <div class="card-header"><i class="fas fa-trophy"></i> <h3>Top 5 Pemain Teraktif</h3></div>
        <div class="table-wrap">
            <table>
                <thead><tr><th>Rank</th><th>Username</th><th>Display Name</th><th>Level</th><th>XP</th><th>Bergabung</th></tr></thead>
                <tbody>
                    <?php
                    $stmt = $db->query("SELECT * FROM players ORDER BY xp DESC LIMIT 5");
                    $rank = 1;
                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)):
                    $rankClass = $rank <= 3 ? 'rank-'.$rank : '';
                    ?>
                    <tr class="<?php echo $rankClass; ?>">
                        <td class="td-rank"><?php echo $rank === 1 ? '🥇' : ($rank === 2 ? '🥈' : ($rank === 3 ? '🥉' : '#'.$rank)); ?></td>
                        <td><?php echo htmlspecialchars($row['username']); ?></td>
                        <td><?php echo htmlspecialchars($row['display_name']); ?></td>
                        <td><span class="badge badge-green">Level <?php echo $row['level']; ?></span></td>
                        <td><span class="badge badge-gold"><?php echo number_format($row['xp']); ?> XP</span></td>
                        <td><?php echo $row['created_at']; ?></td>
                    </tr>
                    <?php $rank++; endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- ========== MATERI ========== -->
<div id="materiSection" class="admin-section">
    <div class="page-header">
        <div class="icon-wrap"><i class="fas fa-book-open"></i></div>
        <div>
            <h2>Upload & Kelola Materi</h2>
            <p>Tambah, ubah, atau hapus konten materi pembelajaran</p>
        </div>
    </div>

    <div class="card">
        <div class="card-header"><i class="fas fa-plus-circle"></i> <h3>Tambah Materi Baru</h3></div>
        <div class="card-body">
            <form method="post" action="edit_materi.php">
                <div class="form-grid">
                    <div class="form-group">
                        <label>ID Materi</label>
                        <input type="text" name="id" placeholder="Contoh: A, B, C..." required>
                    </div>
                    <div class="form-group">
                        <label>Judul Materi</label>
                        <input type="text" name="title" placeholder="Masukkan judul materi" required>
                    </div>
                    <div class="form-group">
                        <label>Icon</label>
                        <input type="text" name="icon" value="📖" placeholder="Emoji icon">
                    </div>
                    <div class="form-group">
                        <label>XP Reward</label>
                        <input type="number" name="xp_reward" value="25" placeholder="Poin XP">
                    </div>
                    <div class="form-group span-2">
                        <label>Konten Materi (HTML diizinkan)</label>
                        <textarea name="content" rows="6" placeholder="Tulis konten materi di sini..." required></textarea>
                    </div>
                </div>
                <button type="submit" name="add_materi" class="btn-submit"><i class="fas fa-save"></i> Simpan Materi</button>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-header"><i class="fas fa-list"></i> <h3>Daftar Materi</h3></div>
        <div class="table-wrap">
            <table>
                <thead><tr><th>ID</th><th>Judul</th><th>Icon</th><th>XP</th><th>Aksi</th></tr></thead>
                <tbody>
                    <?php
                    $stmt = $db->prepare("SELECT * FROM materi ORDER BY id");
                    $stmt->execute();
                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)):
                    ?>
                    <tr>
                        <td class="td-id"><?php echo $row['id']; ?></td>
                        <td><?php echo htmlspecialchars($row['title']); ?></td>
                        <td style="font-size:20px"><?php echo $row['icon']; ?></td>
                        <td><span class="badge badge-gold"><?php echo $row['xp_reward']; ?> XP</span></td>
                        <td>
                            <button class="btn-edit" onclick="editMateri('<?php echo $row['id']; ?>')"><i class="fas fa-edit"></i> Edit</button>
                            <button class="btn-delete" onclick="deleteMateri('<?php echo $row['id']; ?>')"><i class="fas fa-trash"></i> Hapus</button>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- ========== QUIZ ========== -->
<div id="quizSection" class="admin-section">
    <div class="page-header">
        <div class="icon-wrap"><i class="fas fa-question-circle"></i></div>
        <div>
            <h2>Upload & Kelola Soal Quiz</h2>
            <p>Tambah, ubah, atau hapus pertanyaan kuis</p>
        </div>
    </div>

    <!-- Tab untuk Single dan Bulk Input -->
    <div class="tab-container">
        <button class="tab-btn active" onclick="switchQuizTab('single')">Input Satu Soal</button>
        <button class="tab-btn" onclick="switchQuizTab('bulk')">Input Banyak Soal (Maks 10)</button>
    </div>

    <!-- Single Quiz Form -->
    <div id="quizSingleTab" class="tab-content active">
        <div class="card">
            <div class="card-header"><i class="fas fa-plus-circle"></i> <h3>Tambah Satu Soal Quiz</h3></div>
            <div class="card-body">
                <form method="post" action="edit_quiz.php">
                    <div class="form-grid">
                        <div class="form-group span-2">
                            <label>Pertanyaan</label>
                            <textarea name="question" rows="3" placeholder="Tulis pertanyaan di sini..." required></textarea>
                        </div>
                        <div class="form-group">
                            <label>Opsi A</label>
                            <input type="text" name="option_a" placeholder="Jawaban A" required>
                        </div>
                        <div class="form-group">
                            <label>Opsi B</label>
                            <input type="text" name="option_b" placeholder="Jawaban B" required>
                        </div>
                        <div class="form-group">
                            <label>Opsi C</label>
                            <input type="text" name="option_c" placeholder="Jawaban C" required>
                        </div>
                        <div class="form-group">
                            <label>Opsi D</label>
                            <input type="text" name="option_d" placeholder="Jawaban D" required>
                        </div>
                        <div class="form-group">
                            <label>Jawaban Benar</label>
                            <select name="correct_answer">
                                <option value="0">A</option>
                                <option value="1">B</option>
                                <option value="2">C</option>
                                <option value="3">D</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>XP Reward</label>
                            <input type="number" name="xp_reward" value="10" placeholder="Poin XP">
                        </div>
                    </div>
                    <button type="submit" name="add_quiz" class="btn-submit"><i class="fas fa-save"></i> Simpan Soal</button>
                </form>
            </div>
        </div>
    </div>

    <!-- Bulk Quiz Form -->
    <div id="quizBulkTab" class="tab-content">
        <div class="card">
            <div class="card-header"><i class="fas fa-layer-group"></i> <h3>Tambah Banyak Soal Sekaligus</h3></div>
            <div class="card-body">
                <form method="post" action="edit_quiz.php" id="bulkQuizForm">
                    <div class="bulk-input-container" id="bulkQuizContainer">
                        <!-- Soal 1 -->
                        <div class="bulk-item" data-index="1">
                            <div class="bulk-item-header">
                                <div class="bulk-item-number">1</div>
                                <div class="bulk-item-title">Soal #1</div>
                            </div>
                            <div class="form-grid">
                                <div class="form-group span-2">
                                    <label>Pertanyaan</label>
                                    <textarea name="bulk_quiz[1][question]" rows="2" placeholder="Tulis pertanyaan..." required></textarea>
                                </div>
                                <div class="form-group">
                                    <label>Opsi A</label>
                                    <input type="text" name="bulk_quiz[1][option_a]" placeholder="Jawaban A" required>
                                </div>
                                <div class="form-group">
                                    <label>Opsi B</label>
                                    <input type="text" name="bulk_quiz[1][option_b]" placeholder="Jawaban B" required>
                                </div>
                                <div class="form-group">
                                    <label>Opsi C</label>
                                    <input type="text" name="bulk_quiz[1][option_c]" placeholder="Jawaban C" required>
                                </div>
                                <div class="form-group">
                                    <label>Opsi D</label>
                                    <input type="text" name="bulk_quiz[1][option_d]" placeholder="Jawaban D" required>
                                </div>
                                <div class="form-group">
                                    <label>Jawaban Benar</label>
                                    <select name="bulk_quiz[1][correct_answer]">
                                        <option value="0">A</option>
                                        <option value="1">B</option>
                                        <option value="2">C</option>
                                        <option value="3">D</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>XP Reward</label>
                                    <input type="number" name="bulk_quiz[1][xp_reward]" value="10" placeholder="XP">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bulk-actions">
                        <button type="button" class="btn-add-bulk" onclick="addBulkQuiz()">
                            <i class="fas fa-plus"></i> Tambah Soal
                        </button>
                        <span style="font-size:12px;color:var(--text-soft)">Maksimal 10 soal</span>
                    </div>
                    <div style="margin-top:16px;">
                        <button type="submit" name="add_bulk_quiz" class="btn-submit">
                            <i class="fas fa-save"></i> Simpan Semua Soal
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header"><i class="fas fa-list"></i> <h3>Daftar Soal Quiz</h3></div>
        <div class="table-wrap">
            <table>
                <thead><tr><th>ID</th><th>Pertanyaan</th><th>A</th><th>B</th><th>C</th><th>D</th><th>Jawaban</th><th>XP</th><th>Aksi</th></tr></thead>
                <tbody>
                    <?php
                    $stmt = $db->prepare("SELECT * FROM quiz_questions ORDER BY id");
                    $stmt->execute();
                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)):
                    ?>
                    <tr>
                        <td class="td-id"><?php echo $row['id']; ?></td>
                        <td style="max-width:220px;word-break:break-word"><?php echo htmlspecialchars(substr($row['question'], 0, 60)) . (strlen($row['question']) > 60 ? '…' : ''); ?></td>
                        <td><?php echo htmlspecialchars($row['option_a']); ?></td>
                        <td><?php echo htmlspecialchars($row['option_b']); ?></td>
                        <td><?php echo htmlspecialchars($row['option_c']); ?></td>
                        <td><?php echo htmlspecialchars($row['option_d']); ?></td>
                        <td><span class="badge badge-green"><?php echo chr(65 + $row['correct_answer']); ?></span></td>
                        <td><span class="badge badge-gold"><?php echo $row['xp_reward']; ?></span></td>
                        <td>
                            <button class="btn-edit" onclick="editQuiz(<?php echo $row['id']; ?>)"><i class="fas fa-edit"></i> Edit</button>
                            <button class="btn-delete" onclick="deleteQuiz(<?php echo $row['id']; ?>)"><i class="fas fa-trash"></i> Hapus</button>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- ========== GAME ========== -->
<div id="gameSection" class="admin-section">
    <div class="page-header">
        <div class="icon-wrap"><i class="fas fa-puzzle-piece"></i></div>
        <div>
            <h2>Edit Game Pairs</h2>
            <p>Kelola pasangan kategori & contoh untuk mini game</p>
        </div>
    </div>

    <!-- Tab untuk Single dan Bulk Input -->
    <div class="tab-container">
        <button class="tab-btn active" onclick="switchGameTab('single')">Input Satu Pair</button>
        <button class="tab-btn" onclick="switchGameTab('bulk')">Input Banyak Pair (Maks 10)</button>
    </div>

    <!-- Single Game Form -->
    <div id="gameSingleTab" class="tab-content active">
        <div class="card">
            <div class="card-header"><i class="fas fa-plus-circle"></i> <h3>Tambah Satu Pair Game</h3></div>
            <div class="card-body">
                <form method="post" action="edit_game.php">
                    <div class="form-grid">
                        <div class="form-group">
                            <label>Match ID</label>
                            <input type="text" name="match_id" placeholder="Contoh: A, B, C..." required>
                        </div>
                        <div class="form-group">
                            <label>Text Content</label>
                            <input type="text" name="text_content" placeholder="Isi konten" required>
                        </div>
                        <div class="form-group">
                            <label>Kategori</label>
                            <select name="category">
                                <option value="kategori">Kategori</option>
                                <option value="contoh">Contoh</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Icon</label>
                            <input type="text" name="icon" value="✅" placeholder="Emoji icon">
                        </div>
                    </div>
                    <button type="submit" name="add_game" class="btn-submit"><i class="fas fa-save"></i> Simpan Pair</button>
                </form>
            </div>
        </div>
    </div>

    <!-- Bulk Game Form -->
    <div id="gameBulkTab" class="tab-content">
        <div class="card">
            <div class="card-header"><i class="fas fa-layer-group"></i> <h3>Tambah Banyak Pair Sekaligus</h3></div>
            <div class="card-body">
                <form method="post" action="edit_game.php" id="bulkGameForm">
                    <div class="bulk-input-container" id="bulkGameContainer">
                        <!-- Pair 1 -->
                        <div class="bulk-item" data-index="1">
                            <div class="bulk-item-header">
                                <div class="bulk-item-number">1</div>
                                <div class="bulk-item-title">Pair #1</div>
                            </div>
                            <div class="form-grid">
                                <div class="form-group">
                                    <label>Match ID</label>
                                    <input type="text" name="bulk_game[1][match_id]" placeholder="Contoh: A, B, C..." required>
                                </div>
                                <div class="form-group">
                                    <label>Text Content</label>
                                    <input type="text" name="bulk_game[1][text_content]" placeholder="Isi konten" required>
                                </div>
                                <div class="form-group">
                                    <label>Kategori</label>
                                    <select name="bulk_game[1][category]">
                                        <option value="kategori">Kategori</option>
                                        <option value="contoh">Contoh</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>Icon</label>
                                    <input type="text" name="bulk_game[1][icon]" value="✅" placeholder="Emoji icon">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bulk-actions">
                        <button type="button" class="btn-add-bulk" onclick="addBulkGame()">
                            <i class="fas fa-plus"></i> Tambah Pair
                        </button>
                        <span style="font-size:12px;color:var(--text-soft)">Maksimal 10 pair</span>
                    </div>
                    <div style="margin-top:16px;">
                        <button type="submit" name="add_bulk_game" class="btn-submit">
                            <i class="fas fa-save"></i> Simpan Semua Pair
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header"><i class="fas fa-list"></i> <h3>Daftar Game Pairs</h3></div>
        <div class="table-wrap">
            <table>
                <thead><tr><th>ID</th><th>Match ID</th><th>Text</th><th>Kategori</th><th>Icon</th><th>Aksi</th></tr></thead>
                <tbody>
                    <?php
                    $stmt = $db->prepare("SELECT * FROM game_pairs ORDER BY id");
                    $stmt->execute();
                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)):
                    ?>
                    <tr>
                        <td class="td-id"><?php echo $row['id']; ?></td>
                        <td><?php echo $row['match_id']; ?></td>
                        <td><?php echo htmlspecialchars($row['text_content']); ?></td>
                        <td><span class="badge badge-green"><?php echo $row['category']; ?></span></td>
                        <td style="font-size:18px"><?php echo $row['icon']; ?></td>
                        <td>
                            <button class="btn-edit" onclick="editGame(<?php echo $row['id']; ?>)"><i class="fas fa-edit"></i> Edit</button>
                            <button class="btn-delete" onclick="deleteGame(<?php echo $row['id']; ?>)"><i class="fas fa-trash"></i> Hapus</button>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- ========== PLAYERS ========== -->
<div id="playersSection" class="admin-section">
    <div class="page-header">
        <div class="icon-wrap"><i class="fas fa-users"></i></div>
        <div>
            <h2>Data Pemain</h2>
            <p>Lihat dan kelola seluruh akun pemain</p>
        </div>
    </div>

    <div class="card">
        <div class="card-header"><i class="fas fa-user-friends"></i> <h3>Semua Pemain</h3></div>
        <div class="table-wrap">
            <table>
                <thead><tr><th>ID</th><th>Username</th><th>Display Name</th><th>Level</th><th>XP</th><th>Bergabung</th><th>Aksi</th></tr></thead>
                <tbody>
                    <?php
                    $stmt = $db->prepare("SELECT * FROM players ORDER BY xp DESC");
                    $stmt->execute();
                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)):
                    ?>
                    <tr>
                        <td class="td-id"><?php echo $row['id']; ?></td>
                        <td><?php echo htmlspecialchars($row['username']); ?></td>
                        <td><?php echo htmlspecialchars($row['display_name']); ?></td>
                        <td><span class="badge badge-green">Level <?php echo $row['level']; ?></span></td>
                        <td><span class="badge badge-gold"><?php echo number_format($row['xp']); ?> XP</span></td>
                        <td><?php echo $row['created_at']; ?></td>
                        <td>
                            <button class="btn-reset" onclick="resetPlayer(<?php echo $row['id']; ?>)"><i class="fas fa-redo"></i> Reset</button>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

</main>
</div><!-- end layout -->

<script>
function switchTab(section) {
    document.querySelectorAll('.nav-btn').forEach(b => b.classList.remove('active'));
    document.querySelectorAll('.admin-section').forEach(s => s.classList.remove('active'));
    document.getElementById(section + 'Section').classList.add('active');
    document.querySelector('[data-section="' + section + '"]').classList.add('active');
}

document.querySelectorAll('.nav-btn').forEach(btn => {
    btn.addEventListener('click', () => switchTab(btn.dataset.section));
});

// Check URL hash on load
const hashMap = { materi: 'materi', quiz: 'quiz', game: 'game', players: 'players', dashboard: 'dashboard' };
const hash = window.location.hash.replace('#', '').replace('Section', '');
if (hashMap[hash]) switchTab(hashMap[hash]);

function editMateri(id) { window.location.href = 'edit_materi.php?edit=' + id; }
function deleteMateri(id) { if(confirm('Hapus materi ini? Data tidak bisa dikembalikan.')) window.location.href = 'edit_materi.php?delete=' + id; }
function editGame(id) { window.location.href = 'edit_game.php?edit=' + id; }
function deleteGame(id) { if(confirm('Hapus game pair ini? Data tidak bisa dikembalikan.')) window.location.href = 'edit_game.php?delete=' + id; }
function editQuiz(id) { window.location.href = 'edit_quiz.php?edit=' + id; }
function deleteQuiz(id) { if(confirm('Hapus soal ini? Data tidak bisa dikembalikan.')) window.location.href = 'edit_quiz.php?delete=' + id; }
function resetPlayer(id) { if(confirm('Reset semua progress pemain ini? Tindakan ini tidak dapat dibatalkan.')) window.location.href = 'reset_player.php?id=' + id; }

// ===== QUIZ TAB SWITCHER =====
function switchQuizTab(tab) {
    document.querySelectorAll('#quizSection .tab-btn').forEach(b => b.classList.remove('active'));
    document.querySelectorAll('#quizSection .tab-content').forEach(t => t.classList.remove('active'));
    
    if (tab === 'single') {
        document.getElementById('quizSingleTab').classList.add('active');
        document.querySelector('#quizSection .tab-btn:nth-child(1)').classList.add('active');
    } else {
        document.getElementById('quizBulkTab').classList.add('active');
        document.querySelector('#quizSection .tab-btn:nth-child(2)').classList.add('active');
    }
}

// ===== GAME TAB SWITCHER =====
function switchGameTab(tab) {
    document.querySelectorAll('#gameSection .tab-btn').forEach(b => b.classList.remove('active'));
    document.querySelectorAll('#gameSection .tab-content').forEach(t => t.classList.remove('active'));
    
    if (tab === 'single') {
        document.getElementById('gameSingleTab').classList.add('active');
        document.querySelector('#gameSection .tab-btn:nth-child(1)').classList.add('active');
    } else {
        document.getElementById('gameBulkTab').classList.add('active');
        document.querySelector('#gameSection .tab-btn:nth-child(2)').classList.add('active');
    }
}

// ===== BULK QUIZ =====
let quizCounter = 1;
function addBulkQuiz() {
    if (quizCounter >= 10) {
        alert('Maksimal 10 soal!');
        return;
    }
    quizCounter++;
    const container = document.getElementById('bulkQuizContainer');
    const template = `
        <div class="bulk-item" data-index="${quizCounter}">
            <div class="bulk-item-header">
                <div class="bulk-item-number">${quizCounter}</div>
                <div class="bulk-item-title">Soal #${quizCounter}</div>
            </div>
            <div class="form-grid">
                <div class="form-group span-2">
                    <label>Pertanyaan</label>
                    <textarea name="bulk_quiz[${quizCounter}][question]" rows="2" placeholder="Tulis pertanyaan..." required></textarea>
                </div>
                <div class="form-group">
                    <label>Opsi A</label>
                    <input type="text" name="bulk_quiz[${quizCounter}][option_a]" placeholder="Jawaban A" required>
                </div>
                <div class="form-group">
                    <label>Opsi B</label>
                    <input type="text" name="bulk_quiz[${quizCounter}][option_b]" placeholder="Jawaban B" required>
                </div>
                <div class="form-group">
                    <label>Opsi C</label>
                    <input type="text" name="bulk_quiz[${quizCounter}][option_c]" placeholder="Jawaban C" required>
                </div>
                <div class="form-group">
                    <label>Opsi D</label>
                    <input type="text" name="bulk_quiz[${quizCounter}][option_d]" placeholder="Jawaban D" required>
                </div>
                <div class="form-group">
                    <label>Jawaban Benar</label>
                    <select name="bulk_quiz[${quizCounter}][correct_answer]">
                        <option value="0">A</option>
                        <option value="1">B</option>
                        <option value="2">C</option>
                        <option value="3">D</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>XP Reward</label>
                    <input type="number" name="bulk_quiz[${quizCounter}][xp_reward]" value="10" placeholder="XP">
                </div>
            </div>
        </div>
    `;
    container.insertAdjacentHTML('beforeend', template);
}

// ===== BULK GAME =====
let gameCounter = 1;
function addBulkGame() {
    if (gameCounter >= 10) {
        alert('Maksimal 10 pair!');
        return;
    }
    gameCounter++;
    const container = document.getElementById('bulkGameContainer');
    const template = `
        <div class="bulk-item" data-index="${gameCounter}">
            <div class="bulk-item-header">
                <div class="bulk-item-number">${gameCounter}</div>
                <div class="bulk-item-title">Pair #${gameCounter}</div>
            </div>
            <div class="form-grid">
                <div class="form-group">
                    <label>Match ID</label>
                    <input type="text" name="bulk_game[${gameCounter}][match_id]" placeholder="Contoh: A, B, C..." required>
                </div>
                <div class="form-group">
                    <label>Text Content</label>
                    <input type="text" name="bulk_game[${gameCounter}][text_content]" placeholder="Isi konten" required>
                </div>
                <div class="form-group">
                    <label>Kategori</label>
                    <select name="bulk_game[${gameCounter}][category]">
                        <option value="kategori">Kategori</option>
                        <option value="contoh">Contoh</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Icon</label>
                    <input type="text" name="bulk_game[${gameCounter}][icon]" value="✅" placeholder="Emoji icon">
                </div>
            </div>
        </div>
    `;
    container.insertAdjacentHTML('beforeend', template);
}
</script>
</body>
</html>