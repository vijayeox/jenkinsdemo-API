~ Nikhil

/integrations/workflow/ProcessEngine
sh ./gradlew build

copy from /integrations/workflow/ProcessEngine/build/libs/processengine_plugin-1.0.jar  to /integrations/workflow/ProcessEngine/dist
rename processengine_plugin-1.0.jar to processengine_plugin.jar

api/v1/lib/Oxzion/src/Workflow/Camunda/Config.php
change to local ip (ifconfig cmd)

Create .env file in integrations/workflow/ 
copy paste from .env.sample
config process-engine, oxzionapi in .env file (db name, user, password)

run workflow docker (/workflow/readme)
docker build -t workflow .
docker run --network="host" -it --env-file .env workflow

simlink delegate folder
cd app/api/v1/data/delegate
ln -s ../../../../clients/DiveInsurence/data/delegate/ ./appID (DiveInsurance appId get it from DB (ox_app))
run query from migrations folder in diveInsurance/data/migrations
