#!/bin/sh
cd ../view
echo "Copying .env.example to .env in all the apps"
cp -n apps/Calendar/.env.example apps/Calendar/.env
cp -n apps/Chat/.env.example apps/Chat/.env
cp -n apps/CRM/.env.example apps/CRM/.env
cp -n apps/Mail/.env.example apps/Mail/.env
cp -n apps/MailAdmin/.env.example apps/MailAdmin/.env
cp -n apps/Task/.env.example apps/Task/.env 
sleep 1
echo "Copy Complete!"

echo "Copying local.js.example to local.js in bos/src/client"
cp -n bos/src/client/local.js.example bos/src/client/local.js
sleep 1
echo "Copy Complete"

echo "Copying local.js.example to local.js in bos/src/server"
cp -n bos/src/server/local.js.example bos/src/server/local.js
sleep 1
echo "Copy Complete"

echo "Copying .env.example to .env in bos/src/osjs-server"
cp -n bos/src/osjs-server/.env.example bos/src/osjs-server/.env
sleep 1
echo "Copy Complete"


echo "Starting build for for oxzion3.0"
sleep 1
./clean.sh
./build.sh
echo "Build Complete! To start server do 'npm run serve'"
