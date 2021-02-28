![](https://i.imgur.com/2ZrhaiH.png)

# `LabyrinthAP / Lokinet Access Point` [![Release 2.3](https://img.shields.io/badge/Release-2.3-green.svg)](https://github.com/necro-nemesis/raspap-webgui/releases)

LabyrinthAP interfaces with Lokinet daemon to facilitate connections to the Lokinet global privacy network. LabyrinthAP provides a simple, responsive web interface to control wifi, hostapd, Lokinet daemon and related services necessary to access Lokinet on the Raspberry Pi or Orange Pi.

### WHAT IS LOKINET?

https://lokinet.org/

Lokinet is a privacy network which allows users to transact and communicate privately over the internet infrastructure using it's own onion routing network, encryption system and protocols. Lokinet requires no internet IP Address but instead provides it's own network addresses which can be either ephemeral, persistent or personalized depending on the users desired application. Information is onion routed through a globally distributed network of over one thousand nodes. Utilizing blockchain technology Lokinet is run on incentivized decentralized nodes that are paid by the network to maintain the privacy and decentralized aspects of the network. User need not pay for access to use Lokinet but may chose to subscribe to an exit provider should they wish to anonymously access the internet through such a provider. Additionally the network generally has free exit nodes available offered through individuals providing volunteerary support of the network.

LabyrinthAP is currently offered as a free software solution that runs Lokinet on a remote network connected device which creates a WiFi hostspot and/or Ethernet subnet to connect to Lokinet. Using LabyrinthAP not only ensures Lokinet is always connected and available but removes the requirement for installation on a single or group of end point devices. LabyrinthAP makes Lokinet platform agnostic meaning it can connect to a range of devices regardless of the type of hardware or software installed on them. LabyrinthAP is compatible with most web based applications and browsers and has a built in web based interface to manage and control.

LabyrinthAP comes with it's own auto-installation scripts to set up Lokinet and the LabyrinthAP interface thereby providing an easy to create access point on very commonly available single board computer devices such as the Rapsberry Pi using Raspberry OS. It also supports device running Armbian such as the OrangePiR1 or OrangePi Zero + etc.

![](https://i.imgur.com/IbksKgc.png)

![](https://i.imgur.com/F83n7PF.jpg)

## Contents

 - [Prerequisites](#prerequisites)
 - [Preparing the image](#preparing-the-image)
 - [Accessing the device](#accessing-the-device)
 - [Quick installer](#quick-installer)
 - [Test Site](#test-site)
 - [Connecting to an Exit Node](#connecting-to-an-exit-node)
 - [Waveshare 4G Mobile Pi Hat](#waveshare-4G-mobile-pi-hat)
 - [Support us](#support-us)
 - [How to contribute](#how-to-contribute)
 - [License](#license)

## Prerequisites
Start with a clean install of [Armbian](https://www.armbian.com/) or [Raspberry Pi OS](https://www.raspberrypi.org/downloads/raspberry-pi-os/) (currently Buster and Stretch are verified as working). Lite versions are recommended as all additional dependencies are added by the installer. If using Raspberry Pi OS you will need to elevate to root with ```sudo su``` before running the LabyrinthAP installer script. This additional step is not required when using Armbian.

For Orange Pi R1 use Armbian Buster found here: https://www.armbian.com/orange-pi-r1/. Recommend using "minimal" which is available for direct download at the bottom of the page or much faster download by .torrent also linked there.

Specific code has been incorporated to take advantage of the OrangePi R1's second ethernet interface. The AP will provide access via ethernet in addition to wifi when using this board.

For OrangePi Zero use Armbian Buster found here": https://www.armbian.com/orange-pi-zero/

Note:

Although it will function the OrangePi Zero has a well documented issue with the XR819 wifi chip used on this board. It will drop connections occasionally as a result of unresolved firmware issues. The board is supported but I would recommend using the R1 over the Zero given it has two ethernet adapters and is equipped with a Realtek wifi chip which is stable.

To burn the image to an SD card on your PC you can use Etcher:
https://www.balena.io/etcher/

## Preparing the image

For Raspberry Pi OS you will need to remove the SD card from the computer, reinsert it, open the boot directory up and create a new textfile file named `ssh` with no .txt file extension i.e. just `ssh` in order to remotely connect. This step is not required for Armbian.

Insert the SD card into the device and power it up.

## Accessing the device

Obtain a copy of Putty and install it on your PC:
https://www.chiark.greenend.org.uk/~sgtatham/putty/latest.html

1.  Log into your router from your PC and find the address it assigned to the Pi.

2.  Start Putty up and enter this obtained address into Putty with settings:

    Host Name Address = the address obtained from router | Port `22` | connection type `SSH` | then `OPEN`

    For Raspberry Pi OS the default login is `pi` password `raspberry`
    For Armbian the default login is `root` password `1234`

3.  Follow any first user password instructions provided once logged in.

4. If you want to get the lastest updates before installing LabyrinthAP:
```
sudo apt-get update
sudo apt-get upgrade
sudo reboot
```
5. Before installing the LabyrinthAP on the Raspberry pi you must log in to the shell prompt and set your WiFi "Localization Options" to the country code you are in. If this is not set WiFi will not be available on the Rasberry pi. Additionally if you are using the LabyrinthAP with a SIMCOM chip based mobile adapter for cellular connection to the net you will need to go into Interface Options -> serial -> answer "NO" when asked if you want login shell over serial and then answer "YES" when asked if you want the serial port hardware enabled. In order to set these options enter `sudo raspi-config` at the command line and use the menu to get to these settings. When exiting and asked to reboot answer yes and reboot for the settings to be enabled.

With the prerequisites done, you can now proceed with the Quick installer.

## Quick installer

Install LabyrinthAP from shell prompt:
```sh
$ wget -q https://git.io/fjeSw -O /tmp/raspap && bash /tmp/raspap
```
The installer will complete the installation steps for you. You will be occasionally prompted to answer `y` or `n`. Answering yes to all prompts will in almost all cases be the answer you want so if in doubt respond with `y` that you want the default set up and the AP will work.

After the reboot at the end of the installation the wireless network will be
configured as an access point as follows:

* AP GUI address: loki.ap.local or use IP address: 10.3.141.1
  * Username: `admin`
  * Password: `secret`
* DHCP range: 10.3.141.1 to 10.3.141.24
* SSID: `LabyrinthAP`
* Password: `ChangeMe`

## Test site

To test the connection navigate to Lokinet's wiki page found at
http://dw68y1xhptqbhcm5s8aaaip6dbopykagig5q5u1za4c7pzxto77y.loki/wiki/index.php?title=Main_Page
You can also access this page using it's ONS registered domain name at
http://probably.loki

## Connecting to an Exit Node

Exit nodes provide privacy on the internet through onion routing connections via the global network of Lokinet relay nodes and dedicated exit nodes. In order to connect to an exit node you will require the exit address from the exit node provider. These can be found through various social media linked at https://loki.network/. From the GUI this information can be entered and activated.

## Waveshare 4G Mobile Pi HAT

If you have a Waveshare 4G/3G/2G/GSM/GPRS/GNSS HAT for the Raspberry Pi the access point has built in support for it to use cellular data. When installed if Ethernet is available the access point will connect over Ethernet. If you wish to use cellular then booting without an Ethernet connection the access point will automatically switch over to cellular and connect to your provider. You will need to obtain a SIM card and it's APN address from your local cellular service provider. The APN addresses are usually obtainable with a quick online search. Once the software is installed you will need to navigate to the "Mobile APN" tab in the GUI under "Configure Lokinet" and enter in your APN address, set it and then reboot. It will now be saved for future use of the adapter.

![](https://i.imgur.com/eD82qCT.png)
![](https://i.imgur.com/feaC56c.png)

## Support us

LabyrinthAP is free software but powered by your support. If you find it beneficial or wish to contribute to inspire ongoing development small donations are greatly appreciated.

- Oxen Donation Address:
```sh
LA8VDcoJgiv2bSiVqyaT6hJ67LXbnQGpf9Uk3zh9ikUKPJUWeYbgsd9gxQ5ptM2hQNSsCaRETQ3GM9FLDe7BGqcm4ve69bh
```
- PayPal Donation Address:

![](https://i.imgur.com/gIhGB1X.jpg)

## How to contribute

1. File an issue in the repository, using the bug tracker, describing the
   contribution you'd like to make. This will help us to get you started on the
   right foot.
2. Fork the project in your account and create a new branch:
   `your-great-feature`.
3. Commit your changes in that branch.
4. Open a pull request, and reference the initial issue in the pull request
   message.

## License
See the [LICENSE](./LICENSE) file.
