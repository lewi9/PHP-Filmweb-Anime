export XDEBUG_MODE=develop,debug,coverage

cd project/
mkdir -p tests_codeception/_output/c3tmp
touch tests_codeception/_output/c3tmp/codecoverage.serialized

php vendor/bin/codecept run --coverage --coverage-xml --coverage-html

echo "\n\n Please wait a 10 seconds"
sleep 10s

google-chrome tests_codeception/_output/coverage/index.html
