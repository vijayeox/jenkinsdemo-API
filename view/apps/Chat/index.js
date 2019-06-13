/*
* OS.js - JavaScript Cloud/Web Desktop Platform
*
* Copyright (c) 2011-2018, Anders Evenrud <andersevenrud@gmail.com>
* All rights reserved.
*
* Redistribution and use in source and binary forms, with or without
* modification, are permitted provided that the following conditions are met:
*
* 1. Redistributions of source code must retain the above copyright notice, this
*    list of conditions and the following disclaimer
* 2. Redistributions in binary form must reproduce the above copyright notice,
*    this list of conditions and the following disclaimer in the documentation
*    and/or other materials provided with the distribution
*
* THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS" AND
* ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED
* WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE
* DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT OWNER OR CONTRIBUTORS BE LIABLE FOR
* ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES
* (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES;
  * LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND
  * ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
  * (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS
  * SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
  *
  * @author  Anders Evenrud <andersevenrud@gmail.com>
  * @licence Simplified BSD License
  */
  import {name as applicationName} from './metadata.json';
  const baseUrl = process.env.SERVER;

  const trayOptions = {};
  let chatCount = 0;
  let tray = null;

  const resetBadge = () => {
    if(trayOptions.count > 0){
      chatCount = "";
      trayOptions.count = chatCount;
      trayOptions.badge = "";
      tray.update(trayOptions);
    }
  };

  // To clear Client-side cookie
  const clearClientCookie = () => {
    document.cookie = 'MMUSERID=;expires=Thu, 01 Jan 1970 00:00:01 GMT;path=/';
    document.cookie = `MMUSERID=;expires=Thu, 01 Jan 1970 00:00:01 GMT;domain=${window.location.hostname};path=/`;
  };

  // Chat Header Icons are interchanged purposefully. Do not change this.
  const HeaderIcon = () => {
  let parent = document.querySelectorAll(".osjs-window[data-id=ChatWindow] div.osjs-window-header")[0];
  if(parent.childNodes[2].getAttribute('data-action') == 'minimize'){
    let maximize = parent.insertBefore(parent.childNodes[3],parent.childNodes[2]);
    }
  }
  
  const createIframe = (core, proc, win, cb) => {
    const iframe = document.createElement('iframe');
    iframe.style.width = '100%';
    iframe.style.height = '100%';
    iframe.setAttribute('border', '0');
    
    iframe.addEventListener('load', () => {
      
      const ref = iframe.contentWindow;
      const handleNotification = () => {
               
        if(win.state.focused == false || win.state.minimized == true){
          chatCount++;
          chatCount = chatCount > 0 ? (chatCount) : "";
          trayOptions.badge = 'badgeCheck';
          trayOptions.count= chatCount;          
        }
        
        tray.update(trayOptions);
       };
      
      // This will proxy the window focus events to iframe
      win.on('focus', () => {
        ref.focus();
        resetBadge();
      });

      core.on('osjs/core:logged-out', () => {
        clearClientCookie();
      });
      
      win.on('blur', () => ref.blur());
      win.on('iframe:post', msg => ref.postMessage(msg, baseUrl));
      win.on('iframe:get', msg => {
        console.warn('Message from Iframe', msg);
        switch(msg){
          case 'Ping':
          win.emit('iframe:post', 'Pong');
          break;
          case 'Notify':
          handleNotification();  
          break;
        }
        
      });
      
      // After connection is established, this handler will process
      // all events coming from iframe.
      win.on('message', data => {
        console.warn('[Application', 'Iframe sent', data);
        win.emit(data.method, sendMessage, ...data.args);
      });
      
      //cb(sendMessage);
    });
    
    return iframe;
  };

  const makeApiCall = function(core, params) {
    return (async () => {
      var caller = core.make("oxzion/restClient");
      // console.log(params.data)
      var res = await caller.request(
        params.version,
        params.action,
        params.data,
        params.method
        );
        if (res != null && res.status == "success") {
          return Promise.resolve(res);
        } else if (res != null && res.status != "success") {
          console.log("login failed.");
          return Promise.reject(new Error(res.message));
        } else {
          return Promise.reject(new Error(res.message));
        }
      })();
    };
    
    
    // Creates the internal callback function when OS.js launches an application
    // Note the first argument is the 'name' taken from your metadata.json file
    OSjs.make('osjs/packages').register('Chat', (core, args, options, metadata) => {
      
      // Create a new Application instance
      const proc = core.make('osjs/application', {
        args,
        options,
        metadata 
      });
      let trayInitialized = false;
            
      // Create  a new Window instance
      const createProcWindow = () => {
        let win = proc.createWindow({
          id: 'ChatWindow',
          icon: proc.resource(proc.metadata.icon_white),
          title: metadata.title.en_EN,
          dimension: {width: 400, height: 500},
          position: {left: 200, top: 400},
          attributes : {
            visibility: 'restricted',
            closeable: false
          }
        })
        // To close the Chat app when the window is destructed
        .on('close', () => {
          console.log("close event");
        })
       
        // .on('init', () => ref.maximize())
        .render(($content, win) => {

          HeaderIcon();

          // Context menu is hidden
          win.$icon.addEventListener('click', (ev) => {
            ev.stopPropagation();
            ev.preventDefault();
            core.make('osjs/contextmenu').hide();
          });
          win.$icon.addEventListener('dblclick', (ev) =>{
            ev.stopPropagation();
            ev.preventDefault();
          });
         
          // console.log(maximize);
           win.minimize();
          const suffix = `?pid=${proc.pid}&wid=${win.wid}`;
          
          const user = core.make('osjs/auth').user();
          // Get path to iframe content
          const src = proc.resource(baseUrl + '/login' + suffix + '&oxauth=' + user.jwt);
          
          // Create DOM element
          const iframe = createIframe(core, proc, win, send => {
          });
          
          console.log(core);
          // Finally set the source and attach
          iframe.src = src;
          
          win.on("notify", (callback, params) => {
            // console.log(params);
            makeApiCall(core, params)
            .then(res => {
              callback({ method: "notify", params: params, data: res.data });
            })
            .catch(err => {
              callback({
                method: "notify",
                params: params,
                error: err.message
              });
            });
          });
          
          // Tray Icon
          if (core.has('osjs/tray') && !trayInitialized) {
            trayInitialized = true;
            trayOptions.title = "Chat";
            trayOptions.icon = proc.resource(metadata.icon);
            trayOptions.onclick = () => {
                      console.log(proc);
                      win.raise();
                      HeaderIcon();
                      win.focus();
                      resetBadge();
            }
            tray = core.make('osjs/tray').create(trayOptions, (ev) => {
              core.make('osjs/contextmenu').show({
                position: ev
              });
            });
          }
          
          // Attach
          $content.appendChild(iframe);
        })
      
      }
      createProcWindow();  
      return proc;
    });
    