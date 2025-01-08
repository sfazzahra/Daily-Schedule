<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}
$role = $_SESSION['role'];
?>


<?php
// Konfigurasi Database
$host = 'localhost';
$user = 'root';
$password = ''; // Ubah sesuai dengan password database Anda
$dbname = 'pbl'; // Nama database Anda

// Koneksi ke Database
$conn = new mysqli($host, $user, $password, $dbname);

// Periksa Koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Menambahkan Notifikasi Baru
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_notification'])) {
    $title = $_POST['notificationTitle'];
    $date = $_POST['notificationDate'];
    $type = $_POST['notificationType'];

    $stmt = $conn->prepare("INSERT INTO notifications (title, date, type) VALUES (?, ?, ?)");
    $stmt->bind_param('sss', $title, $date, $type);

    if ($stmt->execute()) {
        echo "<script>alert('Notification added successfully!');</script>";
    } else {
        echo "<script>alert('Failed to add notification.');</script>";
    }
    $stmt->close();
}

// Mendapatkan Data Notifikasi
$sql = "SELECT * FROM notifications ORDER BY date DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notification</title>
    <link rel="stylesheet" href="styles7.css">
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        .container {
            display: flex;
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
        

        .sidebar {
            width: 20%;
            background-color: #f4f4f4;
            padding: 20px;
        }
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            justify-content: center;
            align-items: center;
        }
        .modal-content {
            background-color: white;
            padding: 20px;
            border-radius: 5px;
            width: 400px;
        }
        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .modal-header h2 {
            margin: 0;
        }
        .close-button {
            background: none;
            border: none;
            font-size: 20px;
            cursor: pointer;
        }
        .modal-footer {
            text-align: right;
        }
        .modal-footer button {
            margin-left: 10px;
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
        
        <div class="main-content">
            <div class="accordion">
                <div class="col-md-10 p-5 pt-2">
                    <button type="button" class="btn btn-primary mb-2" id="openModalButton">
                        <i class="fas fa-plus-circle me-2"></i> + Set Reminder
                    </button>
                </div>
                <?php if ($result->num_rows > 0): ?>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <div class="accordion-item">
                            <div class="accordion-header" onclick="toggleAccordion(this)"><?php echo $row['title']; ?></div>
                            <div class="accordion-content">
                                <p><strong>Date:</strong> <?php echo $row['date']; ?></p>
                                <p><strong>Notification Type:</strong> <span class="badge"><?php echo $row['type']; ?></span></p>
                                <button class="update-button">Done</button>
                            </div>
                        </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <p>No notifications found.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Add Notification Modal -->
    <div class="modal" id="addNotificationModal">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Add Notification</h2>
                <button class="close-button" id="closeModalButton">&times;</button>
            </div>
            <div class="modal-body">
                <form method="POST">
                    <label for="notificationTitle">Title:</label><br>
                    <input type="text" id="notificationTitle" name="notificationTitle" required><br><br>

                    <label for="notificationDate">Date:</label><br>
                    <input type="datetime-local" id="notificationDate" name="notificationDate" required><br><br>

                    <label for="notificationType">Type:</label><br>
                    <select id="notificationType" name="notificationType" required>
                        <option value="Visual Alert">Visual Alert</option>
                    </select></br><br>
                    <div class="modal-footer">
                        <button type="button" id="cancelButton">Cancel</button>
                        <button type="submit" name="add_notification">Add</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        const addModal = document.getElementById('addNotificationModal');
        const openModalButton = document.getElementById('openModalButton');
        const closeModalButton = document.getElementById('closeModalButton');
        const cancelButton = document.getElementById('cancelButton');

        openModalButton.addEventListener('click', () => {
            addModal.style.display = 'flex';
        });

        closeModalButton.addEventListener('click', () => {
            addModal.style.display = 'none';
        });

        cancelButton.addEventListener('click', () => {
            addModal.style.display = 'none';
        });

        function toggleAccordion(header) {
            const content = header.nextElementSibling;
            const allContents = document.querySelectorAll('.accordion-content');

            allContents.forEach((item) => {
                if (item !== content) {
                    item.style.display = 'none';
                }
            });

            content.style.display = content.style.display === 'block' ? 'none' : 'block';
        }
    </script>
</body>
</html>
