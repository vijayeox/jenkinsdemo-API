// Methods OS.js server requires

require('@babel/register')({
  ignore: [ /(node_modules)/ ],
  presets: ['@babel/env','@babel/react']
});
module.exports = (core, proc) => ({

// When server initializes
init: async () => {
  // HTTP Route example (see index.js)
  core.app.post(proc.resource('/test'), (req, res) => {
    res.json({hello: 'World'});
  });

  core.app.get(proc.resource("/success"), (req, res) => {
          const appId = proc.metadata.appId;
          const html = `<!DOCTYPE html>
          <html>
            <head>
              <title>HUB Company</title>
              <meta charSet="UTF-8" />
              <meta httpEquiv="X-UA-Compatible" content="IE=edge" />
              <meta name="viewport" content="width=device-width, initial-scale=1" />
            </head>
            <body>
              <h1> Payment Successful</h1>
              <a href='/'>Click here</a> to go back to the home page.
            </body>
          </html>
          `;
          res.send(html);
  });
  core.app.get(proc.resource("/failure"), (req, res) => {
    const appId = proc.metadata.appId;
    const html = `<!DOCTYPE html>
    <html>
      <head>
        <title>HUB Company</title>
        <meta charSet="UTF-8" />
        <meta httpEquiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />
      </head>
      <body>
        <h1> Payment Failed</h1>
        <a href='/'>Click here</a> to go back to the home page.
      </body>
    </html>
    `;
    res.send(html);
});
},

// When server starts
start: () => {},

// When server goes down
destroy: () => {},

// When using an internally bound websocket, messages comes here
onmessage: (ws, respond, args) => {
  respond('Pong');
}
});
