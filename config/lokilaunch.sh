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
        echo -n "NEW lokinet.ini FILE CREATED\n"
        cat /root/.lokinet/lokinet.ini
        /home/pi/loki-network/lokinet "-g"
        cp /root/.lokinet/lokinet.ini /home/pi/loki-network
        ;;

bootstrap)
        echo -n "LOKINET DAEMON SHUTDOWN FOR BOOTSTRAPPING\n"
        pkill lokinet
        sleep 2
        pidof lokinet >/dev/null && echo "Service is running\n" || echo "Service NOT running\n"
        echo -n "FETCH BOOTSTRAP <---- "
        /home/pi/loki-network/lokinet-bootstrap "$2"
        echo -n "SUCCESS! BOOTSTRAPPED WTIH ---> $2\n\n"
        cp /home/pi/loki-network/lokinet-bootstrap.exe /root/.lokinet/
        echo -n "MANUALLY RESTART LOKINET DAEMON TO RECONNECT TO SERVICE\n"
        ;;
  *)
        echo "Usage: "$1" {start|stop|gen|bootstrap}"
        exit 1
        ;;
        esac
