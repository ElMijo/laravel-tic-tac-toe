cp .travis/.env .env
cp .travis/phpunit.xml phpunit.xml
mkdir -p build/logs
docker-compose up -d
