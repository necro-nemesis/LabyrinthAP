#!/bin/sh

case "$1" in
  start)
        echo -n "Starting Lokinet"
        /home/pi/loki-network/lokinet
        ;;
  stop)
        echo -n "Stopping daemon"
        pkill lokinet
        ;;
  gen)
        echo -n "Initializing"
        /home/pi/loki-network/lokinet "-g"
        cp /root/.lokinet/lokinet.ini /home/pi/loki-network
        ;;

bootstrap)

        echo -n "Bootstrapping"
        /home/pi/lokinet-bootstrap $2
        ;;


  *)
        echo "Usage: "$1" {start|stop|gen|bootstrap}"
        exit 1
        ;;
        esac
