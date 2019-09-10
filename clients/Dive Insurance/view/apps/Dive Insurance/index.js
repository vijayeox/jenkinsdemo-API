import './index.scss';
import osjs from 'osjs';
import React from "react";
import ReactDOM from "react-dom";
import merge from "deepmerge";
import {name as applicationName,appId as application_id,formId} from './metadata.json';
import {LeftMenuTemplate,FormRender} from "./GUIComponents";
// Our launcher
const register = (core, args, options, metadata) => {
  // Create a new Application instance
  const proc = core.make('osjs/application', {args, options, metadata});
  const user = core.make('osjs/auth').user();
  let win = proc.createWindow({
    id: 'HubWindow',
    title: metadata.title.en_EN,
    dimension: {width: 400, height: 400},
    position: {left: 700, top: 200}
  }).on('destroy', () => proc.destroy());
  const getCacheData = async () => {
    let helper = core.make('oxzion/restClient');
    let cacheData = await helper.request('v1','/app/'+application_id+'/cache', {}, 'get' );
    return cacheData;
  };
  const getFormData = async (workflow_id) => {
    let helper = core.make('oxzion/restClient');
    let cacheData = await helper.request('v1','/app/'+application_id+'/workflow/'+workflow_id+'/startform', {}, 'get' );
    return cacheData;
  };
  let appContent = null;
  getCacheData().then(response => {
   if(response.data && response.data.length>0){
    getFormData(response.data.workflow_id).then(cacheResponse => {
      if(cacheResponse){
        win.render($content => ReactDOM.render(<div className='formContent'><FormRender core={core} page={response.data.page} appId={application_id} formId={cacheResponse.data[0].id} content={cacheResponse.data[0].content} data={response.data}/></div>, $content));
      }
      });
   } else {
    win.render($content => ReactDOM.render(<LeftMenuTemplate core={core} appId={application_id}/>, $content));
   }
 });
   return proc;
};

// Creates the internal callback function when OS.js launches an application
osjs.register(applicationName, register);
