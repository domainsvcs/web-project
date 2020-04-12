# web-project

Boilerplate for simple PHP/Sass/MySQL project.

Designed to save time bootstrapping small admin utilities and CRUD interfaces.

Not intended for production web services.

Not a usable application. Requires a thorough understanding of PHP and MySQL. I do not warrant the code as fit for any particular purpose or that it will work without corrections or modification. In progress.

## Source platform
- Debian 9
- PHP 7.4
- MySQL 5.7

## Requires
- [Composer installed globally: https://getcomposer.org/doc/00-intro.md#globally](https://getcomposer.org/doc/00-intro.md#globally)

- [Sass Compiler: https://sass-lang.com/install](https://sass-lang.com/install)  
I use the deprecated ruby-sass compiler on Debian. I recommend getting the current Sass or sassc version.

## Installation
- Clone template: [Github Instructions](https://help.github.com/en/github/creating-cloning-and-archiving-repositories/creating-a-repository-from-a-template)
- Change all references to web-project in composer.json and other files to your project name.
- Update composer to pull in Bootstrap and jQuery:  
    composer update
- Run Installer to copy files to web accessible folders and compile basic css:  
    sh ./install.sh
- Execute SQL in db.sql in your chosen database.
- Edit newly created config.inc.php with your database credentials.
