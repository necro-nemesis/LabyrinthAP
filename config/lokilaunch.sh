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
        /home/pi/loki-network/lokinet -g
        ;;

  *)
        echo "Usage: "$1" {start|stop|gen}"
        exit 1
        ;;
        esac
