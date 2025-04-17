#!/bin/bash

apache2ctl start &

cd /app/server

php Main.php

wait -n