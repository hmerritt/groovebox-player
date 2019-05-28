#!/usr/bin/liquidsoap

world = playlist.safe("/home/radio/playlists/playlist.txt",mode="randomize",reload_mode="watch")
radioworld = crossfade(start_next=8.,fade_in=3.,fade_out=3.,world)

output.icecast(%mp3(bitrate=320),
host="localhost",
port=7400,
password="CHANGEME",
mount="CHANGEME",
name="CHANGEME",
description="CHANGEME",
genre="CHANGEME",
url="http://127.0.0.1",
radioworld)