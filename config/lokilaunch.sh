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
        echo -n "Lokinet daemon shutdown for bootstrapping\n"
        systemctl stop lokinet
        pidof lokinet >/dev/null && echo "Daemon shutdown failure" || echo "Daemon is stopped"
        echo -n "Fetching bootstrap <---- "
        lokinet-bootstrap "$2"
        systemctl start lokinet
        echo -n "Lokinet daemon restarted"
        ;;

exitup)
        lokinet-vpn --up --exit "$2" --token "$3"
        echo -n  "lokinet-vpn --up --exit ""$2"" --token ""$3"
        ;;

exitdown)
        echo -n "Stopping Exit"
        lokinet-vpn --down
        ;;

whois)
        echo -n "LNS Registration Information\n\n"
        whois -h public.loki.foundation "$2"
        ;;

  *)
        echo "Usage: "$1" {start|stop|gen|bootstrap|exitup|exitdown}"
        exit 1
        ;;
        esac
