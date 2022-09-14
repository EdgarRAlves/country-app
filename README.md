# Country app

This app was originally created as a task for a job interview. The main goal was so show a list of data retrieved from an external API (https://restcountries.com/). I'm trying to improve the app on regular basis.

_**Technologies used**: Symfony Framework, Docker_

### Instructions on how to start the app:
1. Docker Engine and Docker Compose have to be installed on your local machine
2. Clone the repository
3. In the root directory, run: `docker-compose up -d --build`
4. Go to: http://localhost:8080/countries

### Main features:
1. List all countries with information about its population and region
2. Filter to only show countries from Europe
3. Filter to show only countries smaller in population than Lithuania
4. Sorting (ascending and descending) by population and region

### Things To Change:
1. /php folder should be /docker - it's common practice
2. Change Model to Service - since it's not Model - if i remember, on NSCM we probably had Models, but that was also wrong, so forget bad practices from GS :)
3. Go deeper with SOLID practices - currently in this project there is none - it's a mess :)
4. DRY, KISS also good to follow
