# script to package oxzion3.0 to production build
#!/bin/sh

#making mkdir function
make_directories()
{
    #making directories for the build
    echo "Creating directory build with subfolders..."
    mkdir -p build/api/v1
    mkdir -p build/view
    mkdir -p build/integrations/mattermost
    mkdir -p build/integrations/rainloop
    mkdir -p build/integrations/camel
    mkdir -p build/integrations/orocrm
    mkdir -p build/integrations/eventcalendar 
}
#checking if build dir exists, if yes deleting it
if [ -d "./build" ];
then
    echo "Directory build exist!"
    echo "Deleting existing build folder to avoid conflict..."
    rm -Rf build
if
#calling make directory
make_directories

##################################################################
#copying the required files for build

#copy contents of ap1v1 to build
echo "Copying Api/v1...."
cp -R api/v1 build/api/
echo "Copying Completed!"
#copy contents of view to build
echo "Copying View. Please wait this may take sometime...."
cp -R view build/
echo "Copying View Completed!"
#copying contents of orocrm and calendar
echo "Copying Orocrm...."
cp -R ./integrations/orocrm ./build/integrations/
echo "Copying Completed!"
echo ""
echo "Copying Calendar...."
cp -R ./integrations/eventcalendar ./build/integrations/
echo "Copying Calendar Completed!"
#performing builds
cd build/api/v1
#building api_v1
echo "Building API...."
docker run -t -v ${PWD}:/var/www v1_zf composer update
cd ../../view
echo "Building API Completed!"
#building UI/view folder
echo "Build UI/view"
docker run -t -v ${PWD}:/app -p 8081:8081 view ./build.sh
#oxzion3.0/integrations/mattermost
cd ../../integrations/mattermost
echo "Building UI/view Completed!"
echo ""
echo "Building Integration Mattermost..."
docker run -t --network="host" -v ${PWD}:/mattermost --entrypoint ./docker-build.sh mchat
# unzip of the tar.gz file to build/integrations/mattermost
echo "Building Mattermost Completed!"
echo ""
echo "Copying Mattermost"
tar xvzf ./mattermost-server/dist/mattermost-team-linux-amd64.tar.gz -C ../../build/integrations
echo "Copying Mattermost Completed!"
#building rainloop
echo "Building Rainloop"
cd ../rainloop
npm install
npm audit fix
npm update
gulp rainloop:start
#copying contents of src folder to build/integrations/rainloop
echo "Building Rainloop Completed!"
echo ""
echo "Copying Rainloop..."
cp -R ./build/dist/releases/webmail/1.12.1/src/* ../../build/integrations/rainloop/
echo "Copying Completed!"
#building camel
cd ../camel
echo "Building Camel"
docker run --network="host" -t -v ${PWD}:/workspace/app --entrypoint ./docker-build.sh camel
echo "Building Camel Completed!"
echo ""
echo "Copying Camel"
cp ./build/libs/app-0.0.1-SNAPSHOT.jar ../../build/integrations/camel/camel.jar
echo "Copying Camel completed!"
echo ""
echo "Building orocrm"
cd ../../build/integrations/orocrm
composer update
echo ""
echo "Building orocrm Completed!"
#going back to /build directory
cd ../../
# zip the contents of the build folder excluding node_modules
echo "Packaging /build to build.zip"
if [ -d "../build.zip" ];
then
    rm -y ../build.zip
fi
zip -r ../build.zip . -x *node_modules/\*
echo "Packaging Complete :)"
