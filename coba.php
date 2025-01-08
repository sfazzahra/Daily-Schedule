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
                    <button onclick="location.href='taksdatadosen.php'">Create Task</button>
                <?php endif;?>
                
                <?php if($role === "mahasiswa"): ?>
                    <button onclick="location.href='taksdatastudent.php'">Task Data</button>
                    <button onclick="location.href='notifications.php'">Notifications</button>    
                <?php endif; ?> 
                
                
                <button onclick="location.href='history.php'">History</button>
            </nav>
            <div class="menu-icon" onclick="toggleSidebar()"></div>
        <div class="logout-btn">
            <a href="logout.php">Logout ⮞</a>
        </div>
        </aside>
        
        <?php 
                    $tgl = date("Y-m-d");
                    $periksa=$this->db->query("SELECT nama_member, DATEDIFF(tgl_kembali,'$tgl') AS interval_tgl FROM tb_peminjaman_buku INNER JOIN tb_member ON tb_member.id_member=tb_peminjaman_buku.id_member WHERE keterangan='dipinjam'")->result_array();
                    foreach($periksa as $buku){
                        
                        $nama_member=$buku['nama_member'];
                        if($buku['interval_tgl']==1){ 
                            echo "<div style='padding:5px' style='width:50px' ><span class='glyphicon glyphicon-info-sign'></span> Member <a style='color:red'>" .$nama_member."</a>, Besok adalah Batas pengembalian buku </div>"; 
                        } elseif ($buku['interval_tgl']==2) {
                             echo "<div style='padding:5px' style='width:50px' ><span class='glyphicon glyphicon-info-sign'></span> Member <a style='color:red'>" .$nama_member."</a> Batas pengembalian buku tinggal 2 hari </div>"; 
                        } elseif ($buku['interval_tgl']==3) {
                             echo "<div style='padding:5px' style='width:50px' ><span class='glyphicon glyphicon-info-sign'></span> Member <a style='color:red'>" .$nama_member."</a> Batas pengembalian buku tinggal 3 hari </div>"; 
                        } elseif ($buku['interval_tgl']==0) {
                             echo "<div style='padding:5px' style='width:50px' ><span class='glyphicon glyphicon-info-sign'></span> Member <a style='color:red'>" .$nama_member."</a> Hari ini adalah batas pengembalian buku </div>"; 
                        } elseif ($buku['interval_tgl']<0) {
                             echo "<div style='padding:5px' style='width:50px' ><span class='glyphicon glyphicon-info-sign'></span> Member <a style='color:red'>" .$nama_member."</a> Telah melewati batas pengembalian buku </div>"; 
                        } else {
                            echo "";
                        }
                    }
                    ?>

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
