# ExpoGeo

[cite_start]ExpoGeo is an interactive wiki-based portal designed to streamline expo management by centralizing schedules, exhibitor information, and event documentation[cite: 20, 22]. It serves as a comprehensive platform for attendees, organizers, and administrators to connect and manage event data efficiently.

![Project Screenshot](screenshots/1.png)

##  Prerequisites

Before you begin, ensure you have the following installed on your local machine:

* **XAMPP** (or WAMP/MAMP) for the local web server and database.
    * [Download XAMPP here](https://www.apachefriends.org/download.html)
* **Git** (optional, if cloning via terminal).

## ⚙️ Installation & Setup

Follow these steps to get the project running on your local machine:

### 1. Clone or Download the Repository
1.  Navigate to your XAMPP installation folder (usually `C:\xampp\htdocs`).
2.  Open your terminal in this folder.
3.  Clone the repository:
    ```bash
    git clone [https://github.com/DaranishT/ExpoGeo.git](https://github.com/DaranishT/ExpoGeo.git)
    ```
    *(Or simply download the ZIP file and extract it into the `htdocs` folder. Rename the folder to `ExpoGeo`.)*

### 2. Set Up the Database
1.  Open the **XAMPP Control Panel** and start **Apache** and **MySQL**.
2.  Go to your browser and navigate to `http://localhost/phpmyadmin`.
3.  Click **New** on the sidebar to create a new database.
4.  Name the database `expogeo_db` (or check your `config.php` file for the exact name).
5.  Click the **Import** tab at the top.
6.  Choose the `.sql` file provided in this repository (usually located in a `/database` or `/sql` folder).
7.  Click **Go** at the bottom to import the tables.

### 3. Configure the Project
1.  Open the project folder in your code editor.
2.  Locate the database configuration file (e.g., `config.php` or `db_connection.php`).
3.  Ensure the credentials match your XAMPP settings (default is usually user: `root`, password: empty).

### 4. Run the Project
1.  Open your web browser.
2.  Go to:
    ```
    http://localhost/ExpoGeo
    ```

## Screenshots

| Login Page | 

| ![Login Page](screenshots/5.png)

| Sign In Page| 

|![SignIn Page](screenshots/6.png)

| Dashboard |

![Dashboard](screenshots/2.png)
![Dashboard](screenshots/3.png)
![Dashboard](screenshots/4.png)
![Dashboard](screenshots/7.png)

##  Features

* [cite_start]**Interactive Wiki Portal:** Centralized hub for accessing expo schedules, exhibitor information, and comprehensive event documentation[cite: 22].
* [cite_start]**Role-Based Access Control (RBAC):** Secure authentication system separating **Attendees**, **Organizers**, and **Admins**, complete with specific CRUD functionality for each role[cite: 23].
* [cite_start]**Smart Itinerary & Reporting:** Built-in tools for personal itinerary planning, task tracking for organizers, and automated monthly downloadable reports[cite: 24].
* [cite_start]**Real-Time Communication:** Integrated web chat system enabling live communication between visitors and participants during events.

##  Contributing

1.  Fork the repository.
2.  Create a new branch (`git checkout -b feature-branch`).
3.  Commit your changes (`git commit -m 'Add new feature'`).
4.  Push to the branch (`git push origin feature-branch`).
5.  Open a Pull Request.

----------------------------------------
[T Daranish](https://github.com/DaranishT)
