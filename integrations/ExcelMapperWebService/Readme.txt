Files Used:


-integrations\ExcelMapperWebService\ExcelMapperWebService\Settings.cs

    File to define variables that can be sent during initial call and are sent back to the server

- integrations\ExcelMapperWebService\ExcelMapperWebService\appsettings.json
    
    Set postURL where files along withncommands will be sent post processing. (https://eos.eoxvantage.com:8090/pipeline)
    Define the file name for mapping file (Mapping.xlsm)

-integrations\ExcelMapperWebService\ExcelMapperWebService\Properties\launchSettings.json

    IISExpress Config
    Set address, port number, ssl config, environmentVariables
    Initial Launch URL
    Debug and Release build config


Deployment Help:

1) launchSettings.json set port and app url
2) Add Line which defines the project directory in ExcelMapperWebService\Controllers\FileUploadController.cs Line 56 (Inside 2nd If condition) 
         _environment.WebRootPath = "D:\\Oxzion";

    