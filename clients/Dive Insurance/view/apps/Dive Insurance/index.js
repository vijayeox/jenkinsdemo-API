import './index.scss';
import osjs from 'osjs';
import React from "react";
import ReactDOM from "react-dom";
import merge from "deepmerge";
import {name as applicationName,appId as application_id,formId} from './metadata.json';
import {LeftMenuTemplate,FormRender} from "@oxzion/gui";
// Our launcher
const register = (core, args, options, metadata) => {
  // Create a new Application instance
  const proc = core.make('osjs/application', {args, options, metadata});
  const user = core.make('osjs/auth').user();
  let win = proc.createWindow({
    id: 'HubWindow',
    title: metadata.title.en_EN,
    dimension: {width: document.body.clientWidth, height: document.body.clientHeight},
    state: {
      maximized : true
    },
    position: {left: 700, top: 200}
  }).on('destroy', () => proc.destroy());
  var cacheId;
  const getCacheData = async () => {
    let helper = core.make('oxzion/restClient');
    let cacheData = await helper.request('v1','/app/'+application_id+'/cache', {}, 'get' );
    return cacheData;
  };
  const updateCacheData = async (cacheId) => {
    let helper = core.make('oxzion/restClient');
    let cacheData = await helper.request('v1','/app/'+application_id+'/cache'+cacheId, {}, 'delete' );
    return cacheData;
  };
  const getFormData = async (workflow_id) => {
    let helper = core.make('oxzion/restClient');
    let cacheData = await helper.request('v1','/app/'+application_id+'/workflow/'+workflow_id+'/startform', {}, 'get' );
    return cacheData;
  };
  const getTestData = async () => {
    const response = await proc.request('/test', {method: 'post'});
    console.log(response);
  };
  getTestData();
  const postSubmitCallback= (data) => {
    console.log(data);
    // if(cacheId){
    //   win.render($content => ReactDOM.render(<LeftMenuTemplate core={core} appId={application_id}/>, $content));
    //   return proc;
    // }
  }
  // Creates a HTTP call (see server.js)
  proc.request('/test', {method: 'post'})
  .then(response => console.log(response));
  getCacheData().then(cacheResponse => {
   if(cacheResponse.data && cacheResponse.data.workflow_id){
    getFormData(cacheResponse.data.workflow_id).then(formResponse => {
      if(formResponse && formResponse.data.length > 0){
        cacheId = cacheResponse['id'];
        win.render($content => ReactDOM.render(<div className='formContent'><FormRender postSubmitCallback={postSubmitCallback} core={core} page={cacheResponse.data.page} appId={application_id} formId={formResponse.data[0].id} content={JSON.parse(formResponse.data[0].content)} data={cacheResponse.data}/></div>, $content));
      } else {
        win.render($content => ReactDOM.render(<LeftMenuTemplate core={core} appId={application_id}/>, $content));
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
