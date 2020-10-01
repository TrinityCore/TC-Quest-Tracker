TC-Quest-Tracker
================

[![Codacy Badge](https://api.codacy.com/project/badge/Grade/c8c59088c70b4631a8bda494e1c27468)](https://www.codacy.com/app/TrinityCore/TC-Quest-Tracker?utm_source=github.com&amp;utm_medium=referral&amp;utm_content=TrinityCore/TC-Quest-Tracker&amp;utm_campaign=Badge_Grade)

Quest Tracker is a tool for [TrinityCore](https://github.com/TrinityCore/TrinityCore) that tracks most abandoned quests [(see details)](https://github.com/TrinityCore/TrinityCore/pull/13353) and this is a web interface to show them.

You need to enable your Quest Tracker in your **worldserver.conf** file by setting:

```
Quests.EnableQuestTracker = 1
```

To install the web interface, go in your web server folder and type:

```
git clone https://github.com/ShinDarth/TC-Quest-Tracker.git
```

then open the **TC-Quest-Tracker** folder, copy the file **config.php.dist**, rename the copy to **config.php** and edit it properly (it's quite commented).

---

#### Screenshot

![Screenshot](https://raw.githubusercontent.com/TrinityCore/TC-Quest-Tracker/master/screenshot.jpg)

### License

The TrinityCore Quest Tracker is open-sourced software licensed under the [GNU AGPL license](https://github.com/ShinDarth/TC-Quest-Tracker/blob/master/LICENSE).

