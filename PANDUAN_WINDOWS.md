# Deploying PrintFlow on Windows (Office Hours)

Since you want to run this on a Windows computer during office hours (08:00 - 20:00) without losing data, follow these steps.

## 1. Prerequisites (Install Software)
You need **PHP** to run the application. The easiest way is to install **XAMPP**.
1.  Download **XAMPP** (with PHP 8.1 or higher) from [apachefriends.org](https://www.apachefriends.org/).
2.  Install it (default location is `C:\xampp`).

## 2. Moving the Project
1.  Copy your `PrintFlow` folder to `C:\xampp\htdocs\PrintFlow`.
2.  The folder structure should look like `C:\xampp\htdocs\PrintFlow\app`, `C:\xampp\htdocs\PrintFlow\public`, etc.

## 3. Configuring Database (CRITICAL)
Your current database path is set to a MacOS user path. You **MUST** change this for Windows to prevent errors and ensure data persists.

Open `app/Config/Database.php` and edit line 27:
```php
public array $default = [
    // ...
    // CHANGE THIS LINE:
    'database' => __DIR__ . '/../../writable/database.sqlite',
    // ...
];
```
*Note: Using `__DIR__ . '/../../writable/database.sqlite'` makes it relative, so it works anywhere!*

## 4. Running the Application
You have two options to run it.

### Option A: The "Start Script" (Recommended)
This is the easiest way. Create a file named `StartPrintFlow.bat` on your Desktop:

1.  Open Notepad.
2.  Paste this code:
    ```batch
    @echo off
    title PrintFlow Server
    echo Starting PrintFlow POS System...
    echo Do not close this window! Minimize it.
    cd C:\xampp\htdocs\PrintFlow
    start http://localhost:8080
    php spark serve --host 0.0.0.0 --port 8080
    pause
    ```
3.  Save it as `StartPrintFlow.bat`.

**Daily Routine (08:00 AM):**
- Double-click `StartPrintFlow.bat`.
- A black window will open (the server). **Do not close it**, just minimize it.
- Your browser will open the app automatically.

**Closing (20:00 PM):**
- Just close the black window. Your data is safe in `writable/database.sqlite`.

### Option B: Automatic Startup
If you want it to start automatically when the computer turns on:
1.  Press `Win + R`, type `shell:startup`, and press Enter.
2.  Copy your `StartPrintFlow.bat` into this folder.
3.  Now, whenever the PC is turned on, PrintFlow starts automatically.

## 5. Why Data Won't Be Lost?
We are using **SQLite**. The database is just a single file located at:
`C:\xampp\htdocs\PrintFlow\writable\database.sqlite`

As long as you **do not delete this file**, your data (transactions, products, users) is safe forever, even if you restart the computer 100 times.
