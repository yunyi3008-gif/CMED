<?php
session_start();

if (!isset($_SESSION["student_id"])) {
    header("Location: LoginPage.php");
    exit();
}

// Get student name from DB
require_once "config.php";
$studentName = "student_name";
$stmt = $conn->prepare("SELECT Student_Name FROM student WHERE Student_Id = ?");
if ($stmt) {
    $stmt->bind_param("s", $_SESSION["student_id"]);
    $stmt->execute();
    $stmt->bind_result($nameResult);
    if ($stmt->fetch() && !empty($nameResult)) {
        $studentName = $nameResult;
    }
    $stmt->close();
}
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home — Memo Campus</title>
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

        /* ── Welcome ── */
        .welcome-section {
            padding: 20px 16px 10px;
        }
        .welcome-section h2 {
            font-size: 22px;
            color: #2d1b5e;
            font-weight: bold;
        }

        /* ── Banner Carousel ── */
        .banner-section {
            padding: 10px 16px;
        }
        .banner-wrapper {
            overflow: hidden;
            border-radius: 16px;
        }
        .banner-track {
            display: flex;
            transition: transform 0.4s ease;
        }
        .banner-slide {
            min-width: 100%;
            height: 160px;
            border-radius: 16px;
            display: flex;
            align-items: flex-end;
            padding: 14px;
            position: relative;
            overflow: hidden;
        }
        .banner-slide:nth-child(1) { background: linear-gradient(135deg, #1a237e 0%, #4a148c 60%, #880e4f 100%); }
        .banner-slide:nth-child(2) { background: linear-gradient(135deg, #004d40 0%, #00695c 60%, #1b5e20 100%); }
        .banner-slide:nth-child(3) { background: linear-gradient(135deg, #bf360c 0%, #e64a19 60%, #f57f17 100%); }
        .banner-text h3 {
            font-size: 16px;
            color: white;
            font-weight: bold;
            line-height: 1.3;
            text-shadow: 0 1px 4px rgba(0,0,0,0.4);
        }
        .banner-text p {
            font-size: 11px;
            color: rgba(255,255,255,0.8);
            margin-top: 4px;
        }
        .banner-tag {
            position: absolute;
            bottom: 14px;
            right: 14px;
            background-color: #ffd600;
            color: #2d1b5e;
            font-size: 10px;
            font-weight: bold;
            padding: 4px 10px;
            border-radius: 20px;
            letter-spacing: 0.5px;
        }
        .banner-dots {
            display: flex;
            justify-content: center;
            gap: 6px;
            margin-top: 10px;
        }
        .dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background-color: #c9b4f5;
            cursor: pointer;
            transition: background 0.3s;
        }
        .dot.active { background-color: #7c4dff; }

        /* ── Recommended Links ── */
        .section-title {
            background-color: #7c4dff;
            color: white;
            font-size: 16px;
            font-weight: bold;
            padding: 12px 16px;
            margin-top: 16px;
        }
        .links-list {
            padding: 10px 16px;
            display: flex;
            flex-direction: column;
            gap: 10px;
        }
        .link-card {
            display: flex;
            align-items: center;
            gap: 16px;
            background: white;
            border-radius: 16px;
            padding: 14px 16px;
            text-decoration: none;
            color: #2d1b5e;
            box-shadow: 0 2px 8px rgba(124,77,255,0.08);
            transition: transform 0.15s, box-shadow 0.15s;
        }
        .link-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 16px rgba(124,77,255,0.15);
            text-decoration: none;
        }
        .link-icon {
            width: 56px;
            height: 56px;
            border-radius: 50%;
            background-color: #f0e8ff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 26px;
            flex-shrink: 0;
        }
        .link-card .link-label {
            font-size: 16px;
            font-weight: bold;
        }

        /* ── Fixed Footer ── */
        .bottom-footer {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            background: white;
            border-top: 1px solid #e0d4fc;
            padding: 10px 0 14px;
        }
        .footer-copy {
            text-align: center;
            font-size: 11px;
            color: #aaa;
            padding-top: 6px;
        }
        
    </style>
</head>
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

    <!-- Welcome -->
    <div class="welcome-section">
        <h2>Welcome, <?= htmlspecialchars($studentName) ?>!</h2>
    </div>

    <!-- Banner Carousel -->
    <div class="banner-section">
        <div class="banner-wrapper">
            <div class="banner-track" id="bannerTrack">

                <div class="banner-slide">
                    <div class="banner-text">
                        <h3>Mid-Autumn Festival<br>Networking Event</h3>
                        <p>Mooncake tasting · Game · Prize</p>
                    </div>
                    <div class="banner-tag">UPCOMING EVENTS</div>
                </div>

                <div class="banner-slide">
                    <div class="banner-text">
                        <h3>Library Book Fair<br>2025</h3>
                        <p>Grab your favourite reads at great prices</p>
                    </div>
                    <div class="banner-tag">NEW</div>
                </div>

                <div class="banner-slide">
                    <div class="banner-text">
                        <h3>Campus Sports Day<br>Registration Open</h3>
                        <p>Sign up before 30 Sept 2025</p>
                    </div>
                    <div class="banner-tag">REGISTER NOW</div>
                </div>

            </div>
        </div>
        <div class="banner-dots">
            <div class="dot active" onclick="goToSlide(0)"></div>
            <div class="dot" onclick="goToSlide(1)"></div>
            <div class="dot" onclick="goToSlide(2)"></div>
        </div>
    </div>

    <!-- Recommended Links -->
    <div class="section-title">Recommended Links</div>
    <div class="links-list">
        <a href="ClubServices.php" class="link-card">
            <div class="link-icon">⚙️</div>
            <span class="link-label">Club Services</span>
        </a>
        <a href="MyBookingEvents.php" class="link-card">
            <div class="link-icon">📚</div>
            <span class="link-label">My Booking Events</span>
        </a>
        <a href="EventCalendar.php" class="link-card">
            <div class="link-icon">📅</div>
            <span class="link-label">Event Calendar</span>
        </a>
    </div>

    <div class="footer">
        <div class="footer-copy">©2026 Memo Campus</div>
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

        // Banner carousel
        var currentSlide = 0;
        var totalSlides = 3;

        function goToSlide(index) {
            currentSlide = index;
            document.getElementById("bannerTrack").style.transform = "translateX(-" + (index * 100) + "%)";
            document.querySelectorAll(".dot").forEach(function(d, i) {
                d.classList.toggle("active", i === index);
            });
        }

        // Auto-advance every 4 seconds
        setInterval(function() {
            goToSlide((currentSlide + 1) % totalSlides);
        }, 4000);
    </script>

</body>
</html>