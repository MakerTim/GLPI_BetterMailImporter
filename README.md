Better Mail Importer for [GLPI](www.glpi-project.org)
=======================================================

![GLPI Banner](https://i.imgur.com/50w7Pyn.png)

This is a plugin to create more rules for mail importing

## Table of Contents

* Adds the ability to more then 1 Action to a mail import rule
* Automatically add a group as requester based on rule[s]
* Automatically add a group to assignees based on rule[s]

##How to install

1) Download project (as ZIP) from GIT
2) Unzip folder to plugin directory ``GLPI-FOLDER/plugins/``
3) **REQUIRED** Rename folder to ``bettermailimporter`` (= Better Mail Importer)
4) Go into GLPI to ``Setup > Plugins`` and install bettermailimporter
5) Then Activate/Enable the plugin by pressing on the red switch

##How to use the plugin

**Mail rules are only available when you have a Mailreceiver**
> *To enable mail receivers, go to ``Setup > Receivers`` and add a mail receiver / mailgate into GLPI*

* Go to ``Administration > Rules > "Rules for assigning a ticket created through a mails receiver"``
and create / open a rule.
* After creating a ``Criteria`` *(without the rule will never be triggered)* go to ``Actions``
* Add a new Action, choose ``Group`` then ``Assign`` or ``Follow`` and select the group you want!
* Press Add to save

From now on, every mail you receive will get get assigned or be followed by the selected group! 
> WARNING, when removing the plugin, multiple actions will still be triggerd!
> ONLY not the options that where added by this plugin [group...]

##Support

The plugin is only tested for GLPI 4.2+, should work on older versions; not confirmed nor denied yet

##Contributing and Feature requests

Feel free to open a ticket or pull request! 💙OpenSource💙 
