# Groovebox Music Player
A self-hosted web-based music player - just drag, drop and listen 🎵


![Gif of radio client](template/example.gif)


## Features

- Add an infinite amount of playlists
- Tracks are live as soon as they hit the playlist folder
- Powered by PHP - **no** continuous program running on a port in the background


## How To Install

### Prerequisites

- Functioning web-server
- PHP7


### Installation Steps

1. Open `www/login.php`

2. Edit the line 7 and 8 (9 is optionnal) to personalize your user login and password (and eventually the default playlist you want to start with)

3. Save the file

4. Copy contents of `www/` (`api/`, `client/` and `tracks/`) to a directory in your web-server - e.g. `groovebox/`


## Usage

### Add Music

All music is added to the `tracks/` folder and sorted into playlists (sub-folders) of your choice - e.g. `tracks/disco/`

1. Create a folder within the `tracks/` folder. Any folder created will act as a **playlist** (you can create as many playlists as you like)

2. Move all the music you want to play into the playlist folder - e.g. `disco/my-awesome-track.mp3`


### Accessing Playlists

1. First of all, login to the app with your credential

2. To start listening to a playlist, add the name of the playlist folder into the URL as a parameter - `https://example.com/groovebox/pl/disco` or click on the hamburger menu to select the playlist you want listening

### Change the music you are listening

1. You can go to a next song pressing `right-arrow key`

2. You can go to the previous song pressing `let-arrow key`. If it is the first time you play the list, a random file is choosen.

### Change the volume

1. Press the `up-arrow key` or `plus key` to pop up the volume

2. Press the `down-arrow key` or `minus key` to pop down the volume