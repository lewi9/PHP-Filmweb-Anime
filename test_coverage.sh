export XDEBUG_MODE=develop,debug,coverage

cd project/

php vendor/bin/codecept run --coverage --coverage-xml --coverage-html

echo "\n\n Please wait a 10 seconds"
sleep 10s

google-chrome tests_codeception/_output/coverage/index.html
