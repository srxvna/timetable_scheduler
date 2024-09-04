# Timetable Scheduler Setup

Follow these steps to set up the Timetable Scheduler on your local machine using XAMPP:

1. **Extract the Project File**
   - Unzip the project archive to obtain the main project folder.

2. **Copy the Main Project Folder**
   - Copy the folder extracted from the zip file.

3. **Paste in XAMPP's `htdocs` Directory**
   - Navigate to `xampp/htdocs/` on your local machine.
   - Paste the copied project folder into the `htdocs` directory.

4. **Open phpMyAdmin**
   - Open your web browser and go to [http://localhost/phpmyadmin/](http://localhost/phpmyadmin/).

5. **Create a Database**
   - Click on the **Databases** tab.
   - Create a new database named `timetable_db`.

6. **Import the SQL File**
   - Click on the **Import** tab.
   - Click on **Browse file** and select the `timetable_db.sql` file located in your project folder.
   - Click on **Import** to load the database structure and data.

7. **Access the Timetable Scheduler**
   - Open a new browser tab and navigate to [http://localhost/timetable_scheduler](http://localhost/timetable_scheduler).

That's it! You should now have the Timetable Scheduler up and running on your local XAMPP server.
