# CPSC 431 Project - mini drive
## Authors
- RJ Andaya | rjandaya98@csu.fullerton.edu
- Adrian Lawson | adrianboblawson@csu.fullerton.edu

## Introduction
mini drive is a private cloud storage application which allows users to easily access and manage their personal files

## How to Use the Application
1. Log in with one of the premade logins
    * Username: user | Password: password
    * Username: | Password:


## Use Cases
1. File Upload
    * Actors: User (primary)
    * Description: The user will add a new file to the database. The application will store the details about the file
      (file name, file type, file size, date added) to the database. 
2. File Viewing and Retrieval
    * Actors: User (primary)
    * Description: Once they have logged in, the user will be able to view the files they have uploaded. They will also 
      be able to download any files they have uploaded.
3. File Deletion
    * Actors: User (primary)
    * Description: The user will be able to select as many of their files as desired and be able to delete them. 
4. File Search
    * Actors: User (primary)
    * Description: The user will be able to select a search criteria (file name, file type, date added, file size) and enter
      a search query. For example, if searching by file type, the user will select file type and type in a file extension, such as pdf. 
      Then only files that are pdf files will be displayed. 

## Contribution Breakdown
    * File uploads: Adrian Lawson
    * Database access: Adrian Lawson
    * Access control: RJ Andaya
    * Session handling: RJ Andaya
    * Input validation: RJ Andaya and Adrian Lawson

## Note
    * Initial test uploads were appended with the default date_default_timezone_set()
