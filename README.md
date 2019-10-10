![](https://i.imgur.com/mXuacOH.jpg)

# `$ Lokiap-webgui` [![Release 1.7](https://img.shields.io/badge/Release-1.7-green.svg)](https://github.com/necro-nemesis/raspap-webgui/releases)

LokiAP interfaces witht Lokinet daemon to facilitate connections to the the Lokinet global privacy network. LokiAP provides a simple, responsive web interface to control wifi, hostapd, Lokinet daemon and related services necessary to access Lokinet on the Raspberry Pi or Orange Pi.

### WHAT IS LOKI?

https://loki.network/

"Loki is a privacy network which will allow users to transact and communicate privately over the internet, providing a suite of tools to help maintain the maximum amount of anonymity possible while browsing, transacting and communication online. Using the decentralised nature of blockchain technology, Loki creates new private and secure methods of interacting with the internet, as well as building privacy-centric applications, such as messaging services, forums, online marketplaces, and social media platforms."

Loki

![](https://i.imgur.com/V9coVgA.jpg)

This project branches from the work of Raspap and SB Admin 2.

![](https://i.imgur.com/qdXbAGn.png)

## Contents

 - [Prerequisites](#prerequisites)
 - [Quick installer](#quick-installer)
 - [Test Site](#test-site)
 - [Support us](#support-us)
 - [How to contribute](#how-to-contribute)
 - [License](#license)

## Prerequisites
Start with a clean install of the [latest release of Raspbian](https://www.raspberrypi.org/downloads/raspbian/) (currently Buster and Stretch are verified as working). Lite versions are recommended. If using Raspbian Buster you will need to run the command ```apt-get update --allow-releaseinfo-change``` just before running the LokiAP installer script.

For OrangePi Zero you can use an Armbian Buster "nightly" found here": https://dl.armbian.com/orangepizero/nightly/ or stable Armbian Stretch found here: https://dl.armbian.com/orangepizero/

For Orange Pi R1 you can use Armbian Buster "nightly" found here: https://dl.armbian.com/orangepi-r1/nightly/ or stable Stretch found here: https://dl.armbian.com/orangepi-r1/

Be aware "nightly" Armbian releases can be unstable but are needed to run Buster. If in doubt use a Stretch release build.

To burn the image to an SD card on your PC you can may Etcher:
https://www.balena.io/etcher/

## Preparing the image

For Raspbian you will need to remove the SD card from the computer, reinsert it, open the boot directory up and create a new textfile file named `ssh` with no .txt file extension i.e. just `ssh` in order to remotely connect. This step is not required for Armbian.

Insert the SD card into the device and power it up.

## Accessing the device

Obtain a copy of Putty and install it on your PC:
https://www.chiark.greenend.org.uk/~sgtatham/putty/latest.html

1.  Log into your router from your PC and find the address it assigned to the Pi.

2.  Start Putty up and enter this obtained address into Putty with settings:

    Host Name Address = the address obtained from router | Port `22` | connection type `SSH` | then `OPEN`

    For Raspbian the default login is `root` password `raspberry`
    For Armbian the default login is `root` password `1234`

3.  Follow any first user password instructions provided once logged in.

4. If you want to get the lastest updates before installing LokiAP:
```
sudo apt-get update
sudo apt-get upgrade
sudo reboot
```
5. Set the WiFi country in raspi-config's / armbian-config's **Localisation Options**: `sudo raspi-config` or `sudo arbmian-config` for RasperryPi or OrangePi respectively.

6. If you have an older Raspberry Pi without an onboard WiFi chipset, the [**Edimax Wireless 802.11b/g/n nano USB adapter**](https://www.edimax.com/edimax/merchandise/merchandise_detail/data/edimax/global/wireless_adapters_n150/ew-7811un) is an excellent option – it's small, cheap and has good driver support.

With the prerequisites done, you can proceed with either the Quick installer or Manual installation steps below.

## Quick installer

Install LokiAP from shell prompt:
```sh
$ wget -q https://git.io/fjeSw -O /tmp/raspap && bash /tmp/raspap
```
The installer will complete the steps in the manual installation (below) for you. You will be occasionally prompted to answer `y` or `n`. Answering yes to all prompts will in almost all cases be the answer you want so if in doubt respond with `y` that you want the default set up and the AP will work.

After the reboot at the end of the installation the wireless network will be
configured as an access point as follows:
* IP address: 10.3.141.1
  * Username: `admin`
  * Password: `secret`
* DHCP range: 10.3.141.1 to 10.3.141.24
* SSID: `loki-access`
* Password: `ChangeMe`

## Test site

To test the connection navigate to Loki's wiki page found at
http://icxqqcpd3sfkjbqifn53h7rmusqa1fyxwqyfrrcgkd37xcikwa7y.loki/wiki/index.php?title=Main_Page

## Support us

LokiAP is free software, but powered by your support. If you find it beneficial or wish to contribute to inspire ongoing development your donations of any amount; be they even symbolic, are a show of approval and are greatly appreciated.

Loki Donation Address:
```sh
LA8VDcoJgiv2bSiVqyaT6hJ67LXbnQGpf9Uk3zh9ikUKPJUWeYbgsd9gxQ5ptM2hQNSsCaRETQ3GM9FLDe7BGqcm4ve69bh
```
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
