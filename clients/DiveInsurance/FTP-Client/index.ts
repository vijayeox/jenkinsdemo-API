const ftp = require("basic-ftp");
var moment = require("moment");
var accInfo = require("./accInfo.json");

fetchPADI();

async function fetchPADI() {
  const client = new ftp.Client();
  client.ftp.verbose = true;
  try {
    await client.access({
      host: accInfo.host,
      user: accInfo.user,
      password: accInfo.password,
      secure: true,
      secureOptions: { rejectUnauthorized: false },
    });
    client.trackProgress();

    switch (process.argv[2]) {
      case "clone":
        await client.downloadToDir(
          moment().format(process.argv[3] ? process.argv[3] : "MMMM Do YYYY")
        );
        break;
      case "folder":
        await fetchFiles(client, process.argv[3]);
        break;
      case "fileList":
        await fetchFiles(client, null);
        break;
      default:
        await client.downloadToDir(moment().format("MMMM Do YYYY"));
        break;
    }
  } catch (err) {
    console.log(err);
  }
  client.close();
}

async function fetchFiles(client, path) {
  var folderArray = Array();
  path ? await client.cd("/" + path) : null;

  var fileList = await client.list();
  for (let index = 0; index < fileList.length; index++) {
    if (fileList[index].type == 1) {
      await client.downloadTo(fileList[index].name, fileList[index].name);
    } else {
      folderArray.push(fileList[index]);
    }
  }
  folderArray.length > 0 ? await fetchFiles(client, folderArray[0].name) : null;
}
