TC-Quest-Tracker
================

Quest Tracker is a tool for [TrinityCore](https://github.com/TrinityCore/TrinityCore) that tracks most abandoned quests [(see details)](https://github.com/TrinityCore/TrinityCore/pull/13353) and this is a web interface to show them.

You need to enable your Quest Tracker in your **worldserver.conf** file by setting:

```
Quests.EnableQuestTracker = 1
```

To install the web interface, go in your web server folder and type:

```
git clone https://github.com/Trinitycore/TC-Quest-Tracker.git
```

then open the **TC-Quest-Tracker** folder, copy the file **config.php.dist**, rename the copy to **config.php** and edit it properly (it's quite commented).

Make sure to enable pdo_mysql extension too, i.e. edit php.ini and uncomment ```extension=pdo_mysql``` row.

---

#### Screenshot

![Screenshot 1](https://raw.githubusercontent.com/masterking32/TC-Quest-Tracker/master/screenshot1.png)

![Screenshot 2](https://raw.githubusercontent.com/masterking32/TC-Quest-Tracker/master/screenshot2.png)

### License

The TrinityCore Quest Tracker is open-sourced software licensed under the [GNU AGPL license](https://github.com/ShinDarth/TC-Quest-Tracker/blob/master/LICENSE).

