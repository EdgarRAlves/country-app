# Country app

_**Built using the free API:** https://restcountries.com/_

_**Technologies used**: Symfony Framework, Docker_

### Instructions on how to start the app:
1. Install Docker in your local machine
2. Clone this repository
3. In the root directory, run: `docker-compose up -d --build`
4. Go to this URL: http://localhost:8080/countries

Access to the php container: `docker-compose exec php /bin/bash`

After entering php container, run php unit tests: `php bin/phpunit`

### Main features:
1. List all countries with information about its population and region
2. Filter to only show countries from Europe
3. Filter to show only countries smaller in population than Lithuania
4. Sorting (ascending and descending) by population and region

