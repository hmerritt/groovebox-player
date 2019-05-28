# Internet Radio
A self-hosted internet radio solution using `Icecast` and `liquidsoap` which is accessed from a front-end `UI`.


## How To Install

### Prerequisites

- [Docker](https://www.docker.com)
- Functioning web-server


### Installation Steps

#### Icecast
1. Install icecast: `docker pull moul/icecast`
2. Create custom `icecast.xml` to fit your needs
3. Run the dockerized icecast: `docker run -p 7400:7400 -v /home/radio/icecast.xml:/etc/icecast2/icecast.xml moul/icecast`
4. (optional) Set-up a `proxy-pass` in NGINX for a cleaner URL

#### Liquidsoap
1. Install iquidsoap: `sudo apt-get install liquidsoap`
2. Create custom `playlist.liq` to fit your needs
3. Run liquidsoap in a screened terminal: `liquidsoap /etc/liquidsoap/playlist.liq`

#### Web UI
1. Copy `www` files into a web-server