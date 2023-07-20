<?php

use League\CommonMark\GithubFlavoredMarkdownConverter;

$content = <<<XML
# Web module final project
## What are we building here?
- We are going to create a website that displays a challenging question every 24 hours (The specific time that the question is shown is yet to be decided) that can be solved with the help of your programming knowledge.
- People who solve the question will be ranked on the order in which they finished the question (Therefore if you needed to be the first you should submit the question as soon as it appears on the website)
- Users will have to log in/Register with their GitHub accounts to provide an answer to the question that is displayed. (The programming language in which the user is writing the code will not matter as we will only evaluate the output of the code (The output of a program does not depend on the language that it is written in))
- About the pages of the platfrom (Will be updated keep in touch 😉)
<!-- Start of the table When updating search for here -->
| Page logical name | The route path | Description | Should the user be authenticated ? |
| :--- | :--- | :--- | :--- |
| Home page (Without Login) | `/` | This is the  page that is shown to the user when the user visits the website domain without loggin in | No |
| Question page | `/` => Logged In <br><br> `/question` => Login state does not matter | This page contains the question that is shown for today this is the default page if teh website is visited from a logged in users perspective. If there is not question to be shown today then it will display please come again tommorow| No |
| Leadeboard | `/leaderboard` | This page contains the leaderboard for the current question (Todays question) If there is no question available for the day fallback to the previous page or show some text indicating that there is no question for the day | No |
| | `/questions/*N*/leaderboard` | This page contains the leaderboard that was available to the given question number. Here N is the number of the question that the leaderboard needs to be shown.Say for example `questions/6/leaderboard` will show the leaderboard for the question number 6 | No |
| Old questions | `/questions` or `/calendar` | Contains all the old questions that have been previously display within our platfrom. If time permits this page will contain a pagination to view the questions back in history with better efficency | No |
| View old questions | `/questions/*N*` | Contains old questions that are provided in the platfrom. Here N is the question number of the question that needs to be viewed | No |
| About Us | `/about-us` | Contains details about our website, our team and our university | No |
| Contact US | `/contact-us` | Contains a from for users visiting our site to contact us (The dev team) for furthear feature additions, inquires and etc ... | No |
<!-- End of the table -->
## Why languages/frameworks are we using?
### Frontend
- HTML
- Javascript
- CSS
### Backend
- PHP (Considering Laravel)
## Where do we host it?
I am thinking of using Google Cloud Run by dockerizing the entire project will see more about that as we dive deep into this project
## Why do we need it?
Using a VCS like git allows us to easily work on projects like this and helps us collaborate. Most importantly using git will enable us to catch lazy people who don't contribute.
## Okay, How can I learn git ?
There are a few resources that I found useful listed below
- [What is git a very basic Intro](https://www.youtube.com/shorts/NwjYWvq3BMs)
- [Dive deep into git](https://youtu.be/gJv0PcfUXE8)
- [Learn hands-on about branches and pull requests](https://github.com/firstcontributions/first-contributions)
## What editor do I recommend to code on?
I would prefer If you could use a generally available and user-friendly editor such as vs code
### Install vs code on Windows
- Install the package manager choco if not installed
  - Open the PowerShell as administrator and paste the below command
  ```
  Set-ExecutionPolicy Bypass -Scope Process -Force; [System.Net.ServicePointManager]::SecurityProtocol = [System.Net.ServicePointManager]::SecurityProtocol -bor 3072; iex ((New-Object System.Net.WebClient).DownloadString('https://community.chocolatey.org/install.ps1'))
  ```
- Close the Powershell window and open another Powershell window as the administrator
- On that PowerShell window run the following command
```
choco install vscode
```
  - Now close the PowerShell window as vs code is properly installed
### Install vscode on macos
- Install the [homebrew](https://brew.sh/) package manager if not installed
- Install vs code with homebrew
  ```
  brew install --cask visual-studio-code
  ```
- Now vs code is installed
### Install vs code in Linux 😎
- You know the drill install vs code with your package manager of choice
XML;
$output = (new GithubFlavoredMarkdownConverter())->convert($content)->getContent();

echo $output;
