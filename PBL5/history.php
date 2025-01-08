<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}
$role = $_SESSION['role'];
?>

<?php
// Koneksi ke database
$host = "localhost"; // Ganti sesuai konfigurasi Anda
$username = "root"; // Ganti sesuai konfigurasi Anda
$password = ""; // Ganti sesuai konfigurasi Anda
$dbname = "pbl"; // Ganti sesuai nama database Anda

$conn = new mysqli($host, $username, $password, $dbname);

// Cek koneksi
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Ambil data dari tabel history
$historyData = [];
$sql = "SELECT * FROM task";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $historyData[] = $row;
    }
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>History</title>
    <link rel="stylesheet" href="stylesh.css">
    <style>
        body {
            font-family: Arial, sans-serif;

        }
        .container {
            display: flex;
        }

        .sidebar {
            width: 20%;
            background-color: #f4f4f4;
            padding: 20px;
        }

        .main-content {
            flex: 1;
            padding: 20px;
            position: relative;
        }
        .main-content h1 {
            font-size: 1.8em;
            color: black;
            display: flex;
            align-items: center;
        }
        .main-content h1::before {
            content: "";
            margin-right: 8px;
        }
        
        /* History Section */
        .history-container {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
            margin-top: 20px;
        }

        .history-item {
            background-color: #C8D9E6;
            padding: 20px;
            border-radius: 8px;
            color: white;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            font-size: 1.1em;
        }
        
        .history-item h3 {
            margin: 0;
            font-size: 1.1em;
            margin-bottom: 5px;
            color: black;
        }
        .history-item p {
            margin: 5px 0;
            padding: 8px;
            background-color: #f9f9f9;
            border-radius: 5px;
            color: #333;
        }
        .history-item .edit-button {
            align-self: flex-end;
            padding: 5px 10px;
            background-color: #C8D9E6;
            border: none;
            color: white;
            cursor: pointer;
            border-radius: 5px;
            font-size: 0.9em;
        }
        
    </style>
</head>
<body>
    <div class="container">
        <aside class="sidebar">
            <div class="profile">
                <div class="avatar"></div>
                <p class="navbar-brand text-white" href="#">WELCOME <?php echo strtoupper(string: $_SESSION['username']); ?></p>
            </div>
            <nav class="menu">
                <button onclick="location.href='dashboard.php'">Dashboard</button>

                <?php if($role === "dosen"): ?> 
                    <button onclick="location.href='taksdatadosen.php'">Create Schedule</button>
                <?php endif;?>
                
                <?php if($role === "mahasiswa"): ?>
                    <button onclick="location.href='taksdatastudent.php'">Schedule Data</button>
                    <button onclick="location.href='notifications.php'">Notifications</button>    
                <?php endif; ?> 
                
                
                <button onclick="location.href='history.php'">History</button>
            </nav>
            <div class="menu-icon" onclick="toggleSidebar()"></div>
        <div class="logout-btn">
            <a href="logout.php">Logout ⮞</a>
        </div>
        </aside>

        <!-- Main Content -->
        <div class="main-content">
            <h1>History</h1>
            
            <!-- History Items -->
            <div class="history-container">
                <?php if (count($historyData) > 0): ?>
                    <?php foreach ($historyData as $history): ?>
                        <div class="history-item">
                            <h3>Title: <?= htmlspecialchars($history['title']) ?></h3>
                            <p>Date: <?= htmlspecialchars($history['datee']) ?></p>
                            <p>Note: <?= htmlspecialchars($history['note']) ?></p>
                            <p>Status: <?= htmlspecialchars($history['statuss']) ?></p>
                            
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="no-history">No History</div>
                <?php endif; ?>
            </div>
        </div>
        
    </div>
</body>
</html>
