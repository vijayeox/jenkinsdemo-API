import osjs from "osjs";
import { name as applicationName, icon_white } from "./metadata.json";
import React from "react";
import ReactDOM from "react-dom";
import Home from "./home";

// Our launcher
const register = (core, args, options, metadata) => {
  // Create a new Application instance
  const proc = core.make("osjs/application", { args, options, metadata });
  proc
    .createWindow({
      id: "HubWindow",
      title: metadata.title.en_EN,
      icon: proc.resource(icon_white),
      dimension: {
        width: document.body.clientWidth,
        height: document.body.clientHeight
      },
      state: {
        maximized: true 
      },
      attributes: {
        visibility: "restricted",
        controls: false
      }
    })
    .on("destroy", () => proc.destroy())
    .render($content => ReactDOM.render(<Home args={core} params={args} />, $content));

  return proc;
};

// Creates the internal callback function when OS.js launches an application
osjs.register(applicationName, register);
