const path = require("path");
const fs = require("fs");
const React = require("react");
const ReactDOMServer = require("react-dom/server");
const Register = require("./public/register/Register");

module.exports = function(core, proc) {
  console.log("In proc resource");
  console.log(proc.resource("/register/:productName"));
  core.app.get(proc.resource("/register/:productName"), (req, res) => {
    console.log(req.params);
    console.log(req.params.productName);
     if (req.params.productName == 'independentContractor') {
      console.log("In HUB");
      var filePath = path.join(
        __dirname + "/public/register/independentContractorRegistration.json"
      );
      if (fs.existsSync(filePath)) {
        fs.readFile(filePath, function(err, buf) {
          if (err) {
            res.json({ error: "No template found" });
          }
          const params = {
            productName: req.params.productName,
            proc: proc,
            core: core,
          };
          const component = ReactDOMServer.renderToString(
            <Register {...params} />
          );
          const appId = proc.metadata.appId;
          const html = `<!DOCTYPE html>
          <html>
            <head>
              <title>HUB Company</title>
              <meta charSet="UTF-8" />
              <meta httpEquiv="X-UA-Compatible" content="IE=edge" />
              <meta name="viewport" content="width=device-width, initial-scale=1" />
              <link rel="stylesheet" type="text/css" href="../css/bootstrap.min.css">
              <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
              <link rel="stylesheet" type="text/css" href="../css/formio.full.min.css" />
              <link rel="stylesheet" type="text/css" href="../css/custom.css" />
              <script type="text/javascript">
                var formContent = JSON.stringify(${buf});
                var appId='${appId}';
                var productName='${req.params.productName}';
                var baseUrl="${core.config("api.url")}";
              </script>
            </head>
            <body>
              <div id="root">'${component}'</div>
              <script src="../js/country.js"></script>
              <script src="../js/phonelist.js"></script>
              <script src="../js/jquery.min.js"></script>
              <script src="../js/moment.js"></script>
              <script src="../js/moment-tz.js"></script>
              <script src="../js/formio.full.min.js"></script>
              <script src="../js/custom.js"></script>
              <script src="../js/sweetaleart.js"></script>
            </body>
          </html>
          `;
          res.send(html);
        });
      }
    }
  });
};