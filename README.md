# BidAuction

##Setup
###Need to run: docker-compose up --build

##Test
In folder /tests there is one test with prepared data for bid Auction.

* Reserve price is 100 euros
* Buyer A - 110, 130 euros
* Buyer B - 0 euros
* Buyer C - 125 euros
* Buyer D - 105, 115, 90 euros
* Buyer E - 132, 135, 140 euros

Expected winner is Buyer E, with winning price 130 euros
###Run test: docker exec -it bidauction-app-1 php bin/phpunit

##Symfony Command
###Run command: docker exec -it bidauction-app-1  php bin/console app:run-auction

Here you can test winning calculation algorithm where you will be asked to set number of buyers and their bids.
