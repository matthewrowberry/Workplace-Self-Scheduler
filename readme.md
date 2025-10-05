# Workplace Self-Scheduler

I have a business where employees can sign up for shifts based on their availability. I want a way to automatically send out shift sign ups now that I have more than one work station available. I want to be able to eventually reward good employees with being able to sign up early and get better shifts to promote more efficient work. That feature will be added later though. Current abilities are login, selecting desired shifts, submitting, removing shifts, updating.


## Instructions for Build and Use

Steps to build and/or run the software:

1. Copy code to folder on php and sql enabled web server
2. create your own config.js and config.php files. config.js has const url = and config.php has all the database login info
3. visit /CreateTable and /createUserTable to create the tables
4. Add at least one user to the users table to be able to access

Instructions for using the software:

1. Visit the site
2. login with credentials
3. select desired shifts
4. Press submit; it will store in database and others won't be able to remove

## Development Environment 

To recreate the development environment, you need the following software and/or libraries with the specified versions:

* Have php, html, css, javascript extensions in vscode

## Useful Websites to Learn More

I found these websites useful in developing this software:

* [W3Schools](w3schools.com)

## Future Work

The following items I plan to fix, improve, and/or add to this project in the future:

* [ ] Create limitations on when users can schedule defined by new tables
* [ ] Add Admin page to be able to manage users and limitations
* [ ] 