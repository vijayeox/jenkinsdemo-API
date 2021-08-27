1. Create a copy of accInfo.json.sample with the file name accInfo.json
2. Update the same file with your FTP credentials
3. Run npm install on the folder
4. Everytime you want to fetch files run the command -> node . [ARGUMENTS]

Available configurations:

node .                     --  Creates full clone of the ftp server with the exact file structure

node . clone [DATE-FORMAT] -- Creates full clone and puts the files in a folder with the given date format.

node . folder [PATH]       -- Gets only the files within the given server folder path

node . fileList            -- Fetches all the files in the server in puts them in the current DIR without creating any folders