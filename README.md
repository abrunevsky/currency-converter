# Installation
1. Clone project from the repo to the selected directory:
   ```sh
   git clone https://github.com/abrunevsky/currency-converter.git ./
   ```
3. Prepare local `.env` file with command
  ```sh
  cp .env.dev .env
  ```
5. Build docker containers with command
   ```sh
   docker compose up -d --build &&\
      docker compose exec php composer install && \
      docker compose down
   ```

# Start / Stop project
Start with 
```sh
docker compose up -d
```

Stop with 
```sh
docker compose down
```

# Usage
Before the next steps make sure that the project is started.

## CLI commands
1. Get list of all available currencies:
   ```sh
   docker compose exec php bin/console app:convert:list
   ```
2. Convert some amount from sourse currency to target:
   ```sh
   docker compose exec php bin/console app:convert <source-iso> <source-amount> <target-iso>
   ```

## Http API endpoints
1. Get list of all available currencies:
   ```sh
   curl -s http://localhost:8000/currencies
   ```
   Returns JSON data map in a format like this:
   ```json
   {
     "AED": "UAE Dirham",
     "AMD":"Armenia Dram",
     "AUD":"Australian Dollar",
     ...
   }
   ```
3. Convert some amount from sourse currency to target:
   ```sh
   curl -s http://localhost:8000/convert/<source-iso>/<source-amount>/<target-iso>
   ```
   For example, the following GET request `http://localhost:8000/convert/EUR/10/BYN` could return this JSON response:
   ```json
   {
     "source": {
       "EUR": 10
     },
     "target": {
       "BYN": 34.7443
   }
   ```
