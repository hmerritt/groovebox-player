# Internet Radio
A self-hosted internet radio solution using `Icecast` and `liquidsoap` which is accessed from a front-end `UI`.


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

#### Liquidsoap
1. Install iquidsoap: `sudo apt-get install liquidsoap`
2. Edit `template/playlist.liq` to fit your needs
3. Run liquidsoap in a screened terminal: `liquidsoap template/playlist.liq`

#### Web UI
1. Copy `www` files into a web-server