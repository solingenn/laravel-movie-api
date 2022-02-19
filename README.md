## Movie API

Simple API for inserting, showing all or one movie, updating and deleting movie.  

Create database ```movie-api```, set collation to ```utf8mb4_general_ci```.
From project root run ```./setup.sh``` script, it will automatically migrate tables (first it will drop all tables if they exist)  
and it will run all tests.

API Endpoints: 
``` 
GET     /api/movies  
POST    /api/movies  
GET     /api/movies/{id}  
PUT     /api/movies/{id}  
DELETE  /api/movies/{id}  
```
