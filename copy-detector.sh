#!/bin/bash

cd project/
vendor/bin/phpcpd . --fuzzy --min-lines 1 --min-tokens 20 --exclude vendor --exclude tests* --exclude config --exclude storage

