#!/bin/sh

case "$1" in

  start)
        echo -n "Starting LOKINET daemon\n"
        systemctl start lokinet
        #lokinet > /dev/null 2>&1 &
        ;;

  stop)
        echo -n "daemon stop command sent\n"
        systemctl stop lokinet
        #pkill lokinet
        ;;

  gen)
        systemctl stop lokinet
        echo -n "NEW lokinet.ini FILE CREATED\n"
        tmpdir=$(mktemp --tmpdir -d lokinet.XXXXXXXXXX)
        /usr/bin/lokinet -g $tmpdir/lokinet.ini
        sudo sed -i -e "s#$tmpdir#/var/lib/lokinet#" $tmpdir/lokinet.ini
        chmod 640 $tmpdir/lokinet.ini
        chgrp _loki $tmpdir/lokinet.ini
        mv -f $tmpdir/lokinet.ini /var/lib/lokinet/lokinet.ini
        cat /var/lib/lokinet/lokinet.ini
        systemctl start lokinet
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
