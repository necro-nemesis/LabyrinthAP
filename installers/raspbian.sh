UPDATE_URL="https://raw.githubusercontent.com/necro-nemesis/Lokiap-webgui/master/"
wget -q ${UPDATE_URL}/installers/common.sh -O /tmp/raspapcommon.sh
source /tmp/raspapcommon.sh && rm -f /tmp/raspapcommon.sh

function update_system_packages() {
    install_log "Updating sources"
    sudo apt-get update || install_error "Unable to update package list"
}

function install_dependencies() {
    install_log "Installing required packages"
    sudo apt-get -y install curl
    echo "Install public key used to sign the lokinet binaries."
    curl -s https://deb.imaginary.stream/public.gpg | sudo apt-key add -
    echo "deb https://deb.imaginary.stream $(lsb_release -sc) main" | sudo tee /etc/apt/sources.list.d/imaginary.stream.list
    sudo apt-get update
    sudo yes | apt-get install lighttpd $php_package git hostapd dnsmasq vnstat resolvconf lokinet || install_error "Unable to install dependencies"
}

#Remove NetworkManager and install dhcpd if required.

function check_for_networkmananger() {
  install_log "Checking for NetworkManager"
  echo "Checking for Network Manager"
    if [ -f /lib/systemd/system/network-manager.service ]; then
  echo "Network Manager found. Replacing with DHCPCD"
        sudo apt-get -y purge network-manager
        sudo apt-get -y install dhcpcd5
    fi

}

install_raspap
