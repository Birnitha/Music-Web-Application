// DOM Elements
const openBtn = document.getElementById('openBtn');
const closeBtn = document.getElementById('close-playlist');
const sidebar = document.getElementById('sidebar');
const createPlaylistBtn = document.getElementById('createPlaylistBtn');
const playlistMenu = document.querySelector('.playlist-menu');
const songCards = document.querySelectorAll('.song-card');
const recentItems = document.querySelectorAll('.recent-item');
const playPauseBtn = document.getElementById('playPauseBtn');
const prevBtn = document.getElementById('prevBtn');
const nextBtn = document.getElementById('nextBtn');
const progressBar = document.getElementById('progressBar');
const progressBarContainer = document.getElementById('progressBarContainer');
const nowPlayingTitle = document.getElementById('nowPlayingTitle');
const nowPlayingArtist = document.getElementById('nowPlayingArtist');
const nowPlayingImg = document.getElementById('nowPlayingImg');
const currentTimeDisplay = document.getElementById('currentTime');
const durationDisplay = document.getElementById('duration');
const playlistModal = document.getElementById('playlistModal');
const playlistNameInput = document.getElementById('playlistNameInput');
const createPlaylistConfirm = document.getElementById('createPlaylistConfirm');
const cancelPlaylistCreation = document.getElementById('cancelPlaylistCreation');

// Audio Player
const audio = new Audio();
let isPlaying = false;
let currentSong = null;
let progressInterval;
let currentPlaylist = [];
let currentSongIndex = 0;

// Sample Playlists
let playlists = {
    'Tamil Vibes': [
        {
            title: 'Vazhithunayee',
            artist: 'Leon James',
            cover: 'pics/tamil/vazhithunayee.webp',
            audioSrc: 'songs/tamil/vazhithunaye.mp3'
        },
        {
            title: 'Marali manasagide',
            artist: 'B Ajaneesh Loknath',
            cover: 'pics/kannada/marali_manasagide.jpg',
            audioSrc: 'songs/kannada/marali_manasagide.mp3'
        }
    ],
    'Kannada Mix': [
        {
            title: 'Bujji thalli',
            artist: 'Devi Sri Prasad',
            cover: 'pics/telugu/bujji_thalli.jpg',
            audioSrc: 'songs/telugu/bujji_thalli.mp3'
        },
		{
            title: 'Heeriye',
            artist: 'Jasleen Royal',
            cover: 'pics/hindi/heeriye.jpg',
            audioSrc: 'songs/hindi/heeriye.mp3'
        },
    ],
    'Telugu Beats': [],
    'Malayalam': [],
    'Hindi Hits': [],
    'English Pops': []
};

// Initialize App
function init() {
    setupEventListeners();
    renderPlaylists();
    updatePlayerDisplay();

    // Simulate Recently Played
    recentItems.forEach(item => {
        const title = item.querySelector('.recent-title').textContent;
        const artist = item.querySelector('.recent-artist').textContent;
        const cover = item.querySelector('.recent-cover').src;

        if (!playlists['Recently Played']) {
            playlists['Recently Played'] = [];
        }

        if (!playlists['Recently Played'].some(song => song.title === title)) {
            playlists['Recently Played'].push({
                title,
                artist,
                cover,
                audioSrc: `music/${title.toLowerCase().replace(/\s+/g, '-')}.mp3`
            });
        }
    });
}

// Event Listeners
function setupEventListeners() {
    openBtn.addEventListener('click', toggleSidebar);
    closeBtn.addEventListener('click', toggleSidebar);

    createPlaylistBtn.addEventListener('click', showPlaylistModal);
    createPlaylistConfirm.addEventListener('click', createPlaylist);
    cancelPlaylistCreation.addEventListener('click', hidePlaylistModal);

    songCards.forEach(card => {
        const playBtn = card.querySelector('.play-btn');

        card.addEventListener('click', () => playSongFromCard(card));
        playBtn.addEventListener('click', (e) => {
            e.stopPropagation();
            playSongFromCard(card);
        });
    });

    recentItems.forEach(item => {
        item.addEventListener('click', function () {
            const title = this.querySelector('.recent-title').textContent;
            const artist = this.querySelector('.recent-artist').textContent;
            const cover = this.querySelector('.recent-cover').src;
            playSong(title, artist, cover);
        });
    });

    playPauseBtn.addEventListener('click', togglePlayPause);
    prevBtn.addEventListener('click', playPreviousSong);
    nextBtn.addEventListener('click', playNextSong);
    progressBarContainer.addEventListener('click', seekAudio);

    audio.addEventListener('timeupdate', updateProgress);
    audio.addEventListener('ended', playNextSong);
    audio.addEventListener('play', () => {
        isPlaying = true;
        updatePlayerDisplay();
    });
    audio.addEventListener('pause', () => {
        isPlaying = false;
        updatePlayerDisplay();
    });
}

// Sidebar Toggle
function toggleSidebar() {
    sidebar.classList.toggle('open');
    document.body.classList.toggle('sidebar-open');
}

// Playlist UI
function renderPlaylists() {
    playlistMenu.innerHTML = '';
    Object.keys(playlists).forEach(name => {
        const li = document.createElement('li');
        li.textContent = name;
        li.addEventListener('click', () => loadPlaylist(name));
        playlistMenu.appendChild(li);
    });
}

function showPlaylistModal() {
    playlistModal.style.display = 'flex';
    playlistNameInput.focus();
}

function hidePlaylistModal() {
    playlistModal.style.display = 'none';
    playlistNameInput.value = '';
}

function createPlaylist() {
    const name = playlistNameInput.value.trim();
    if (!name) return;

    if (!playlists[name]) {
        playlists[name] = [];
        renderPlaylists();
        hidePlaylistModal();
    } else {
        alert('Playlist with this name already exists!');
    }
}

function loadPlaylist(name) {
    currentPlaylist = playlists[name];
    currentSongIndex = 0;
    console.log(`Loaded playlist: ${name} (${currentPlaylist.length} songs)`);
}

// Song Playback
function playSongFromCard(card) {
    const title = card.querySelector('.song-title').textContent;
    const artist = card.querySelector('.song-artist').textContent;
    const cover = card.querySelector('.song-cover').src;
    playSong(title, artist, cover);
}

function playSong(title, artist, cover) {
    let song = null;

    for (const [playlistName, playlist] of Object.entries(playlists)) {
        const index = playlist.findIndex(s => s.title === title && s.artist === artist);
        if (index !== -1) {
            song = playlist[index];
            currentPlaylist = playlist;
            currentSongIndex = index;
            break;
        }
    }

    // If not found in known playlists
    if (!song) {
        song = {
            title,
            artist,
            cover,
            audioSrc: `music/${title.toLowerCase().replace(/\s+/g, '-')}.mp3`
        };
        currentPlaylist = [song];
        currentSongIndex = 0;
    }

    currentSong = song;
    audio.src = song.audioSrc;
    audio.play().catch(err => {
        console.error('Playback error:', err);
        alert('Error playing song. Please check file path.');
    });
}

function togglePlayPause() {
    if (!currentSong) {
        alert('Please select a song first');
        return;
    }

    isPlaying ? audio.pause() : audio.play();
}

function playNextSong() {
    if (currentPlaylist.length === 0) return;

    currentSongIndex = (currentSongIndex + 1) % currentPlaylist.length;
    const nextSong = currentPlaylist[currentSongIndex];
    playSong(nextSong.title, nextSong.artist, nextSong.cover);
}

function playPreviousSong() {
    if (currentPlaylist.length === 0) return;

    currentSongIndex = (currentSongIndex - 1 + currentPlaylist.length) % currentPlaylist.length;
    const prevSong = currentPlaylist[currentSongIndex];
    playSong(prevSong.title, prevSong.artist, prevSong.cover);
}

function seekAudio(e) {
    if (!currentSong || isNaN(audio.duration)) return;
    const percent = e.offsetX / progressBarContainer.offsetWidth;
    audio.currentTime = percent * audio.duration;
}

function updateProgress() {
    if (!currentSong || isNaN(audio.duration)) return;

    const percent = (audio.currentTime / audio.duration) * 100;
    progressBar.style.width = `${percent}%`;

    currentTimeDisplay.textContent = formatTime(audio.currentTime);
    durationDisplay.textContent = formatTime(audio.duration);
}

function formatTime(seconds) {
    const mins = Math.floor(seconds / 60);
    const secs = Math.floor(seconds % 60);
    return `${mins}:${secs < 10 ? '0' : ''}${secs}`;
}

function updatePlayerDisplay() {
    if (currentSong) {
        nowPlayingTitle.textContent = currentSong.title;
        nowPlayingArtist.textContent = currentSong.artist;
        nowPlayingImg.src = currentSong.cover;
        playPauseBtn.textContent = isPlaying ? '⏸' : '▶';
    } else {
        nowPlayingTitle.textContent = 'Not Playing';
        nowPlayingArtist.textContent = 'Select a song to play';
        nowPlayingImg.src = 'https://source.unsplash.com/random/100x100/?music';
        playPauseBtn.textContent = '▶';
    }
}

// Modal Click-Outside Close
window.addEventListener('click', (e) => {
    if (e.target === playlistModal) {
        hidePlaylistModal();
    }
});

// Initialize
document.addEventListener('DOMContentLoaded', init);

// 🔍 Optional: Enable this if you're using the search bar
/*
document.addEventListener('DOMContentLoaded', function () {
    const searchInput = document.getElementById('searchInput');
    const searchIcon = document.getElementById('searchIcon');

    function performSearch() {
        const query = searchInput.value.trim();
        if (!query) {
            searchInput.focus();
            return;
        }

        const encoded = encodeURIComponent(query);
        window.location.href = `search.php?query=${encoded}`;
    }

    searchInput.addEventListener('keypress', function (e) {
        if (e.key === 'Enter') performSearch();
    });

    if (searchIcon) {
        searchIcon.addEventListener('click', performSearch);
    }
});
*/



