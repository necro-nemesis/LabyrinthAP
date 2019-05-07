#!/usr/bin/env bash
set -x
gateway_route=$(ip route | grep ^default | grep via | head -n1)
gateway_addr=$(echo "$gateway_route" | cut -d' ' -f3 )
for addr in $STRICT_CONNECT_ADDRS ; do
        sudo ip route add $(echo $addr | cut -d':' -f 1) via $gateway_addr
done
sudo ip route del $gateway_route
sudo ip route add default dev lokitun0
cat <<EOF > /.lokinet/on-down.sh
#!/usr/bin/env bash
set -x
for addr in \$STRICT_CONNECT_ADDRS ; do
        sudo ip route del \$(echo \$addr | cut -d':' -f 1) via $gateway_addr
done
sudo ip route del default dev lokitun0
sudo ip route add $gateway_route

EOF
chmod +x /.lokinet/on-down.sh
