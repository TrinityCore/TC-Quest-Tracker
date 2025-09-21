TC-Quest-Tracker
================

Quest Tracker is a tool for [TrinityCore](https://github.com/TrinityCore/TrinityCore) that tracks most abandoned quests [(see details)](https://github.com/TrinityCore/TrinityCore/pull/13353) and this is a web interface to show them.

You need to enable your Quest Tracker in your **worldserver.conf** file by setting:

```
Quests.EnableQuestTracker = 1
```

# Installation

- Make sure you have a web server with PHP 8.0+ and MySQL/MariaDB.
- Make sure you have [Composer](https://getcomposer.org/) and [Git](https://git-scm.com/) installed.

Then clone this repository:

```bash
git clone https://github.com/masterking32/TC-Quest-Tracker.git
```

Then, in your web server folder, run:

```bash
composer install
```

Edit the file **config.php.dist** to set your database connection parameters.
Rename it to **config.php**.

Finally, open your web browser and go to the folder where you installed the tracker, e.g. http://localhost/

Make sure to enable pdo_mysql extension too, i.e. edit php.ini and uncomment ```extension=pdo_mysql``` row.

---

#### Screenshot

![Screenshot 1](https://raw.githubusercontent.com/masterking32/TC-Quest-Tracker/refs/heads/master/screenshot1.png)

![Screenshot 2](https://raw.githubusercontent.com/masterking32/TC-Quest-Tracker/refs/heads/master/screenshot2.png)

### License

The TrinityCore Quest Tracker is open-sourced software licensed under the [GNU AGPL license](https://github.com/ShinDarth/TC-Quest-Tracker/blob/master/LICENSE).


### Credits
- [masterking32](https://github.com/masterking32) 
