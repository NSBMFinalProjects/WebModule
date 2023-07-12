# Web module final project

## What are we building here ?

- We are going to create a website that displays a challenging question every 24 hours (The specific time that the question is shown is yet to be decided) that can be solved by the help of your programming knowledge.

- People who solve the question will be ranked on the order in which they finished the question (Therefore if you needed to be the first you should submit the question as soon as it appears on the website)

- Users will have to Login/Register with their GitHub accounts to provide an answer to the question that is displayed. (The programming language in which the users is writing the code on will not matter as we will only evaluate the output of the code (The output of a program does not depend on the language that it is written in))

- There will be atleast 7 pages in this website and they will be updated below as we understand more and more on what our end product will be
  - Home page
    This page will only be shown to the user if only they are not logged in

  - Question page
    This page will be shown to the user if and only if the user is logged in

  - Contact Us page
    This page will be used by the end users of our site to contact us to get more information about the site or provide some meaningful insights related to out site

  - About us page
    This page wil be used to display information about us the devs who worked really hard to make this site possible

  - Old questions
    This page is used to show the users the old questions along side with its answer

  - Old Questions leaderboard
    This page will show the leaderboard of the selected old question
      For example say that the user selected yesterdays question then this page will show the leader board of the users who submited the yesterdays questions first

## Why languages / frameworks are we using ?

### Frontend

- HTML
- Javascript
- CSS

### Backend

- PHP (Considering about Laravel)

## Where do we host it ?

I am thinking of using Google Cloud Run by dockerizing the entire project will see more about that as we dive deep into this project

## Why do we need git ?

Using a VCS like git allows us to work on projects like this with ease and helps us to collobarate. Most importantly using git allows us to catch lazy people who doesnt contribute in their own act

## Okay, How can I learn git ?

There are few resources that I found useful listed below

- [What is git a very basic Intro](https://www.youtube.com/shorts/NwjYWvq3BMs)
- [Dive deep into git](https://youtu.be/gJv0PcfUXE8)
- [Learn hands on about branches and pull requests](https://github.com/firstcontributions/first-contributions)

## What editor do I recommend to code on ?

I would prefer If you could use a generally available and user friendly editor such as vscode

### Install vscode on windows

- Install the package manager choco if not installed

  - Open the powershell as administrator and paste the below command
  ```
  Set-ExecutionPolicy Bypass -Scope Process -Force; [System.Net.ServicePointManager]::SecurityProtocol = [System.Net.ServicePointManager]::SecurityProtocol -bor 3072; iex ((New-Object System.Net.WebClient).DownloadString('https://community.chocolatey.org/install.ps1'))
  ```

- Close the powershell window and open another powershell window as the administrator

- On that powershell window run the following command
```
choco install vscode
```

  - Now close the powershell window as vscode is properly installed

### Install vscode on macos

- Install the [homebrew](https://brew.sh/) package mananger if not installed

- Install vscode with homebrew
  ```
  brew install --cask visual-studio-code
  ```

- Now vscode is installed

### Install vscode in Linux 😎

- You know the drill install vscode with your package manager of choice
  


