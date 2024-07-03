## Getting Started
### Requirements

1. [PHP 7.3+](https://www.apachefriends.org/download.html)
1. [Composer](https://getcomposer.org/download/)


### Preparing

1. Run ```composer install``` command to install required dependencies
2. Copy ```.env.example``` file and rename to ```.env```
3. Run ```php artisan key:generate``` command to generate `APP_KEY` into the `.env` file
4. Create a database with the name ```PBEngine```
5. Run ```php artisan serve``` command to run the app server

## Contribute

1. Pull the newest changes on `master` branch using
    ```
    git checkout master
    git pull
    ```
1. Create and checkout a new branch from `master` branch using `git checkout -b '[your branch name]'`
1. Do your work
1. Run `composer lint` command to automatically fix correctable issues on your code.
1. If there are errors, please correct them according to the error messages
1. Run `git add .` to stage your changes
1. Run `git commit -m '[your commit message]'` to commit your changes
1. Run `git push` to push your changes into remote repository
1. To create a pull request, click the link below the `Create a pull request for...` text
1. In your browser, then click `Create pull request` button