## Movie API

Simple API for inserting, showing one or all, updating and deleting movie, person, role and movie details.

Create database movie-api.
From project root run ./setup.sh script, it will automatically migrate tables (first it will drop all tables if they exist), insert seed  
data and it will run all tests.

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

Persons endpoint: 
``` 
GET     /api/person
POST    /api/person
GET     /api/person/{id}
PUT     /api/person/{id}
DELETE  /api/person/{id}
```

Roles endpoint: 
``` 
GET     /api/roles
POST    /api/roles
GET     /api/roles/{id}
PUT     /api/roles/{id}
DELETE  /api/roles/{id}
```

Movie details endpoint
```
GET     /api/movie-details/{id} -> id of movie of which you want to see details
POST    /api/movie-details
PUT     /api/movie-details/{id}
DELETE  /api/movie-details/{id}
```