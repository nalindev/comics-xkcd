<h1 align="center"> XKCD Comics </h1> <br>
<p align="center">
  <a href="https://www.comics.igsavers.com/assets/img/icon.jpg">
    <img alt="Comics" title="XKCD Comics" src="https://www.comics.igsavers.com/assets/img/icon.jpg" width="180">
  </a>
</p>

<p align="center">
  Receive random comics updates on your mail every 5 minutes to get updated with latest comics.<br>
  <a href="https://www.comics.igsavers.com/"><strong>Click To Visit On Demo Page</strong> </a>
</p>

## Introduction 

Register on XKCD comics to get comic updates every 5 minutes. you can unsubscribe from our mail any time you want. Our script will also send you an image attachment of every comic.

> **Important Note** :  No 3rd party codes, libraries are used in this project. Hence, this code is lightweight and easy to set up and understand. I try to write my code as clean as possible. Also, it doesn't have any security issues.

> **This Project Follows PHP coding standards**

**Available On Web.**

<p align="center">
  <img src = "https://i.imgur.com/gEaXBnE.png" width=390>
</p>

## Features

A few featured of this comic application:

* Email verification before registering
* send comics updates on every 5 mins
* unsubscribe from mail anytime
* Easy to use UI
* No security vulnaribilities.
* No 3rd party codes, libraries are used (Therefore No lib folder created) 

<p align="center">
  <img src = "https://i.imgur.com/qfyrUtW.png" width=390>
</p>

## Setup Process

- Clone or download the repo
- Turn on your xampp server
- create a new database and save it's credentials in `core/config.php`.

```
$dbhost = 'localhost';
$dbuser = 'database_user';
$dbpass = 'Your_pass';
$dbname = 'database_name';
```
- setup cron on `cron.php` file on every 5 minutes.
- add your server email address in `core/config.php` file.
```
$from = "mail@yourdomain.com";
```
**Now This Project will start working**

## Unsubscribe any time
You can unsubscribe from the XKCD Comics server anytime you want with just a single click. 

<p align="center">
  <img src = "https://i.imgur.com/4xdlzdj.png" width=390>
</p>

## Important Notes
- After email validation, it takes 8-9 minutes to start arriving the mail at your email address so please keep patience.
- After that, every email will arrive at 5 mins interval.