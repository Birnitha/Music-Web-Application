<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Vibe Cast - Welcome Back</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0&icon_names=add" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200">
    <link rel="stylesheet" href="login.css">
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
            <a href="#" class="active">Home</a>
            <a href="playlist.php">All Songs</a>
            <a href="myplaylist.php">My Playlist</a>
            <div class="search-container">
                <span class="search-icon material-symbols-outlined" id="searchIcon">search</span>
                <form method="get" action="search.php">
                <input type="text" name="searchInput" class="search-box" id="searchInput" placeholder="Search songs, artists, albums...">
                </form>
            </div>
            <div class="user-profile">
                <img src="https://th.bing.com/th/id/R.c3631c652abe1185b1874da24af0b7c7?rik=XBP%2fc%2fsPy7r3HQ&riu=http%3a%2f%2fpluspng.com%2fimg-png%2fpng-user-icon-circled-user-icon-2240.png&ehk=z4ciEVsNoCZtWiFvQQ0k4C3KTQ6wt%2biSysxPKZHGrCc%3d&risl=&pid=ImgRaw&r=0" alt="User" class="user-avatar">
                <span class="username"><?php echo $_SESSION['music']; ?></span>
                <div class="dropdown-menu">
                    <a href="profile.html" class="dropdown-item">Your Profile</a>
                    <a href="settings.html" class="dropdown-item">Settings</a>
                    <div class="dropdown-divider"></div>
                    <a href="logout.html" class="dropdown-item">Log Out</a>
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
            <!-- Add this modal at the bottom for playlist input -->
        <div class="playlist-modal" id="playlistModal">
            <div class="playlist-modal-content">
                <h3>Create New Playlist</h3>
                <input type="text" class="playlist-input" id="playlistNameInput" placeholder="Playlist name">
                <div class="playlist-modal-buttons">
                    <button class="playlist-modal-btn cancel" id="cancelPlaylistCreation">Cancel</button>
                    <button class="playlist-modal-btn create" id="createPlaylistConfirm">Create</button>
                </div>
            </div>
        </div>

        <section class="content">
            <!-- Welcome Back Section -->
            <div class="featured-section">
                <div class="section-header">
                    <h2>Welcome back, <?php echo $_SESSION['music']; ?></h2>
                </div>
				<!-- Featured Songs -->
            <div class="featured-section">
                <div class="section-header">
                    <h2>Featured Songs</h2>
                    <div class="view-all">
						<a href="playlist.php">View All</a>
					</div>
                </div>
                <div class="song-grid">
                    <!-- Song 1 -->
                    <div class="song-card slide-up" >
    <img src="pics/tamil/vazhithunayee.webp" alt="Song Cover" class="song-cover">
    <div class="song-info">
        <div class="song-title">Vazhithunayee</div>
        <div class="song-artist">Leon James</div>
        <button class="play-btn">▶</button>
    </div>
</div> 
                    <!-- Song 2 -->
                    <div class="song-card slide-up" >
                        <img src="pics/kannada/marali_manasagide.jpg" alt="Song Cover" class="song-cover">
                        <div class="song-info">
                            <div class="song-title">Marali manasagide</div>
                            <div class="song-artist">B Ajaneesh Loknath</div>
                            <button class="play-btn">▶</button>
                        </div>
                    </div>
                    
                    <!-- Song 3 -->
                    <div class="song-card slide-up" >
                        <img src="pics/telugu/bujji_thalli.jpg" alt="Song Cover" class="song-cover">
                        <div class="song-info">
                            <div class="song-title">Bujji thalli</div>
                            <div class="song-artist">Devi Sri Prasad</div>
                            <button class="play-btn">▶</button>
                        </div>
                    </div>
                    
                    <!-- Song 4 -->
                    <div class="song-card slide-up" >
                        <img src="pics/hindi/heeriye.jpg" alt="Song Cover" class="song-cover">
                        <div class="song-info">
                            <div class="song-title">Heeriye</div>
                            <div class="song-artist">Jasleen Royal</div>
                            <button class="play-btn">▶</button>
                        </div>
                    </div>
                </div>
            </div>
                

            <!-- Recently Played -->
            <div class="featured-section">
                <div class="section-header">
                    <h2>Recently Played</h2>
                    <a href="playlist.php" class="view-all">View All</a>
                </div>
                <div class="song-grid">
                    <div class="song-card slide-up" onclick="playSong('Midnight Drive', 'Neon Dreams', 'https://source.unsplash.com/random/300x300/?music,cover2')">
                        <img src="pics/telugu/kissik.jpg" alt="Song Cover" class="recent-cover">
                        <div class="song-info">
                            <div class="recent-title">Kissik</div>
                            <div class="recent-artist">Devi Sri Prasad</div>
							<button class="play-btn">▶</button>
                        </div>
                    </div>
                    <div class="song-card slide-up" onclick="playSong('Ocean Breeze', 'Wave Theory', 'https://source.unsplash.com/random/300x300/?music,cover3')">
                        <img src="pics/hindi/zara_zara.jpg" alt="Song Cover" class="recent-cover">
                        <div class="song-info">
                            <div class="recent-title">Zara Zara</div>
                            <div class="recent-artist">Harris Jayaraj</div>
							<button class="play-btn">▶</button>
                        </div>
                    </div>
                    <div class="song-card slide-up" onclick="playSong('Electric Love', 'Voltage', 'https://source.unsplash.com/random/300x300/?music,cover4')">
                        <img src="pics/English/die_with_smile.jpeg" alt="Song Cover" class="recent-cover">
                        <div class="song-info">
                            <div class="recent-title">Die With Smile</div>
                            <div class="recent-artist">Lady Gaga</div>
							<button class="play-btn">▶</button>
                        </div>
                    </div>
                    <div class="song-card slide-up" onclick="playSong('Starlight', 'Cosmic Sound', 'https://source.unsplash.com/random/300x300/?music,cover5')">
                        <img src="pics/kannada/kotigobba.jpg" alt="Song Cover" class="recent-cover">
                        <div class="song-info">
                            <div class="recent-title">Kotigobba</div>
                            <div class="recent-artist">D Imaan</div>
							<button class="play-btn">▶</button>
                        </div>
                    </div>
                    <div class="song-card slide-up" onclick="playSong('Urban Jungle', 'Metro Beats', 'https://source.unsplash.com/random/300x300/?music,cover6')">
                        <img src="pics/telugu/chuttamale.jpg" alt="Song Cover" class="recent-cover">
                        <div class="song-info">
                            <div class="recent-title">Chuttamale</div>
                            <div class="recent-artist">Anirudh Ravichander</div>
							<button class="play-btn">▶</button>
                        </div>
                    </div>
					</div
                </div>
            </div>

            <!-- Your Playlists -->
            <div class="featured-section">
                <div class="section-header">
                    <h2>Languages</h2>
                    <a href="playlist.php" class="view-all">View All</a>
                </div>
                <div class="playlist-grid">
                    <div class="playlist-card" onclick="window.location.href='playlist.php'">
                        <img src="pics/tamil/sawadeeka.webp" alt="Playlist Cover" class="playlist-cover">
                        <div class="playlist-info">
                            <div class="playlist-name">Tamil Vibes</div>
                            <div class="playlist-count">10 songs</div>
                        </div>
                    </div>
                    <div class="playlist-card" onclick="window.location.href='playlist.php'">
                        <img src="pics/kannada/appu_dance.jpg" alt="Playlist Cover" class="playlist-cover">
                        <div class="playlist-info">
                            <div class="playlist-name">Kannada Mix</div>
                            <div class="playlist-count">11 songs</div>
                        </div>
                    </div>
                    <div class="playlist-card" onclick="window.location.href='playlist.php'">
                        <img src="pics/telugu/pushpa.jpg" alt="Playlist Cover" class="playlist-cover">
                        <div class="playlist-info">
                            <div class="playlist-name">Telugu Beats</div>
                            <div class="playlist-count">11 songs</div>
                        </div>
                    </div>
                    <div class="playlist-card" onclick="window.location.href='playlist.php'">
                        <img src="pics/malayalam/vatteppam.jpg" alt="Playlist Cover" class="playlist-cover">
                        <div class="playlist-info">
                            <div class="playlist-name">Malayalam</div>
                            <div class="playlist-count">7 songs</div>
                        </div>
                    </div>
					<div class="playlist-card" onclick="window.location.href='playlist.php'">
                        <img src="pics/hindi/sun_sathiya.jpg" alt="Playlist Cover" class="playlist-cover">
                        <div class="playlist-info">
                            <div class="playlist-name">Hindi Hits</div>
                            <div class="playlist-count">7 songs</div>
                        </div>
                    </div>
					<div class="playlist-card" onclick="window.location.href='playlist.php'">
                        <img src="pics/English/believer.jpg" alt="Playlist Cover" class="playlist-cover">
                        <div class="playlist-info">
                            <div class="playlist-name">English Pops</div>
                            <div class="playlist-count">10 songs</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recommended For You -->
            <div class="featured-section">
                <div class="section-header">
                    <h2>Recommended For You</h2>
                </div>
                <div class="song-grid">
                    <div class="song-card" onclick="playSong('Neon Dreams', 'Midnight City', 'https://source.unsplash.com/random/300x300/?music,cover9')">
                        <img src="pics/hindi/chaleya.jpg" alt="Song Cover" class="song-cover">
                        <div class="song-info">
                            <div class="song-title">Chaleya</div>
                            <div class="song-artist">Anirudh Ravichander</div>
                            <button class="play-btn">▶</button>
                        </div>
                    </div>
                    <div class="song-card" onclick="playSong('Solar Flare', 'Cosmic Sound', 'https://source.unsplash.com/random/300x300/?music,cover10')">
                        <img src="pics/telugu/peelings.jpg" alt="Song Cover" class="song-cover">
                        <div class="song-info">
                            <div class="song-title">Peelings</div>
                            <div class="song-artist">Devi Sri Prasad</div>
                            <button class="play-btn">▶</button>
                        </div>
                    </div>
                    <div class="song-card" onclick="playSong('Digital Love', 'Voltage', 'https://source.unsplash.com/random/300x300/?music,cover11')">
                        <img src="pics/kannada/party_freak.jpg" alt="Song Cover" class="song-cover">
                        <div class="song-info">
                            <div class="song-title">Party Freak</div>
                            <div class="song-artist">Chandan Shetty</div>
                            <button class="play-btn">▶</button>
                        </div>
                    </div>
                    <div class="song-card" onclick="playSong('Midnight Sun', 'Aurora Lights', 'https://source.unsplash.com/random/300x300/?music,cover12')">
                        <img src="pics/tamil/po_nee_po.jpg" alt="Song Cover" class="song-cover">
                        <div class="song-info">
                            <div class="song-title">Po Nee Po</div>
                            <div class="song-artist">Anirudh Ravichander</div>
                            <button class="play-btn">▶</button>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

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
        // Sidebar toggle
            document.getElementById('openBtn').addEventListener('click', function() {
                const sidebar = document.getElementById('sidebar');
                sidebar.style.transform = 'translateX(0)';
            });

            document.getElementById('close-playlist').addEventListener('click', function() {
                const sidebar = document.getElementById('sidebar');
                sidebar.style.transform = 'translateX(-100%)';
            });
    </script>
</body>
</html>