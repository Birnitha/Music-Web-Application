<?php
// Connect to database
$conn = mysqli_connect("localhost", "root", "", "music1");
if (!$conn) {
    die("Cannot connect to database");
}

// Start session
session_start();

// Get page to show
$page = isset($_GET['page']) ? $_GET['page'] : 'dashboard';

// Add new song
if (isset($_POST['add_song'])) {
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $artist = mysqli_real_escape_string($conn, $_POST['artist']);
    $duration = mysqli_real_escape_string($conn, $_POST['duration']);
    $playlist_id = (int)$_POST['playlist_id'];

    mysqli_query($conn, "INSERT INTO songs (title, artist, duration, playlist_id) 
                        VALUES ('$title', '$artist', '$duration', $playlist_id)");
    $_SESSION['message'] = "Song added!";
    header("Location: ?page=dashboard");
    exit();
}

// Add new playlist
if (isset($_POST['add_playlist'])) {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    mysqli_query($conn, "INSERT INTO playlists (name) VALUES ('$name')");
    $_SESSION['message'] = "Playlist created!";
    header("Location: ?page=manage_playlists");
    exit();
}

// Delete song
if (isset($_GET['delete_song'])) {
    $id = (int)$_GET['delete_song'];
    mysqli_query($conn, "DELETE FROM songs WHERE id=$id");
    $_SESSION['message'] = "Song deleted!";
    header("Location: ?page=view_songs");
    exit();
}

// Delete playlist
if (isset($_GET['delete_playlist'])) {
    $id = (int)$_GET['delete_playlist'];
    mysqli_query($conn, "DELETE FROM playlists WHERE id=$id");
    $_SESSION['message'] = "Playlist deleted!";
    header("Location: ?page=manage_playlists");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Music Manager</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #181818;
            color: #e0e0e0;
            display: flex;
        }

        .menu {
            width: 200px;
            background: linear-gradient(to right, #FF1493, #8B008B);
            padding-top: 30px;
            height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .menu a {
            display: block;
            color: #fff;
            text-decoration: none;
            margin: 10px 0;
            font-weight: bold;
            width: 100%;
            text-align: center;
            padding: 12px 0;
            transition: background 0.3s;
        }

        .menu a:hover {
            background: #4b0082;
        }

        .main-content {
            margin-left: 200px;
            padding: 30px;
            flex: 1;
        }

        h1, h2, h3 {
            color: #ffffff;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            background-color: #2b2b2b;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.3);
        }

        th, td {
            padding: 12px 15px;
            border-bottom: 1px solid #444;
            text-align: left;
        }

        th {
            background: #3b3b3b;
            color: #fff;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            display: block;
            margin-bottom: 6px;
            font-weight: bold;
        }

        .form-group input, .form-group select {
            width: 100%;
            padding: 8px 10px;
            background: #222;
            color: #fff;
            border: 1px solid #555;
            border-radius: 6px;
            box-sizing: border-box;
        }

        .btn {
            padding: 8px 16px;
            background: #8e44ad;
            color: white;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            transition: background 0.3s ease;
        }

        .btn:hover {
            background: #732d91;
        }

        .btn-danger {
            background: #c0392b;
            margin-left: 10px;
        }

        .btn-danger:hover {
            background: #992d22;
        }

        p[style*="color:green"] {
            font-weight: bold;
            background: #1f442f;
            padding: 10px;
            border-left: 4px solid limegreen;
            border-radius: 5px;
        }

        /* Header styles */
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            padding: 10px 30px;
        }

        .header .logo img {
            width: 60px;
            height: auto;
        }

        .header h1 {
            margin: 0;
            color: #fff;
            font-size: 2.5em;
        }

        .header .auth-buttons a {
            color: #fff;
            text-decoration: none;
            font-size: 1.1em;
            background-color: #8e44ad;
            padding: 10px 20px;
            border-radius: 5px;
            transition: background 0.3s ease;
        }

        .header .auth-buttons a:hover {
            background-color: #732d91;
        }
    </style>
</head>
<body>
    <div class="menu">
        <a href="?page=dashboard">Dashboard</a>
        <a href="?page=add_song">Add Song</a>
        <a href="?page=view_songs">View Songs</a>
        <a href="?page=manage_playlists">Manage Playlists</a>
    </div>
    <div class="main-content">
        <div class="header">
            <div class="logo">
               
            </div>
            <h1>VIBECAST</h1>

            <!-- Admin Login/Logout aligned right -->
            <div class="auth-buttons">
                <?php if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] == true) { ?>
                    <a href="admin_logout.php">Logout</a>
                <?php } else { ?>
                    <a href="admin_login.php">Login</a>
                <?php } ?>
            </div>
        </div>

        <?php if (isset($_SESSION['message'])) {
            echo "<p style='color:green'>" . $_SESSION['message'] . "</p>";
            unset($_SESSION['message']);
        } ?>

        <?php if ($page == 'dashboard') {
            echo "<h2>Dashboard</h2>";
            $result = mysqli_query($conn, "SELECT COUNT(*) FROM songs");
            $count = mysqli_fetch_row($result)[0];
            echo "<p>Total Songs: $count</p>";

            $result = mysqli_query($conn, "SELECT COUNT(*) FROM playlists");
            $playlist_count = mysqli_fetch_row($result)[0];
            echo "<p>Total Playlists: $playlist_count</p>";

            echo "<h3>Recent Songs</h3>";
            $songs = mysqli_query($conn, "SELECT songs.*, playlists.name as playlist_name FROM songs LEFT JOIN playlists ON songs.playlist_id = playlists.id ORDER BY songs.id DESC LIMIT 5");
            echo "<table><tr><th>Title</th><th>Artist</th><th>Duration</th><th>Playlist</th></tr>";
            while ($song = mysqli_fetch_assoc($songs)) {
                echo "<tr>
                        <td>{$song['title']}</td>
                        <td>{$song['artist']}</td>
                        <td>{$song['duration']}</td>
                        <td>" . ($song['playlist_name'] ?? 'None') . "</td>
                    </tr>";
            }
            echo "</table>";
        } elseif ($page == 'add_song') {
            echo "<h2>Add New Song</h2>";
            echo "<form method='POST'>
                    <div class='form-group'>
                        <label>Title:</label>
                        <input type='text' name='title' required>
                    </div>
                    <div class='form-group'>
                        <label>Artist:</label>
                        <input type='text' name='artist' required>
                    </div>
                    <div class='form-group'>
                        <label>Duration (MM:SS):</label>
                        <input type='text' name='duration' required>
                    </div>
                    <div class='form-group'>
                        <label>Playlist:</label>
                        <select name='playlist_id'>
                            <option value=''>-- Select Playlist --</option>";
            $playlists = mysqli_query($conn, "SELECT * FROM playlists");
            while ($playlist = mysqli_fetch_assoc($playlists)) {
                echo "<option value='{$playlist['id']}'>{$playlist['name']}</option>";
            }
            echo "</select>
                    </div>
                    <button type='submit' name='add_song' class='btn'>Add Song</button>
                </form>";
        } elseif ($page == 'view_songs') {
            echo "<h2>All Songs</h2>";
            $songs = mysqli_query($conn, "SELECT songs.*, playlists.name as playlist_name FROM songs LEFT JOIN playlists ON songs.playlist_id = playlists.id");
            echo "<table>
                    <tr>
                        <th>Title</th>
                        <th>Artist</th>
                        <th>Duration</th>
                        <th>Playlist</th>
                        <th>Action</th>
                    </tr>";
            while ($song = mysqli_fetch_assoc($songs)) {
                echo "<tr>
                        <td>{$song['title']}</td>
                        <td>{$song['artist']}</td>
                        <td>{$song['duration']}</td>
                        <td>" . ($song['playlist_name'] ?? 'None') . "</td>
                        <td><a href='?page=view_songs&delete_song={$song['id']}' onclick='return confirm(\"Delete this song?\")' class='btn-danger'>Delete</a></td>
                    </tr>";
            }
            echo "</table>";
        } elseif ($page == 'manage_playlists') {
            echo "<h2>Manage Playlists</h2>";
            echo "<form method='POST'>
                    <div class='form-group'>
                        <label>New Playlist Name:</label>
                        <input type='text' name='name' required>
                    </div>
                    <button type='submit' name='add_playlist' class='btn'>Create Playlist</button>
                </form>
                <h3>Existing Playlists</h3>";
            $playlists = mysqli_query($conn, "SELECT * FROM playlists");
            if (mysqli_num_rows($playlists) > 0) {
                echo "<table>
                        <tr>
                            <th>Name</th>
                            <th>Action</th>
                        </tr>";
                while ($playlist = mysqli_fetch_assoc($playlists)) {
                    echo "<tr>
                            <td>{$playlist['name']}</td>
                            <td><a href='?page=manage_playlists&delete_playlist={$playlist['id']}' onclick='return confirm(\"Delete this playlist and all its songs?\")' class='btn-danger'>Delete</a></td>
                        </tr>";
                }
                echo "</table>";
            } else {
                echo "<p>No playlists found.</p>";
            }
        }
        ?>
    </div>
</body>
</html>
