# WebCrawlerExample

This project is a web application built using Angular for the frontend and PHP for the backend. The PHP backend handles the data and the Angular frontend communicates with the backend via HTTP requests. This project displays a website where you see a table with all entries obtained from YCombinator. Following the table there's a list of buttons for filtering by points(where title has 5 or less words), comments(where title has more than 5 words), showing all, and switching between ascending or descending order. Below are the steps to set up and run the project.

## Prerequisites

Make sure you have the following software installed:

1. **Node.js**: v18 or above  
   [Download Node.js here](https://nodejs.org/) if you don't have it installed. Make sure to install Node.js and npm (which comes with Node.js).
   
   You can check your Node.js and npm versions by running:
   ```bash
   node -v
   npm -v
2. Angular CLI: You need to have the Angular CLI installed to run the Angular frontend. You can install it globally by running:
   ```bash
   npm install -g @angular/cli
4. PHP: You need PHP installed to run the PHP backend. You can check if you have it running:
   ```bash
   php -v
   ```
   Or download it here: [PHP Download](https://www.php.net/downloads.php)

##Setup

1. Start the backed php server
   open a command line in the backend folder and start the server like this:
   ```bash
   cd backend
   php -S localhost:8000
   ```
2. Start the Angular application by going into the frontend folder and serving the app like this:
   ```bash
   cd ../frontend
   npm install
   ng serve
   ```
3. Make sure you have both *locahost:4200 and *localhost:8000 currently running

##Project Structure
WebCrawlerExample/
│
├── Backend/        # PHP server code
│   └── endpoint.php  # PHP entry point
|   └── filter.php    # Filtering logic
|   └── crawler.php   # Request to get the website information and logic to process it
|   └── logger.php    # Helper to debug and log the backend functionality
|   └── log.txt       # Text log
│
├── Frontend/      # Angular frontend code
│   └── src/                     # Angular application source code
│       └── app/api.service.ts      # Logic for interacting with the backed
│       └── app/app.component.ts    # Main website logic
│       └── app/app.component.html  # Website structure for displaying the table and filtering options
│       └── app/app.component.scss  # Styling
│   └── .../         # Angular structure
└── README.md        # Project documentation
