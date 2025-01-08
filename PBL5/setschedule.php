<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = htmlspecialchars($_POST['title']);
    $datee = htmlspecialchars($_POST['datee']);
    $note = htmlspecialchars($_POST['note']);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Task Scheduler</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        .container {
            display: flex;
        }
        .sidebar {
            width: 15%;
            background-color: #f4f4f4;
            padding: 20px;
        }
        .main-content {
            width: 80%;
            padding: 20px;
        }
        .form-group {
            margin-bottom: 15px;
        }
        label {
            display: block;
            margin-bottom: 5px;
        }
        .form-actions {
            margin-top: 20px;
        }
        .result-container {
            margin-top: 30px;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
            background-color: #f9f9f9;
        }
    </style>
</head>
<body>
    <div class="container">
    <aside class="sidebar">
            <div class="profile">
                <div class="avatar"></div>
                <a class="navbar-brand text-white" href="#">Welcome <?php echo strtoupper(string: $_SESSION['username']); ?></a>
            </div>
            <nav class="menu">
                <button onclick="location.href='dashboard.php'">Dashboard</button>

                <?php if($role === "dosen"): ?> 
                    <button onclick="location.href='Frame5.php'">Create Task</button>
                <?php endif;?>
                
                <?php if($role === "mahasiswa"): ?>
                    <button onclick="location.href='taksdatastudent.php'">Task Data</button>
                    <button onclick="location.href='notifications.php'">Notifications</button>    
                <?php endif; ?> 
                
                
                <button onclick="location.href='history.php'">History</button>
            </nav>
        </aside>  >
        </aside>
        <main class="main-content">
            <h1>Set the Date and Schedule</h1>
            <?php if (!empty($title)) { ?>
                <div class="result-container">
                    <h2>Task Details</h2>
                    <p><strong>Title:</strong> <?php echo $title; ?></p>
                    <p><strong>Date:</strong> <?php echo $datee; ?></p>
                    <p><strong>Note:</strong> <?php echo $note; ?></p>
                    <p><strong>Attachment:</strong> - </p>
                </div>
            <?php } else { ?>
                <form id="schedule-form" class="schedule-form" method="POST">
                    <div class="form-group">
                        <label for="title">Schedule Title:</label>
                        <input type="text" id="title" name="title" placeholder="" required>
                    </div>
                    <div class="form-group">
                        <label for="datee">Date:</label>
                        <input type="datee" id="time" name="datee" required>
                    </div>
                    <div class="form-group">
                        <label for="note">Note:</label>
                        <textarea id="note" name="note" placeholder="" required></textarea>
                    </div>
                    <div class="form-group">
                        <label>Add File:</label>
                        <button type="button">Files</button>
                    </div>
                    <div class="form-actions">
                        <button type="button" class="back-btn" >Back</button>
                        <button type="submit" class="save-btn" >Save</button>
                    </div>
                </form>
            <?php } ?>
        </main>
    </div>
</body>
</html>
