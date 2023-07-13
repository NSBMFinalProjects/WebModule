# Web module final project

## What are we building here?

- We are going to create a website that displays a challenging question every 24 hours (The specific time that the question is shown is yet to be decided) that can be solved with the help of your programming knowledge.

- People who solve the question will be ranked on the order in which they finished the question (Therefore if you needed to be the first you should submit the question as soon as it appears on the website)

- Users will have to log in/Register with their GitHub accounts to provide an answer to the question that is displayed. (The programming language in which the user is writing the code will not matter as we will only evaluate the output of the code (The output of a program does not depend on the language that it is written in))

- There will be at least 7 pages on this website and they will be updated below as we understand more and more about what our end product will be
  - Home page
    - This page will only be shown to the user if only they are not logged in

  - Question page
    - This page will be shown to the user if and only if the user is logged in

  - Contact Us page
    - The end users of our site will use this page to contact us to get more information about the site or provide some meaningful insights related to our site

  - About Us page
    - This page will be used to display information about us the devs who worked hard to make this site possible

  - Old questions
    - This page is used to show the users the old questions alongside their answer

  - Old Questions leaderboard
    - This page will show the leaderboard of the selected old question
      For example, say that the user selected yesterday's question then this page will show the leader board of the users who submitted yesterday's questions first

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
  


