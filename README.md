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

1. Copy contents of `www/` (`api/`, `client/` and `tracks/`) to a directory in your web-server - e.g. `groovebox/`


## Usage

### Add Music

All music is added to the `tracks/` folder and sorted into playlists (sub-folders) of your choice - e.g. `tracks/disco/`

1. Create a folder within the `tracks/` folder. Any folder created will act as a **playlist** (you can create as many playlists as you like)

2. Move all the music you want to play into the playlist folder - e.g. `disco/my-awesome-track.mp3`


### Accessing Playlists

1. To start listening to a playlist, add the name of the playlist folder into the URL as a parameter - `https://example.com/groovebox/client/?playlist=disco`