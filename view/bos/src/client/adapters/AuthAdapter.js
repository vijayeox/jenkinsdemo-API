/*
For more information about authentication adapters, visit:
- https://manual.os-js.org/v3/tutorial/auth/
- https://manual.os-js.org/v3/guide/auth/
- https://manual.os-js.org/v3/development/
*/

import LocalStorageAdapter from './localStorageAdapter.js';


const loginAdapter = (core, config) => ({
  login: (req, res) => {

    const username = req.username;
    var lsHelper = new LocalStorageAdapter;
    var reqData = new FormData();
    reqData.append("username", username);
    reqData.append("password", req.password);
    // making request using the rest client
    var caller =  core.make('oxzion/restClient');
    return (async() => {
      var res = await caller.authenticate(reqData);
      if (res["status"] == "success") {
        if(lsHelper.supported() || lsHelper.cookieEnabled()){
          lsHelper.set('AUTH_token',res["data"]["jwt"]);
          lsHelper.set('User',username);
          return Promise.resolve({jwt:res["data"]["jwt"], username : username}); 
        } else {
          console.log('login failed.');
          return Promise.reject(new Error(res.message));
        }
      } else {
        return Promise.reject(new Error(res.message));
      }
    })();
    
  },

  logout: (req, res) => {
    var lsHelper = new LocalStorageAdapter;
    if(lsHelper.supported() || lsHelper.cookieEnabled()){
      lsHelper.purge('AUTH_token');
      lsHelper.purge('User');
      lsHelper.purge('UserInfo');
      return Promise.resolve(true); 
    }
  }
});

export default loginAdapter;