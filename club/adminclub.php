<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>A.CLUB.Page</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css">
     <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }
        body {
            background-color: #f7f2fe;
            min-height: 100vh;
            padding-bottom: 80px;
        }

        /* ── Top Header ── */
        .top-header {
            background-color: #f7f2fe;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 10px 16px;
            border-bottom: 1px solid #e0d4fc;
            position: sticky;
            top: 0;
            z-index: 100;
        }
        .top-header-left {
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .top-header-left img {
            width: 48px;
            height: 48px;
            object-fit: contain;
        }
        .top-header-left .site-title {
            font-size: 20px;
            font-weight: bold;
            color: #2d1b5e;
            letter-spacing: 1px;
        }
        .top-header-right {
            display: flex;
            align-items: center;
            gap: 4px;
        }
        .icon-btn {
            background: none;
            border: none;
            cursor: pointer;
            font-size: 22px;
            color: #2d1b5e;
            padding: 6px;
            line-height: 1;
            width: auto;
            margin: 0;
            border-radius: 50%;
        }
        .icon-btn:hover { background-color: #e0d4fc; }
        .hamburger {
            display: flex;
            flex-direction: column;
            gap: 5px;
            cursor: pointer;
            padding: 6px;
            border-radius: 6px;
        }
        .hamburger:hover { background-color: #e0d4fc; }
        .hamburger span {
            display: block;
            width: 22px;
            height: 2.5px;
            background-color: #2d1b5e;
            border-radius: 2px;
        }

        /* ── Side Menu ── */
        .overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,0.35);
            z-index: 200;
        }
        .overlay.open { display: block; }
        .side-menu {
            position: fixed;
            top: 0;
            left: -280px;
            width: 260px;
            height: 100%;
            background: white;
            z-index: 300;
            transition: left 0.3s ease;
            padding: 30px 20px;
            box-shadow: 4px 0 20px rgba(0,0,0,0.15);
        }
        .side-menu.open { left: 0; }
        .side-menu h3 {
            font-size: 16px;
            color: #7c4dff;
            margin-bottom: 24px;
            padding-bottom: 10px;
            border-bottom: 2px solid #e0d4fc;
        }
        .side-menu a {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 8px;
            color: #2d1b5e;
            font-size: 15px;
            text-decoration: none;
            border-radius: 10px;
            margin-bottom: 4px;
        }
        .side-menu a:hover { background-color: #f7f2fe; text-decoration: none; }
        .side-menu .menu-icon { font-size: 20px; }
        .side-menu .logout-link {
            color: #e53935;
            margin-top: 20px;
            border-top: 1px solid #eee;
            padding-top: 16px;
        }
    </style>
</head>


<?php  

include_once'config.php';

    if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_club'])) {
        $Club_Name = $_POST['Club_Name'];
        $Club_Image = $_FILES['Club_Image'] ['name'];

         $sql="INSERT into club (Club_Name,Club_Image) VALUES 
         ('$Club_Name','$Club_Image')";

    if($conn->query($sql)===TRUE){
        echo " New student added successfully";
    }else {
        echo " Error: " . $sql . " <br> " . $conn->error;
    }

    $conn->close();
    }




?>



<body>
    

    <!-- Overlay -->
    <div class="overlay" id="overlay" onclick="closeMenu()"></div>

    <!-- Side Menu -->
    <div class="side-menu" id="sideMenu">
        <h3><img src="MemoCampusLogo.png" alt="Memo Campus Logo" style="width: 30px; height: 30px; margin-right: 10px;">MEMO CAMPUS</h3>
        <a href="CampusSystemHomePage.php"><span class="menu-icon">🏠</span> Home</a>
        <a href="#"><span class="menu-icon">📅</span> Club</a>
        <a href="#"><span class="menu-icon">📖</span> My Booking Events</a>
        <a href="#"><span class="menu-icon">⚙️</span> Settings</a>
        <a href="LoginPage.php" class="logout-link"><span class="menu-icon">🚪</span> Log Out</a>
    </div>

    <!-- Top Header -->
    <div class="top-header">
        <div class="top-header-left">
            <div class="hamburger" onclick="openMenu()">
                <span></span><span></span><span></span>
            </div>
            <img src="MemoCampusLogo.png" alt="Memo Campus Logo">
            <span class="site-title">MEMO CAMPUS</span>
        </div>
        <div class="top-header-right">
            <button class="icon-btn" title="Notifications">🔔</button>
            <button class="icon-btn" title="Profile">👤</button>
        </div>
    </div>
















   
    <div class="container mt-5">

          <h1>Club Admin</h1>
        
 
         <h3>Add New Club</h3>

    <form action="viewclub.php" method="post" enctype="multipart/form-data">
        <label for="Club_Name">Club Name:</label>
        <input class="form-control mt-4" type="text" id="Club_Name" name="Club_Name" placeholder="Enter club" required>
        <br><br>

        <label for="Club_Image">Club Image:</label>
        <input class="form-control mt-4"  type="file" name="Club_Image" id="" accept="image/*" required>

        <br><br>
      <button class="btn btn-primary mt-4" type="submit" value="Upload" name="submit">Add Club</button>                                  

    </form>
    </div>







 <script>
        // Side menu
        function openMenu() {
            document.getElementById("sideMenu").classList.add("open");
            document.getElementById("overlay").classList.add("open");
        }
        function closeMenu() {
            document.getElementById("sideMenu").classList.remove("open");
            document.getElementById("overlay").classList.remove("open");
        }
    </script>   

</body>
</html>