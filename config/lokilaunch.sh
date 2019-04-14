#!/bin/sh

case "$1" in
  start)
        echo -n "Starting LOKINET\n"
        /home/pi/loki-network/lokinet > /dev/null 2>&1 &
        sudo iptables -t nat -A POSTROUTING -s 10.3.141.0/24 -o lokitun0 -j MASQUERADE #LOKIPAP
        ehco -n "rerouted iptables\n"
        sudo ip rule add from 10.3.141.1 lookup main prio 1000
        echo -n "added wlan0 address rule\n"
        sudo ip rule add from 10.3.141.0/24 lookup lokinet prio 1000
        echo -n "added wifi-clients rule\n"
        sudo ip route add default dev lokitun0 table lokinet
        echo -n "added lokitun0 route\n"
        echo -n "Restarting DNSMASQ\n"
        sudo /etc/init.d/dnsmasq restart
        ;;

  stop)
        echo -n "Stopping daemon\n"
        pkill lokinet
        sudo iptables -t nat -F
        sudo iptables -F
        sudo iptables -t nat -A POSTROUTING -j MASQUERADE
        ehco -n "rerouted iptables\n"
        sudo ip rule del from 10.3.141.1 lookup main prio 1000 #LOKIPAP
        echo -n "removed wlan0 address rule\n"
        sudo ip rule del from 10.3.141.0/24 lookup lokinet prio 1000 #LOKIPAP
        echo -n "removed wifi-clients rule\n"
        sudo ip route del default dev lokitun0 table lokinet
        echo -n "removed lokitun0 route\m"
        echo -n "Restarting DNSMASQ\n"
        sudo /etc/init.d/dnsmasq restart
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
