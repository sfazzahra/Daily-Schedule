<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

$role = $_SESSION['role'];
$username = htmlspecialchars($_SESSION['username']);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Schedule Data</title>
    <link rel="stylesheet" href="styles6.css">
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

        button {
            background-color: #729bbd;
            color: white;
            padding: 8px 12px;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            font-size: 16px;
            margin: 4px 2px;
            cursor: pointer;
        }

        /* Status color */
        .status.in-progress {
            background-color: orange;
        }

        .status.done {
            background-color: green;
        }

        /* Button style for status */
        .status-button {
            padding: 6px 12px;
            font-size: 14px;
            border: none;
            cursor: pointer;
        }

        .status-button.in-progress {
            background-color: orange;
            color: white;
        }

        .status-button.done {
            background-color: green;
            color: white;
        }

        .status-button:disabled {
            cursor: not-allowed;
        }
    </style>
</head>
<body>
    <div class="container">
    <aside class="sidebar">
        <div class="profile">
            <div class="avatar"></div>
            <p class="navbar-brand text-white">WELCOME <?php echo strtoupper($username); ?></p>
        </div>
        <nav class="menu">
            <button onclick="location.href='dashboard.php'">Dashboard</button>

            <?php if ($role === "dosen"): ?> 
                <button onclick="location.href='taksdatadosen.php'">Create Schedule</button>
            <?php endif; ?>

            <?php if ($role === "mahasiswa"): ?>
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
        <h1>Schedule</h1>

        <table class="table table-striped table-bordered">
            <thead> 
                <tr> 
                  <th scope="col">No</th> 
                  <th scope="col">Schedule Title</th> 
                  <th scope="col">Date</th> 
                  <th scope="col">Status</th> 
                  <th scope="col">Note</th> 
                  <th scope="col">Action</th>
                </tr> 
            </thead> 
            <tbody> 
                <?php 
                include 'koneksi.php'; 
                $query = mysqli_query($koneksi, "SELECT * FROM task"); 
                if (!$query) {
                    die('Error: ' . mysqli_error($koneksi));
                }
                $no = 1; 
                while ($data = mysqli_fetch_assoc($query)) { 
                ?> 
                <tr id="row-<?php echo $data['id']; ?>"> 
                  <td><?php echo $no++; ?></td> 
                  <td><?php echo htmlspecialchars($data['title']); ?></td> 
                  <td><?php echo htmlspecialchars($data['datee']); ?></td>
                  <td id="status-<?php echo $data['id']; ?>" class="status">
                    <button class="status-button <?php echo strtolower($data['statuss']); ?>" 
                            data-id="<?php echo $data['id']; ?>" 
                            data-status="<?php echo $data['statuss']; ?>"
                            <?php echo (strtolower($data['statuss']) == 'done' || strtolower($data['statuss']) == 'in progress') ? 'disabled' : ''; ?>>
                        <?php echo htmlspecialchars($data['statuss']); ?>
                    </button>
                  </td>
                  <td><?php echo htmlspecialchars($data['note']); ?></td>
                  <td>
                    <button 
                        class="update-btn" 
                        data-id="<?php echo $data['id']; ?>" 
                        data-status="<?php echo $data['statuss']; ?>">Update</button>
                  </td>   
                </tr> 
                <?php 
                } 
                ?> 
            </tbody> 
        </table>
    </div> 
</div>

<script>
    // Event listener for status buttons
    document.querySelectorAll('.status-button').forEach(button => {
        button.addEventListener('click', function () {
            const taskId = this.getAttribute('data-id');
            const currentStatus = this.getAttribute('data-status');

            // Prompt for new status
            const newStatus = prompt("Choose a status:\n1. In Progress\n2. Done", currentStatus);
            if (newStatus) {
                let statusClass = "";
                if (newStatus.toLowerCase() === "in progress") {
                    statusClass = "in-progress";
                } else if (newStatus.toLowerCase() === "done") {
                    statusClass = "done";
                } else {
                    alert("Status tidak valid");
                    return;
                }

                // Kirim data ke server
                fetch('update_task.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ id: taskId, status: newStatus })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Update status di tabel dan tambahkan kelas warna
                        const statusElement = document.getElementById(`status-${taskId}`);
                        const button = statusElement.querySelector('button');
                        button.textContent = newStatus;
                        button.className = `status-button ${statusClass}`;
                        alert('Status successfully updated!');
                    } else {
                        alert('Failed to update status.: ' + data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred while updating the status.');
                });
            }
        });
    });

    // Event listener for update button
    document.querySelectorAll('.update-btn').forEach(button => {
        button.addEventListener('click', function () {
            const taskId = this.getAttribute('data-id');
            const statusElement = document.getElementById(`status-${taskId}`).querySelector('button');
            // Trigger the status change when Update button is clicked
            if (statusElement && !statusElement.disabled) {
                statusElement.click();
            }
        });
    });
</script>


</body>
</html>
