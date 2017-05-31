# [ACME](https://github.com/kilinkis/acme-test)

Simple theme for WordPress projects.

* Project: [github.com/kilinkis/acme](https://github.com/kilinkis/acme)
* Twitter: [@kilinhead](http://twitter.com/kilinhead)
* Author : [Juan Manuel Incaurgarat](http://kilinkis.me)

## Version

1.0

## Deployment

To deploy this theme, SSH into your server (ssh user@server.domain.com and wait for the password prompt).
Then navigate to the themes directory (wp-content/themes/) and finally clone this repo:
https://github.com/kilinkis/acme.git

And voilá, now you have ACME theme installed and ready to activate. Happy WordPressing!

To update it, you will notice that if you repeat the steps above, you get an error saying the directory already exits and it's not empty.
To work around this there are at least a couple of ways:

Option 1. (Easier and recommended) cd into the acme directory and run: git pull https://github.com/kilinkis/acme.git

Option 2. Clone the repo into a new directory (e.g. acme1.1). Now you will see the theme new version in the admin screen, so you can test it (using live preview). If everything went well, you can just activate the new copy of the theme and delete the former.

Option 3. Use Github Updater plugin (https://github.com/afragen/github-updater). This plugin watches the github repo and lets you know when there are new versions available, so you update it just like you do with all the other plugins and themes.
If you are a developer, just remember to update the theme version in the style.css.
