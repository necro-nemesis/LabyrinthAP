#!/bin/sh

# 4G
sudo qmicli -d /dev/cdc-wdm0 --dms-set-operating-mode='online' # power on module
# configure raw-ip protocol
sudo ip link set wwan0 down
echo 'Y' | sudo tee /sys/class/net/wwan0/qmi/raw_ip
sudo ip link set wwan0 up
# connect to carrier
sudo qmicli -p -d /dev/cdc-wdm0 --wds-start-network="apn='your.apn.here',ip-type=4" --client-no-release-cid
# set default rout and IP
sudo udhcpc -i wwan0
# receive DHCP lease from network
ip a s wwan0

exit 0
