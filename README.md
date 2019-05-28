# Internet Radio
A self-hosted internet radio solution using [`moul/icecast`](https://github.com/moul/docker-icecast) and [`liquidsoap`](https://www.liquidsoap.info/) which connects to a front-end `UI` for consumption.


## How To Install

### Prerequisites

- [Docker](https://www.docker.com)
- Functioning web-server


### Installation Steps

#### Icecast
1. Install icecast: `docker pull moul/icecast`
2. Edit `template/icecast.xml` to fit your needs
3. Run the dockerized icecast: `docker run -p 7400:7400 -v template/icecast.xml:/etc/icecast2/icecast.xml moul/icecast`
4. (optional) Add `template/nginx.conf` to existing nginx.conf for a cleaner URL

#### Add Music
1. Copy all music to be streamed to the server
2. Run `ls -d "$PWD"/* > playlist.txt` from within the music folder to generate a playlist file which is used by `liquidsoap`

#### Liquidsoap
1. Install iquidsoap: `sudo apt-get install liquidsoap`
2. Edit `template/playlist.liq` to fit your needs
3. Run liquidsoap in a screened terminal: `liquidsoap template/playlist.liq`

#### Web UI
1. Copy `www` files onto a web-server