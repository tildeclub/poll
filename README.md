# Poll Script

This script allows you to create a poll with multiple-choice options and an expiration date. The poll results are stored in a SQLite database and can be viewed using the results.php script.
Prerequisites:

    1.PHP 7.0 or higher
    2.SQLite 3
    3.A Google reCaptcha account (https://www.google.com/recaptcha/)
    
    
# Usage Instructions

Basic steps include:

1. Clone the repo.
2. Create new SQLite database and name it poll.db.
3. Create a new table in the database named 'poll_votes" with the following structure:

        CREATE TABLE poll_votes (option_id INTEGER);
  
4. Create a text file named poll_options.txt and add the expiry date of the poll and the poll options to the file. The expiry date should be the first line of the file, followed by the poll options. Each poll option should be on a new line.

For example:


        02/01/2023
        Option 1
        Option 2
        Option 3  
  

5. Create a text file named question.txt and add the poll question to the file.
6. Create a new PHP file named recaptcha_keys.php and add the following code to the file, replacing YOUR_SECRET_KEY and YOUR_SITE_KEY with your own reCaptcha secret and site key:

  
        <?php

        $recaptcha_secret = 'YOUR_SECRET_KEY';
        $recaptcha_site_key = 'YOUR_SITE_KEY';

        ?>

7. Upload all files to your hosting provider.  Ensure poll.db is writable by the web server.  

The reset.sh script is how you can clear the database rows when you start a new poll.
