## Movie API

Simple API for inserting, showing all or one movie or person, updating and deleting movie or person.

Create database movie-api, set collation to utf8mb4_general_ci.  
From project root run ./setup.sh script, it will automatically migrate tables (first it will drop all tables if they exist) and it will run all tests.

Before using API, user must be registered and signed in.
Received token in JSON response must be entered into Authorization->Bearer Token, otherwise endpoints will return ```Unauthorized``` message.

Register endpoint:
```
POST /api/register

Params:
name:User
email:user@gmail.com
password:secret
confirm_password:secret

JSON response example:
{
    "success": true,
    "data": {
        "token": "1|vWMTNLDQKz2o3rxaOyipaPmr2tjOvnF7nVGe9LFu",
        "name": "User"
    },
    "message": "User created successfully."
}
```

Login endpoint:
```
POST /api/login

Params:
email:user@gmail.com
password:secret

JSON response example:
{
    "success": true,
    "data": {
        "token": "2|6yXb7Oplytl45zdxP45u47FIan7EfHwWzr7HyNFy",
        "name": "User"
    },
    "message": "User signed in"
}
```

Movies endpoint: 
``` 
GET     /api/movies
POST    /api/movies
GET     /api/movies/{id}
PUT     /api/movies/{id}
DELETE  /api/movies/{id}
```

Person endpoint: 
``` 
GET     /api/person
POST    /api/person
GET     /api/person/{id}
PUT     /api/person/{id}
DELETE  /api/person/{id}
```