<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}
$role = $_SESSION['role'];

// Koneksi ke database
$servername = "localhost"; // Ganti dengan host database Anda
$username = "root";       // Ganti dengan username database Anda
$password = "";           // Ganti dengan password database Anda
$dbname = "pbl";      // Ganti dengan nama database Anda

$conn = new mysqli($servername, $username, $password, $dbname);

// Cek koneksi
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Ambil data tugas dari database
$sql = "SELECT * FROM task";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="styles4.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            width: 100vw;
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
        
        .search-bar {
            margin-bottom: 20px;
        }
        .task-item {
            border: 1px solid #ddd;
            padding: 0px;
            margin-bottom: 10px;
            border-radius: 5px;
        }
    </style>
</head>
<body>
    <div class="container">
        <aside class="sidebar">
            <div class="profile">
                <div class="avatar" href="profile.jpg"></div>
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
            <div class="search-bar">
                <input type="text" placeholder="Search task">
            </div>
            
            <h1>Dashboard</h1>
            
            <!-- Timeline -->
            <div class="timeline">
                <h2>Timeline</h2>
                
                <?php
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<div class='task-item'>";
                        echo "<h3>" . htmlspecialchars($row['title']) . "</h3>";
                        echo "<p><strong>Due Date:</strong> " . htmlspecialchars($row['datee']) . "</p>";
                        echo "<p><strong>Note:</strong> " . htmlspecialchars($row['note']) . "</p>";
                        echo "</div>";
                    }
                } else {
                    echo "<p>No tasks available.</p>";
                }
                ?>
            </div>            
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php
$conn->close();
?>
