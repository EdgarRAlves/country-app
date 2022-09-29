# Country app

This app was originally created as a task for a job interview. The main goal was so show a list of data retrieved from an external API (https://restcountries.com/). I'm trying to improve the app on regular basis.

### Instructions on how to start the app:
1. Docker Engine and Docker Compose have to be installed on your local machine
2. Clone the repository
3. In the root directory, run: `docker-compose up -d --build`
4. Go to: http://localhost:8080/countries

### Main features:
1. List all countries with information about its population and region
2. Filter by region
3. Filter countries smaller in population than selected country
4. Sort by population and region