## Movie API

Simple API for inserting, showing one or all, updating and deleting movie, person, role and movie details.

Create database movie-api.  
From project root run ./setup.sh script, it will automatically migrate tables (first it will drop all tables if they exist), insert seed 
data and it will run all tests.

Before using API, user must be registered and signed in.  
Received token in JSON response must be entered into Authorization->Bearer Token, otherwise endpoints will return ```Unauthorized``` message.

Postman documentation;  
[API postman documentation](https://documenter.getpostman.com/view/4845924/UVyoVdQo) 
