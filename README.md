# Installation
1. Copy file `/.env.dev` to `/.env`
2. Build docker containers with command
   ```sh
   docker compose build
   ```

# Start / Stop services (`dev` mode only)
Start with 
```sh
docker compose up -d
```

Stop with 
```sh
docker compose down
```

# Usage
Before the next steps make sure that docker services are started.

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
   ```
   GET http://localhost:8000/currencies
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
   ```
   GET http://localhost:8000/convert/<source-iso>/<source-amount>/<target-iso>
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
