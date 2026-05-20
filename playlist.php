<?php
session_start();
$conn = mysqli_connect("localhost", "root", "", "music");
if (!$conn) {
    die("Connection failed" . mysqli_connect_error());
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>All Songs</title>
    <link rel="stylesheet" href="login.css">
    <style>
        body {
            background-color: #111;
            color: white;
            font-family: sans-serif;
            margin: 0;
        }

        header {
            display: flex;
            align-items: center;
            padding: 10px 20px;
            background-color: #222;
        }

        .logo {
            display: flex;
            align-items: center;
            cursor: pointer;
        }

        .logo img {
            height: 40px;
            margin-right: 10px;
        }

        h2 {
            margin-left: 20px;
            color: white;
        }

        .scroll-container {
            overflow-x: auto;
            white-space: nowrap;
            padding: 10px;
            scroll-behavior: smooth;
            scrollbar-width: none;        /* Firefox */
            -ms-overflow-style: none;     /* IE and Edge */
        }

        .scroll-container::-webkit-scrollbar {
            display: none;               /* Chrome, Safari */
        }

        .gallery {
            display: flex;
            flex-direction: row;
            gap: 15px;
            align-items: center;
            padding: 20px 0;	
        }

        .image {
            width: 210px;
            height: 210px;
            border-radius: 15px;
            object-fit: cover;
            cursor: pointer;
            
            opacity: 0.9;
            transition: all 0.3s ease;
        }

        .gallery .image:hover {
            filter: none;
            opacity: 1;
            transform: scale(1.1);
            z-index: 2;
        }

        .scroll-item {
            text-align: center;
            flex-shrink: 0;
        }

        .scroll-item h3 {
            font-size: 16px;
        }

        /* Style the audio player container */
.audio-player {
    width: 180px;
    border-radius: 10px;
    background-color: transparent;
}

/* Style the controls panel (Chrome/Edge only) */
audio::-webkit-media-controls-panel {
    background: linear-gradient(135deg, #ff00cc, #3333ff) !important;
    color: white;
}

/* Progress bar styling */
audio::-webkit-media-controls-timeline {
    background: transparent;
}

audio::-webkit-media-controls-current-time-display,
audio::-webkit-media-controls-time-remaining-display {
    color: white;
}

/* Play/pause button color */
audio::-webkit-media-controls-play-button {
    background-color: transparent;
    filter: hue-rotate(300deg) saturate(200%) brightness(120%);
}

		
		.image.selected {
    filter: none;
    opacity: 1;
    transform: scale(1.1);
    outline: 3px solid #00ffcc; /* highlight border */
    z-index: 3;
}

		.scroll-item.playing {
    border: 2px solid #ff00cc; /* Purple-pink */
    border-radius: 10px;
    box-shadow: 0 0 15px rgba(255, 0, 204, 0.5);
    padding: 5px;
    transition: all 0.3s ease;
}
		.scroll-item.playing .image {
    filter: none;
    opacity: 1;
    transform: scale(1.1);
    z-index: 2;
}

    </style>
</head>
<body>

<header>
    <button id="openBtn">☰</button>
    <div class="logo" onclick="window.location.href='home.html'">
        <img src="pics/logos/vibecast.png" alt="Vibe Cast Logo">
        <h3>VIBE CAST</h3>
    </div>
	<nav class="nav">
            <a href="after_login.php">Home</a>
            <a href="#" class="active">All Songs</a>
            <a href="myplaylist.php">My Playlist</a>
            <div class="search-container">
				<img src="pics/logos/search.png" alt="search">
                <span class="search-icon material-symbols-outlined" id="searchIcon" ></span>
                <form method="get" action="search.php">
                <input type="text" name="searchInput" class="search-box" id="searchInput" placeholder="Search songs, artists, albums...">
                </form>
				 <div class="user-profile">
                <img src="https://th.bing.com/th/id/R.c3631c652abe1185b1874da24af0b7c7?rik=XBP%2fc%2fsPy7r3HQ&riu=http%3a%2f%2fpluspng.com%2fimg-png%2fpng-user-icon-circled-user-icon-2240.png&ehk=z4ciEVsNoCZtWiFvQQ0k4C3KTQ6wt%2biSysxPKZHGrCc%3d&risl=&pid=ImgRaw&r=0" alt="User" class="user-avatar">
                <span class="username"><?php echo $_SESSION['music']; ?></span>
                <div class="dropdown-menu">
                    <a href="profile.html" class="dropdown-item">Your Profile</a>
                    <a href="settings.html" class="dropdown-item">Settings</a>
                    <div class="dropdown-divider"></div>
                    <a href="logout.php" class="dropdown-item">Log Out</a>
                </div>
            </div>
</header>

<?php
$languages = ['Tamil', 'Kannada', 'Telugu', 'Malayalam', 'Hindi', 'English'];

foreach ($languages as $lang) {
    echo "<h2>$lang Songs</h2><div class='scroll-container' id='scroll-$lang'><div class='gallery'>";
    $query = "SELECT * FROM songs WHERE language = '$lang'";
    $result = mysqli_query($conn, $query);

    while ($row = mysqli_fetch_assoc($result)) {
        $imgPath = "pics/" . strtolower($lang) . "/" . htmlspecialchars($row['song_pic']);
        echo "<div class='scroll-item'>
        <img src='$imgPath' class='image' alt='" . htmlspecialchars($row['song_name']) . "'>
        <h3>" . htmlspecialchars($row['song_name']) . "</h3>";

        
        $songPath = "songs/" . strtolower($lang) . "/" . htmlspecialchars($row['song_url']);
echo "<audio controls class='audio-player' style='margin-top: 10px;'>
        <source src='$songPath' type='audio/mp3'>
        Your browser does not support the audio element.
      </audio>";

        echo "</div>";
    }
	if (!file_exists($songPath)) {
    echo "Audio file not found!";
} else {
    echo "<audio controls class='audio-player' style='margin-top: 10px;'>
          <source src='$songPath' type='audio/mp3'>
          Your browser does not support the audio element.
        </audio>";
}


    echo "</div></div>";
}
?>
	
<script>
document.addEventListener('DOMContentLoaded', function () {
    let currentAudio = null;

    document.querySelectorAll('audio').forEach(audioElement => {
        audioElement.addEventListener('play', function () {
            // Pause the previously playing audio
            if (currentAudio && currentAudio !== this) {
                currentAudio.pause();
                currentAudio.currentTime = 0;
                // Remove highlight from previous
                currentAudio.closest('.scroll-item')?.classList.remove('playing');
            }

            // Highlight the current song
            this.closest('.scroll-item')?.classList.add('playing');
            currentAudio = this;
        });

        audioElement.addEventListener('pause', function () {
            // Remove highlight when paused manually
            if (currentAudio === this) {
                this.closest('.scroll-item')?.classList.remove('playing');
                currentAudio = null;
            }
        });
    });
});
</script>



<script>
// Auto-scroll all scroll containers
document.querySelectorAll('.scroll-container').forEach(container => {
    let scrollSpeed = 1;

    function autoScroll() {
        container.scrollLeft += scrollSpeed;
        if (container.scrollLeft >= container.scrollWidth - container.clientWidth) {
            container.scrollLeft = 0; // loop back
        }
    }
});
</script>

	<script>
document.querySelectorAll('.image').forEach(img => {
    img.addEventListener('click', () => {
        // Remove "selected" from all images
        document.querySelectorAll('.image').forEach(i => i.classList.remove('selected'));
        
        // Add "selected" to the clicked image
        img.classList.add('selected');
    });
});

</script>

		<footer>
        <div class="footer-content">
            <div class="footer-section footer-logo">
                <img src="pics/logos/vibecast.png" alt="Vibe Cast Logo">
                <p>Feel the beat, live the vibe!</p>
            </div>
            <div class="footer-section">
                <h3>Quick Links</h3>
                <ul>
                    <li><a href="after_login.html" style="color: #8e3eff;text-decoration:none;">Home</a></li>
                    <li><a href="aboutus.html" style="color: #8e3eff;text-decoration:none;">About</a></li>
                    <li><a href="playlist.php" style="color: #8e3eff;text-decoration:none;">All Songs</a></li>
                    <li><a href="login.php" style="color: #8e3eff;text-decoration:none;">My Playlist</a></li>
                </ul>
            </div>
            <div class="footer-section">
                <h3>Contact Us</h3>
                <p>Email: support@vibecast.com</p>
                <p>Phone: +1 234 567 890</p>
            </div>
        </div>
        <div class="copyright">
            &copy; 2023 Vibe Cast. All rights reserved.
        </div>
    </footer>

</body>
</html>
