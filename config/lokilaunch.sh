#!/bin/sh

case "$1" in

  start)
        echo -n "Starting LOKINET daemon\n"
        lokinet > /dev/null 2>&1 &
        ;;

  stop)
        echo -n "Stopping LOKINET daemon\n"
        pkill lokinet
        ;;

  gen)
        echo -n "NEW lokinet.ini FILE CREATED\n"
        lokinet "-g"
        cp /root/.lokinet/lokinet.ini /usr/local/bin/
        cat /usr/local/bin/lokinet.ini
        ;;

bootstrap)
        echo -n "LOKINET DICONNECTED AND DAEMON SHUTDOWN FOR BOOTSTRAPPING\n"
        pkill lokinet
        sleep 2
        pidof lokinet >/dev/null && echo "Service is running\n" || echo "Service NOT running\n"
        echo -n "FETCH BOOTSTRAP <---- "
        lokinet-bootstrap "$2"
        echo -n "SUCCESS! BOOTSTRAPPED WTIH ---> $2\n\n"
        echo -n "YOU MUST MANUALLY RESTART LOKINET DAEMON AND RECONNECT FOR SERVICE\n"
        ;;

  *)
        echo "Usage: "$1" {start|stop|gen|bootstrap}"
        exit 1
        ;;
        esac
