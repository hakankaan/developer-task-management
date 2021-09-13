# Developer Task Assignment
Run the migrations with **php artisan migrate**
Command to fetch tasks from apis is **php artisan task:fetch**
To seed developer to database  **php artisan db:seed --class=DeveloperSeeder**

Start the backend server running **composer install** then **php artisan serve** 
Backend should be running on 8000 port or change the port value at *frontend/App.js*

To start front end project run **npm i**  then **npm start**
Browse http://localhost:3000/ 

Some rules was unclear for me -because of the weekend i couldn't reach you- considered like below.
- Developer with lower difficulty can't be assigned to higher difficulty job.
- If developer with higher difficulty assigned to lower difficulty job, duration doesn't change.
- Wrote the algorithm to handle the logic with more developers.

**If only 1x tasks are too much then other tasks assigning can take long time. For other scenarios job finishes in milliseconds.
