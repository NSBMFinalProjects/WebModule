# Web module final project

## What are we building here?

- We are going to create a website that displays a challenging question every 24 hours (The specific time that the question is shown is yet to be decided) that can be solved with the help of your programming knowledge.

- People who solve the question will be ranked on the order in which they finished the question (Therefore if you needed to be the first you should submit the question as soon as it appears on the website)

- Users will have to log in/Register with their GitHub accounts to provide an answer to the question that is displayed. (The programming language in which the user is writing the code will not matter as we will only evaluate the output of the code (The output of a program does not depend on the language that it is written in))

- About the pages of the platfrom (Will be updated keep in touch 😉)
| Page logical name | The route path | Description | Should the user be authenticated ? |
| --- | --- | --- | --- |
| Home page (Without login) | / | This is the page that is shown to the user if the user is not logged in when the user enters the domain of the website | No |
| Question pag (With or without login) | / → If Logged in
/question → If not logged in or not (Does not really matter) | This page contains the current question that is availabel for today with the relevant countdown and the with the ability to pick the correct answer from the list of answers | No |
| Leaderboard | /leaderboard | This page contains the leaderboard for the current question. The user muse not be logged in to view this page. If there is no question to show for today then fallback to the previous question | No |
|  | /questions/N/leaderboard | This shows the leaderboard for the given question number (The question number is denoted by N ). Say for example if the user wants to view the question number 6 then the path that the user should visit will be denoted as /questions/6/leaderboard | No |
| Old questions | /questions or /calendar | This route shows the user all the previous questions that are provided within the platfrom | No |
| View Old question | /questions/N | N is the number of the question that you need to watch | No |
| About US | /about | Contains the about us of the dev team and out university | No |
| Contact US | /contactus | Contains the contact us page. This page allows users to inquiry the dev team for further clarifications about the platform or to provide some meaningful suggestions | No |

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
  


