import './index.scss';
import osjs from 'osjs';
import React from "react";
import ReactDOM from "react-dom";
import merge from "deepmerge";
import {name as applicationName,appId as application_id} from './metadata.json';
import LeftMenuTemplate from "../../../../../view/gui/src/leftmenutemplate.js";
// let menus = [];
// Our launcher
const register = (core, args, options, metadata) => {
  // Create a new Application instance
  const proc = core.make('osjs/application', {args, options, metadata});

//   const getMenulist = async () => {
//     let helper = core.make('oxzion/restClient');
//     let menulist = await helper.request('v1','/app/'+application_id+'/menu', {}, 'get' );
//     return menulist;
// };

// getMenulist().then(response => {
//  menus = response["data"];
//  console.log("Menuss");
//   console.log(menus);
// });
// console.log("Menus List");
// console.log(menus);
  // Create  a new Window instance
  proc.createWindow({
    id: 'HubWindow',
    title: metadata.title.en_EN,
    dimension: {width: 400, height: 400},
    position: {left: 700, top: 200}
  })
    .on('destroy', () => proc.destroy())
    .render($content => ReactDOM.render(<LeftMenuTemplate args={core} appId={application_id}/>, $content));

   return proc;
};

// Creates the internal callback function when OS.js launches an application
osjs.register(applicationName, register);
