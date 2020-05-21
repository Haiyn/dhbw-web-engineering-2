rm -rf src/resources/assets
mkdir src/resources/assets
cp -R vendor/twbs/bootstrap/dist src/resources/assets/bootstrap
mkdir src/resources/assets/jquery
cp -R vendor/components/jquery/*.js src/resources/assets/jquery