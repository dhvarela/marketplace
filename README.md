## MarketPlace

Marketplace is a shopping cart project that allows you to add products to a Cart.
Products may have offers according to the units added.
 
The project uses Alphavantage (https://www.alphavantage.co) as a Currency Exchange third party api to allow the currency conversions.

The Alphavantage api endpoints are cached by php-vcr package. 
So, the first time you run the tests, api calls are executed and it costs some extra milliseconds. The next times you run the tests, vcr will get responses from /test/fixtures cached files and it will cost no extra time. 


## Get started

##### Clone the project

    $ git clone https://github.com/dhvarela/marketplace.git
    $ cd marketplace/
    
##### Environment configuration 

i) Copy the default environment variables:

    $ cp .env.template .env
    
ii) Fill ALPHAVANTAGE_KEY variable with your Alphavantage free api key.
You can get an Alphavantage free api key from https://www.alphavantage.co/support/#api-key

iii) Add marketplace.local domain to your local hosts:
 
    $ echo "127.0.0.1 marketplace.local"| sudo tee -a /etc/hosts > /dev/null

##### Run docker-compose to execute all the configurations

    $ docker-compose build

##### Up docker-compose to launch all the containers

    $ docker-compose up -d

##### Pass the composer in container

    $ docker exec -it marketplace_php bash
    $ cd marketplace/
    $ composer install
    
##### Execute PHPUnit tests

    $ bin/phpunit
    
_NOTE: You can ensure that application is up visiting the local project http://marketplace.local:8080_    