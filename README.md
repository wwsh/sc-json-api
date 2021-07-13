# Shoutcast JSON API interface

A small proxy system, translating and aggregating SHOUTcast V2 metadata into a database, serving Datasource endpoints for quick data retrieval.

## Input side

It can be any SHOUTcast source, like Winamp or Traktor. Connect your source to the proxy server at 2640 (default) and start sending metadata.

## Middle man

To some degree, the incoming data is matched against Discogs public data. Your metadata should be kept clean and ascetic, in order to work with the Discogs search engine.

## Output side

The app serves several endpoints to retrieve the metadata. They work regardless of stream or Discogs status. You can ALWAYS fetch latest metadata in little time (high availability).

    http://scjsonapi.localhost:2630/get/history
    http://scjsonapi.localhost:2630/get/title
    http://scjsonapi.localhost:2630/get/artist
    http://scjsonapi.localhost:2630/get/label?uppercase=1
    http://scjsonapi.localhost:2630/get/year

## The requirements

You will need to configure the `.env` 

Get a Discogs token from your Discogs settings.

## Code

Take a look at the code to find out what's more in the box.

There are no tests. You can write some :)

Feel free to commit whatever you think is best, without breaking the compatibility.

## Runtime

    docker-compose build
    docker-compose up -d
    docker-compose exec php composer install
    docker-compose exec php bin/console doctrine:database:create
    docker-compose exec php bin/console doctrine:mig:migrate
    
And there you go.
