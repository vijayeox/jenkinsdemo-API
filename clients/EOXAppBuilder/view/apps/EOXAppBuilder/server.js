const fs = require('fs');
const express = require('express');
const path = require('path');
const React = require('react');
const ReactDOMServer = require('react-dom/server');
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
    const app = express()
    core.app.use('/public', express.static(path.resolve(__dirname, 'public')));
    // WebSocket Route example (see index.js)
    core.app.ws(proc.resource('/socket'), (ws, req) => {
      ws.send('Hello World');
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
