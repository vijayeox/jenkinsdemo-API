#!/bin/bash

oxhome=$(pwd)

echo "Building IdentityService"
cd ${oxhome}/IdentityService
./gradlew build

echo "Building ProcessEngine"
cd ${oxhome}/ProcessEngine
./gradlew build
