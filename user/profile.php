<?php
// user/profile.php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/Database.php'; 

session_name(SESSION_NAME);
session_start();

require_role(['resident','manager','admin']);

// ✅ FIX: Use getInstance()->pdo() to solve the Fatal Error
$db = Database::getInstance()->pdo(); 

$role = $_SESSION['user']['role'] ?? '';
$dashboardUrl = ($role === 'admin') ? '../admin/dashboard.php' : (($role === 'manager') ? '../index2.php' : '../index.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Profile</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/MDstyle.css">
</head>
<body class="MDbody">

    <header class="MDheader">
        <h1>My Profile</h1>
        <nav class="MDnav">
            <a href="<?= $dashboardUrl; ?>" class="MDbtn">← Dashboard</a>
        </nav>
    </header>

    <div class="container">
        <div class="form-card" style="max-width: 500px; margin: 2rem auto;">
            <div style="text-align: center; margin-bottom: 2rem;">
                <div style="width: 80px; height: 80px; background: #007bff; color: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 2rem; font-weight: bold; margin: 0 auto 1rem;">
                    <?= strtoupper(substr($_SESSION['user']['username'], 0, 1)) ?>
                </div>
                <h2 style="margin:0;">User Profile</h2>
                <p style="color: #666; margin:0;">Account Overview</p>
            </div>

            <div style="border-top: 1px solid #eee; padding-top: 1rem;">
                <div style="display: flex; justify-content: space-between; padding: 0.8rem 0; border-bottom: 1px solid #f8fafc;">
                    <span style="color: #666; font-weight: 600;">Username</span>
                    <span style="font-weight: 500;"><?= htmlspecialchars($_SESSION['user']['username']); ?></span>
                </div>
                
                <div style="display: flex; justify-content: space-between; padding: 0.8rem 0; border-bottom: 1px solid #f8fafc;">
                    <span style="color: #666; font-weight: 600;">Role</span>
                    <span style="background: #e0e7ff; color: #4338ca; padding: 2px 10px; border-radius: 20px; font-size: 0.8rem; font-weight: bold; text-transform: uppercase;">
                        <?= htmlspecialchars($_SESSION['user']['role']); ?>
                    </span>
                </div>

                <div style="display: flex; justify-content: space-between; padding: 0.8rem 0;">
                    <span style="color: #666; font-weight: 600;">Status</span>
                    <span style="color: #10b981; font-weight: 500;">● Active</span>
                </div>
            </div>

            <div style="margin-top: 2rem; text-align: center;">
                </div>
        </div>
    </div>

</body>
</html>