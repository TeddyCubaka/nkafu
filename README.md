# <center> TAC LIBOKE </center>

TAC LIBOKE is a micro-framework created by TRANS-ACADEMIA to meet their internet needs in order to better manage their backend applications.

So, if you get it, here the way to install and run the app.

## installation and config

Firt, got to offical repository of this project in the workspace of trans-academia on github. Make sûre you have enough autorisation to access the repository.

### requirement

Before to use this project, make sure you have these app on your machine :

+ git
+ php, the latest version, (with apache, xampp, lampp, mampp or other)
+ a database (`mysql` preferably). Because, the database connection is make for mysql. You can also modify it in database connection file.
+ composer

### installation

So, if you can access the repository, then copy the the https or ssh url of the app to clone it with git.

To download the app you can use to way. Either you download the app as a zip file or you git to continue.

If you choose to use git, there is the way to use :

1. First open your terminal in the folder you want the app to be. In your terminal :

```bash
# using ssh url
~ git clone git@github.com:Trans-academia/TAC-LIBOKE.git

# using http url
~ git clone https://github.com/Trans-academia/TAC-LIBOKE.git
```

2. After cloning the app you must install all dependacies wich are in the composer.json file of the app. To do that, do :

```bash
~ composer install
```

Is the installation finished ? Congrate ! you installed the app.

### configuration

1. Look in the app folder, you'll see the file named `.env.example`. If you often use the environment variable, you know what is that. If you have no ideas of what is that, I'll explain it shortly. It used as reffenrency file for the .env file for environment variable.

`note` : An environment variable in a programming project is a dynamic value that can be accessed by the software during runtime. It is a key-value pair that holds information about the operating system, server configuration, or custom settings.

So, you must create a new file named `.env` to let you app know where is environement variable. And then create all requested variable which are in the `.env.example` file. You'll find a mini doc in it.

### start the app

First, make sure you mysql server is on. If okay, open you project in your terminal and do :

```bash
~ cd public/
```

after :

```bash
~ (public) » php -S localhost:8080
```

Note that you can change the app port.

The project will be listen on the port 8080 or at htpp://localhost:8080 

You will find the developpement doc in the folder document/.

Thank you.
