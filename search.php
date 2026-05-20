<?php
session_start();
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "music";

$conn = mysqli_connect($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . mysqli_connect_error());
}

$search = isset($_GET['searchInput']) ? $conn->real_escape_string($_GET['searchInput']) : '';
?>

<!DOCTYPE html>
<html>
<head>
    <title>Search Results - Vibe Cast</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0&icon_names=add" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200">
    <link rel="stylesheet" href="login.css">
    <style>
        .song-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 20px;
            padding: 20px;
            margin-top: 20px;
			position:inherit;
        }
        
        .song-card {
            background: #282828;
            border-radius: 8px;
            padding: 15px;
            transition: all 0.3s ease;
            cursor: pointer;
            position: relative;
            overflow: hidden;
        }
        
        .song-card:hover {
            background: #383838;
            transform: translateY(-5px);
        }
        
        .song-cover {
            width: 100%;
            aspect-ratio: 1;
            object-fit: cover;
            border-radius: 4px;
            margin-bottom: 12px;
        }
        
        .song-title {
            font-size: 16px;
            font-weight: 600;
            margin-bottom: 4px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        
        .song-artist {
            color: #b3b3b3;
            font-size: 14px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        
        .play-btn {
            position: absolute;
            bottom: 15px;
            right: 15px;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: #1db954;
            color: white;
            border: none;
            display: flex;
            align-items: center;
            justify-content: center;
            opacity: 0;
            transform: translateY(10px);
            transition: all 0.3s ease;
            cursor: pointer;
        }
        
        .song-card:hover .play-btn {
            opacity: 1;
            transform: translateY(0);
        }
        
        .search-header {
            padding: 20px;
            border-bottom: 1px solid #333;
        }
        
        .search-header h2 {
            margin: 0;
            font-size: 24px;
        }
        
        .no-results {
            padding: 40px;
            text-align: center;
            color: #b3b3b3;
        }
		
		.now-playing {
            position: fixed;
            bottom: 0;
            width: 100%;
            background: #111;
            color: white;
            padding: 10px 20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .now-playing img {
            width: 50px;
            height: 50px;
            object-fit: cover;
            margin-right: 10px;
        }

        .now-playing-info {
            display: flex;
            align-items: center;
        }
		
    </style>
</head>
<body>
    <script src="app.js" defer></script>
    <header>
        <button id="openBtn">☰</button>
        <div class="logo" onclick="window.location.href='home.html'">
            <img src="pics/logos/vibecast.png" alt="Vibe Cast Logo">
            <h3>VIBE CAST</h3>
        </div>
        <nav class="nav">
            <a href="after_login.php">Home</a>
            <a href="playlist.php">All Songs</a>
            <a href="myplaylist.php">My Playlist</a>
            <div class="search-container">
                <span class="search-icon material-symbols-outlined" id="searchIcon">search</span>
                <input type="text" class="search-box" id="searchInput" placeholder="Search songs, artists, albums..." 
                       value="<?php echo htmlspecialchars($search); ?>">
            </div>
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
        </nav>
    </header>

    <div class="main">
        <aside class="sidebar" id="sidebar">
            <div class="sidebar-header">
                <h2>Your Library</h2>
                <button id="close-playlist">✖</button>
            </div>
            <div class="playlist">
                <button class="create-btn" id="createPlaylistBtn">
                    <span class="material-symbols-outlined">add</span>
                    Create playlist
                </button>
                <ul class="playlist-menu">
                    <li>Tamil Vibes</li>
                    <li>Kannada Mix</li>
                    <li>Telugu Beats</li>
                    <li>Malayalam</li>
                    <li>Hindi Hits</li>
                    <li>English Pops</li>
                </ul>
            </div>
        </aside>

        <section class="content">
            <div class="search-header">
                <h2>Search results for: "<?php echo htmlspecialchars($search); ?>"</h2>
            </div>
            
            <div class="song-grid">
                <?php
                $sql = "SELECT * FROM songs 
                        WHERE song_name LIKE '%$search%' 
                        OR song_artist LIKE '%$search%'
                        LIMIT 50";
                $result = mysqli_query($conn,$sql);
                
                if (mysqli_num_rows($result) > 0) {
    while($row = mysqli_fetch_assoc($result)) {
        $songName = htmlspecialchars($row['song_name']);
        $songArtist = htmlspecialchars($row['song_artist']);
        $songPic = htmlspecialchars($row['song_pic']);
        $songAudio = htmlspecialchars($row['song_url']); // assuming this field stores the audio filename

		echo '
                <div class="song-card" onclick="playSong(
                    \'' . addslashes($songName) . '\',
                    \'' . addslashes($songArtist) . '\',
                    \'pics/tamil/' . $songPic . '\',
                    \'songs/tamil/' . $songAudio . '\')">
                    <img src="pics/tamil/' . $songPic . '" class="song-cover" alt="' . $songName . '">
                    <div class="song-title">' . $songName . '</div>
                    <div class="song-artist">' . $songArtist . '</div>
                    <button class="play-btn"><span class="material-symbols-outlined">play_arrow</span></button>
                </div>';
            }
        } else {
            echo '<div class="no-results"><p>No songs found.</p></div>';
        }
        ?>
		</div>		
       
			<!-- Hidden Audio Element -->
    <audio id="audioPlayer" src="" style="display:none;"></audio>

    
 
		<!-- Now Playing Bar -->
    <div class="now-playing" id="nowPlaying">
        <div class="now-playing-info">
            <img src="hg" alt="Now Playing" class="now-playing-cover" id="nowPlayingImg">
            <div class="now-playing-text">
                <div class="now-playing-title" id="nowPlayingTitle">Not Playing</div>
                <div class="now-playing-artist" id="nowPlayingArtist">Select a song to play</div>
            </div>
        </div>
        
        <div class="player-controls">
            <button class="control-btn" id="prevBtn">⏮</button>
            <button class="control-btn play-pause-btn" id="playPauseBtn">▶</button>
            <button class="control-btn" id="nextBtn">⏭</button>
        </div>
        
        <div class="progress-container">
            <span class="time" id="currentTime">0:00</span>
            <div class="progress-bar" id="progressBarContainer">
                <div class="progress" id="progressBar"></div>
            </div>
            <span class="time" id="duration">0:00</span>
        </div>
    </div>

    <footer>
        <div class="footer-content">
            <div class="footer-section footer-logo">
                <img src="pics/logos/vibecast.png" alt="Vibe Cast Logo">
                <p>Feel the beat, live the vibe!</p>
            </div>
            <div class="footer-section">
                <h3>Quick Links</h3>
                <ul>
                    <li><a href="#" style="color: #8e3eff;text-decoration:none;">Home</a></li>
                    <li><a href="aboutus.html" style="color: #8e3eff;text-decoration:none;">About</a></li>
                    <li><a href="playlist.php" style="color: #8e3eff;text-decoration:none;">All Songs</a></li>
                    <li><a href="myplaylist.php" style="color: #8e3eff;text-decoration:none;">My Playlist</a></li>
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

    <script>
        // Play song function
        function playSong(title, artist, cover, audiourl) {
            document.getElementById('nowPlayingTitle').textContent = title;
            document.getElementById('nowPlayingArtist').textContent = artist;
            document.getElementById('nowPlayingImg').src = cover;
            document.getElementById('playPauseBtn').textContent = '⏸';
            const audioPlayer = document.getElementById('audioPlayer');
    audioPlayer.src = audioUrl;
    audioPlayer.play();

    document.getElementById('nowPlaying').scrollIntoView({ behavior: 'smooth' });
			
            // you would start audio playback here
            console.log('Now playing:', title, 'by', artist);
            
            
            document.getElementById('nowPlaying').scrollIntoView({ behavior: 'smooth' });
        }
        
        // Search functionality
        document.getElementById('searchInput').addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                const query = this.value.trim();
                if (query) {
                    window.location.href = 'search.php?searchInput=' + encodeURIComponent(query);
                }
            }
        });
        
        // Search icon click
        document.getElementById('searchIcon').addEventListener('click', function() {
            const query = document.getElementById('searchInput').value.trim();
            if (query) {
                window.location.href = 'search.php?searchInput=' + encodeURIComponent(query);
            }
        });
        
        // Sidebar toggle
        document.getElementById('openBtn').addEventListener('click', function() {
            document.getElementById('sidebar').classList.add('open');
        });
        
        document.getElementById('close-playlist').addEventListener('click', function() {
            document.getElementById('sidebar').classList.remove('open');
        });

        const audioPlayer = document.getElementById('audioPlayer');
        const nowPlayingTitle = document.getElementById('nowPlayingTitle');
        const nowPlayingArtist = document.getElementById('nowPlayingArtist');
        const nowPlayingImg = document.getElementById('nowPlayingImg');
        const playPauseBtn = document.getElementById('playPauseBtn');

        function playSong(title, artist, cover, audioUrl) {
            nowPlayingTitle.textContent = title;
            nowPlayingArtist.textContent = artist;
            nowPlayingImg.src = cover;

            audioPlayer.src = audioUrl;
            audioPlayer.play().then(() => {
                playPauseBtn.textContent = '⏸';
                console.log("Playing:", audioUrl);
            }).catch(err => {
                console.error("Error playing:", err);
            });
        }

        function togglePlayPause() {
            if (audioPlayer.paused) {
                audioPlayer.play();
                playPauseBtn.textContent = '⏸';
            } else {
                audioPlayer.pause();
                playPauseBtn.textContent = '▶';
            }
        }
    </script>
	
<script>
    // Elements
    const audioPlayer = document.getElementById('audioPlayer');
    const nowPlayingTitle = document.getElementById('nowPlayingTitle');
    const nowPlayingArtist = document.getElementById('nowPlayingArtist');
    const nowPlayingImg = document.getElementById('nowPlayingImg');
    const playPauseBtn = document.getElementById('playPauseBtn');
    const prevBtn = document.getElementById('prevBtn');
    const nextBtn = document.getElementById('nextBtn');
    const progressBar = document.getElementById('progressBar');
    const progressBarContainer = document.getElementById('progressBarContainer');
    const currentTimeElement = document.getElementById('currentTime');
    const durationElement = document.getElementById('duration');

    // Array of songs for demo purposes
    const songs = [
        { title: "Song 1", artist: "Artist 1", cover: "path/to/song1.jpg", audioUrl: "path/to/song1.mp3" },
        { title: "Song 2", artist: "Artist 2", cover: "path/to/song2.jpg", audioUrl: "path/to/song2.mp3" },
        // Add more songs as needed
    ];

    let currentSongIndex = 0;

    // Function to play the song
    function playSong(song) {
        nowPlayingTitle.textContent = song.title;
        nowPlayingArtist.textContent = song.artist;
        nowPlayingImg.src = song.cover;

        audioPlayer.src = song.audioUrl;
        audioPlayer.play().then(() => {
            playPauseBtn.textContent = '⏸'; // Change play button to pause
            updateDuration();
        }).catch((err) => {
            console.error('Error playing the song:', err);
        });
    }

    // Play/pause toggle
    playPauseBtn.addEventListener('click', () => {
        if (audioPlayer.paused) {
            audioPlayer.play();
            playPauseBtn.textContent = '⏸'; // Pause icon
        } else {
            audioPlayer.pause();
            playPauseBtn.textContent = '▶'; // Play icon
        }
    });

    // Skip to previous song
    prevBtn.addEventListener('click', () => {
        currentSongIndex = (currentSongIndex - 1 + songs.length) % songs.length;
        playSong(songs[currentSongIndex]);
    });

    // Skip to next song
    nextBtn.addEventListener('click', () => {
        currentSongIndex = (currentSongIndex + 1) % songs.length;
        playSong(songs[currentSongIndex]);
    });

    // Update progress bar and time
    audioPlayer.addEventListener('timeupdate', () => {
        const currentTime = audioPlayer.currentTime;
        const duration = audioPlayer.duration;

        if (!isNaN(duration)) {
            const progress = (currentTime / duration) * 100;
            progressBar.style.width = progress + '%';
            currentTimeElement.textContent = formatTime(currentTime);
            durationElement.textContent = formatTime(duration);
        }
    });

    // Format time in mm:ss
    function formatTime(seconds) {
        const mins = Math.floor(seconds / 60);
        const secs = Math.floor(seconds % 60);
        return `${mins}:${secs < 10 ? '0' + secs : secs}`;
    }

    // Function to update song duration after the song is loaded
    function updateDuration() {
        const duration = audioPlayer.duration;
        if (!isNaN(duration)) {
            durationElement.textContent = formatTime(duration);
        }
    }

    // Initially load and play the first song
    playSong(songs[currentSongIndex]);
</script>

		
</body>
</html>

<?php
mysqli_close($conn);
?>