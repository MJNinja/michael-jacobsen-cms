# Legacy PHP CMS

Legacy PHP CMS developed as a personal project, showcasing custom content management functionality, architecture, and development practices from an earlier stage of my career.

## Overview

This repository contains a modular PHP-based Content Management System that I developed as a personal learning project many years ago.

The project was never intended to become a commercial product and is being published primarily for historical and portfolio purposes. It represents my approach to PHP application development, CMS architecture, modular design, and custom content management solutions at that stage of my career.

## Important Notice

⚠️ **This project is provided for historical and educational purposes only.**

* The codebase is no longer actively maintained.
* It is not production-ready.
* It has not been reviewed for modern security standards.
* It may contain bugs, security vulnerabilities, outdated dependencies, and coding practices that would not meet current development standards.
* No support or updates are planned.

Please do not deploy this project to a public-facing production environment without a complete review and modernization effort.

## Project Structure

### Base Installations

The repository contains two base CMS variants:

#### `_basic`

Standard CMS installation.

#### `_basic_language_options`

CMS installation with additional language-management functionality.

Choose one of these directories as your starting point and copy its contents into the root directory of your project.

## Installation Process

### 1. Choose a Base Version

Select either:

* `_basic`
* `_basic_language_options`

Copy the contents into your web root or project directory.

### 2. Execute Database Scripts

Various modules include a `Database.txt` file containing SQL statements required for installation.

Execute the relevant SQL scripts against your database.

### 3. Apply Additional Integration Steps

Some modules include additional `.txt` files containing:

* SQL scripts
* Cron job definitions
* JavaScript snippets
* Header integrations
* Footer integrations

These instructions must be applied manually.

Typical integration files include:

* `header-inc.txt`
* `footer-inc.txt`
* `javascript-inc.txt`

The contents of these files should be added to their corresponding CMS include files.

### 4. Install Modules

Modules are located inside the `_modules` directory.

Each module generally contains:

* CMS pages
* Library classes
* Icons
* Database scripts
* Integration instructions

To install a module:

1. Copy the module's CMS files into the existing `cms` directory created by the selected base installation.
2. Copy any remaining files into their corresponding locations.
3. Execute any supplied SQL scripts.
4. Apply any additional integration instructions contained in the provided `.txt` files.

## Included Modules

The repository includes a collection of modules, including:

* Basic Content Manager
* Blog Manager
* Affiliate Manager
* Portfolio Manager
* Resource Manager
* Video Tutorials Manager
* Banner Manager
* Quotes Manager
* Employment History Manager
* Software Manager
* Staff Manager
* Product Manager
* Feedback Manager
* Gallery Manager
* Tours Manager
* FAQ Manager
* Vacancy Manager
* Events Manager
* Forms Manager

## cPanel Backup Utilities

The `_cpanel_backups` directory contains scripts that automate cPanel account backups.

Features include:

* Automated full cPanel backups
* Backup relocation and organization
* Backup retention management
* Integration with external archival solutions

Backups are stored in the `_backups` directory and can be further transferred to external storage solutions such as Amazon S3 Glacier or other long-term backup platforms.

## Technology

This project reflects technologies and development practices common at the time it was created, including:

* PHP
* MySQL
* JavaScript
* jQuery
* CKEditor

## License

This project is licensed under the MIT License.

See the LICENSE file for details.
