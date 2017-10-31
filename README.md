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

Host block features:
--------------------

* Each host block displays a maximum of 3 triggers, hover on trigger to see full text.
* In case of multipe triggers fired on a host, the highest priority trigger will adjust the color and or size of the hostblock
* There are 5 stages defined in which a block is displayed based upon trigger severity. (as per Zabbix)
* Triggered host blocks will get the state normal when the trigger state is "OK" (via acknowledgment of trigger or threshold level is normal)

*This has been tested with version 3.4.x of Zabbix.*

Additional Links:
* https://www.zabbix.com/
* https://github.com/incama/Zabbix-Dashboard (Original Project)
* http://jquerymobile.com/
* http://jquery.com/
