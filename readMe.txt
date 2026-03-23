# Readme for Notehub

## Table of Contents
1. Project Overview
2. Include build/run instructions
3. Running the Project
4. URL and Server Login Information
5. Login credentials
6. Pre-loaded data
7. How to use the application

1. Project Overview: 
   - Notehub is a web application where users use to create, view, delete, and rename notes after signing up for an account.
   Additonal features include: 
   - Users can pick an avatar from a selection provided by the website.
   - Notes can be customized with rich text formatting options such as bold, italic, underline, and checkboxes.

2. Include build/run instructions: 

   ## Technologies Used
   - PHP 8.2
   - HTML5
   - CSS3
   - JavaScript
   - [Bootstrap 5](https://getbootstrap.com/)
   - [jQuery](https://jquery.com/)
   - JSON

   This is a school project where all files including code and images, are stored directly in the 123host.vn website(https://free02.123host.vn:2222/evo/).

   ## How to run the project on your machine?

   To set up the project on your local machine, follow these steps:
   - Download and install the appropriate version of XAMPP for your operating system (https://www.apachefriends.org/download.html).
   - Launch XAMPP and start Apache and MySQL (ensure both are running and highlighted in green).
   - Place all files in the htdocs directory located within your XAMPP installation folder.
   - Open phpMyAdmin by clicking Admin under MySQL in XAMPP.
   - Import the provided database file: ijdtkfvr_user_accounts.sql.
   - Configure the database connection in db_config_notes.php with your local database credentials.

   ## Testing

   To test the project and ensure that everything is working correctly, follow these steps:

   a. **Verify File Paths**
      Ensure that all paths in the source files are updated to match your local file paths.

   b. **Test the Login and Registration functionalities **
      Open the index.html file in your web browser to access the login page.
      You can either create a new account by signing up, or log in using the provided test account below:
   - Email: notehub1805@gmail.com
   - Password: 123456

   c. **Test the note management**
      Upon successful login, the user is redirected to noteManagement.php, which provides access to all note management features like: 
         create, view, delete, rename, search for the note, and edit notes.

   d. **Test the logout functionalities **
      Users can log out at any time using the Logout button, which safely ends the session and redirects to the login page.

3. Running the project:
   There are two ways to run the project: on localhost or on a live server
      a. Running on Localhost: 
         - Launch XAMPP and start both Apache and MySQL.
         - Open your browser and navigate to: http://localhost/notehub/
      b. Running on a Live Server: 
         - Access the project through your browser at the following URL: [Notehub](https://n0tehub.me/).

4. URL and Server Login Information: 
   The project's repository for this project can be found [here](https://free02.123host.vn:2222/). 
   Please sign in by this account information below:
   - Username: ijdtkfvr
   - Password: iZtFium9Z5

   To view the database in phpMyAdmin, follow these instructions: 
   - Go to your hosting panel.
   - Search for "Account Manager" and click on "Databases" (look for the icon with the SQL symbol).
   - From there, you can access phpMyAdmin to view or manage the project's database.

5. Login credentials: 
   - The user account to log in the website: 
      Email: notehub1805@gmail.com
      User Password: 123456

6. Pre-loaded data: 
   - This test account has 1 note pre-loaded to demonstrate editing, deleting, renaming and setting passwords for the note.

7. How to use the application: 
   
   ## Logging in the website with a provided user account:
   - Click on the "Log In" (next to a white right-facing bracket icon) on the navigate bar in index page.
      Email: notehub1805@gmail.com
      User name: admin
      User password: 123456 

   ## If users choose to sign up your own account:
   - Click on the "Sign up" on the navigation bar in index page: 
   - Provided all information that server required: email, username, password and confirm password.
   - Wait for the activation email to be sent to your email (please check your spam-mail box if you do not see the email).
   - The confirmation email may take 5 to 10 minutes or slightly longer to arrive, so please be patient
      We apologize for any inconvenience this may cause. 
      If you do not receive the email, please contact our team via our members' GitHub accounts below: 
      ** https://github.com/AtLastttttt **
      ** https://github.com/Bontori1987 **
      ** https://github.com/tracynguyen203 **

   ## After logging in successfully: 
      If the user logs in with their own account, the website will redirect them to the User Preference page to select an avatar.

      Otherwise, the website will redirect them to the noteManagement page to access the note management functionalities:

      a. Creating a note: 
      - Click the Create button (represented by a pen inside a square icon), which is next to the logo of Notehub.

      b. Deleting a note: 
      - Click the Delete button (represented by a white trash can icon on a red background), which is the third icon attached to the note's title.

      c. Renaming a note's title: 
      - Click the Edit button (represented by a white pen icon on a blue background), which is the first icon attached to the note's title.

      d. Creating the password for a note: 
      - Click the Set note's password button (represented by a black lock on a yellow background), which is the second icon attached to the note's title.
      - This button will help users to set a password to view the note's content. 
         Therefore, if users want to change to the new password then they have to enter the old one and the new one.

      e. Pinning or unpinning a note: 
      - Click the Pin button (represented by a white thumbstack icon on a gray background), which is the icon positioned before the note's title.
      - This button will help users to pin the note on the top or you can unpin a note.

      f. Text formatting which is in the formatting toolbar above the note content: 
      - Making text bold: click the Bold button (represented by a bold capital letter "B" icon).
      - Making text italic: click the Italic button (represented by a italic capital letter "I" icon).
      - Making text underline: click the Underline button (represented by a underline capital letter "U" icon).
      - Making text become a checkbox: click the Checkbox button (represented by a list-checked icon).

      g. Live search notes: 
      - Typing on the search bar when you want to find the note's title or some words in the note's content.

      h. Display in grid or list view: 
      - Click the Layout button (represented by a white grid icon with large squares), which is used to toggle the note view layout.

      i. In the "Profile" section from the navigation bar: 
      - Users get to change their account name and avatar.
      - To change the account name: enter the new one. 
      - To change the avatar: choose the new one from the selection.

      j. In the "Setting" section from the navigation bar: 
      - Users will change their password in this section by providing the old one and the new one.
      - Users can change font or color for the note.

      k. Logging out the account: 
      - Click the Menu button (represented by a black bars icon - three horizontal lines) to open the navigation sidebar.
      - Look for the Log Out button (represented by black opened door with Log out text), which is the last icon in the accessing list of navigation bar.
