Zabbix-Dash2
============

Cloned from https://github.com/incama/Zabbix-Dashboard and updated to work with Zabbix 3.4. 

This was put together as a need and expanded to allow for better functionality overall.

Screen Configuration Page
-------------------------
<img src="https://github.com/tdp4/Zabbix-Dash2/raw/master/docs/Zabbix_Dashboard.png" />

Sample Dashboard
----------------
<img src="https://github.com/tdp4/Zabbix-Dash2/raw/master/docs/ZbxDash_Test_Dashboard.png" />

Features / Requirements:
------------------------

* Gets triggers from hosts which are nested in hostgroups.
* Requres API user with read access to hosts.
* Screens are optimized for 1920px capable monitors.
* Masonry js library is used to align host blocks tightly.
* JQuery and JQueryMobile are used for the rest of the interface heavy lifting.
* Requires http://zabbixapi.confirm.ch (Zabbix PHP API) which is included in this build.
* Works with Zabbix 3.4
* Host groups can contain up to 7 hosts per line.
* Requires PHP 5.6 or better.

Host block features:
--------------------

* Each host block displays a maximum of 3 triggers, hover on trigger to see full text.
* In case of multipe triggers fired on a host, the highest priority trigger will adjust the color and or size of the hostblock
* There are 5 stages defined in which a block is displayed based upon trigger severity. (as per Zabbix)
* Triggered host blocks will get the state normal when the trigger state is "OK" (via acknowledgment of trigger or threshold level is normal)

*This has been tested with version 3.4.x of Zabbix.*

Installation
============

* Clone/Download project to a host with a webserver.  You can use your existing Zabbix web installation.
* Place a copy of the installation where it is accessable by your webserver.  Choices are:
  * Directly in the path of your web server (check your webserver's configuration).
  * In a subdirectory in your webserver (zabbix-dash2 recommended).
  * In (on linux) /usr/share/zabbix-dash2 (make sure to set permissions in a sane manner).
* Copy config.php.template into config.php
  * Set username and password used to login to Zabbix.  To encode password use: enc_passwd.sh
  * Set $context to match the location on your webserver where this installtion is located. (ex: if in http://myhost/zabbix-dash2, use "/zabbix-dash2").
  * Adjust $host_group_filter if needed.  To remove filter, use '/./' (matches anything).
* Test installation by going to index.php.  Check you webserver logs for any errors connecting.

Troubleshooting
===============

* Verify your permissions of the files are correct.  If the webserver can't read them, they won't display.
* If javascript and css are not loading, check your $context is right.
* If you don't see your host groups, maybe your filter is wrong.
* If the page is blank, likely there is a problem connecting to the API, verify your user/password.

Additional Links:
* https://www.zabbix.com/
* https://github.com/incama/Zabbix-Dashboard (Original Project)
* http://jquerymobile.com/
* http://jquery.com/
