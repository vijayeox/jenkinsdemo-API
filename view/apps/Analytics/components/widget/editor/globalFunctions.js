
class Deferred {
    static deferredRegistry = {};

    constructor() {
        this.corrId = Deferred.generateUUID();
        this.promise = new Promise((resolve, reject)=> {
            this.reject = reject;
            this.resolve = resolve;
        });
        Deferred.deferredRegistry[this.corrId] = this;
        //console.log(Deferred.deferredRegistry);
    }

    static generateUUID() { // Public Domain/MIT
        let d = new Date().getTime();//Timestamp
        let d2 = (performance && performance.now && (performance.now()*1000)) || 0;//Time in microseconds since page-load or 0 if unsupported
        return 'xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx'.replace(/[xy]/g, function(c) {
            let r = Math.random() * 16;//random number between 0 and 16
            if(d > 0){  //Use timestamp until depleted
                r = (d + r)%16 | 0;
                d = Math.floor(d/16);
            } else {    //Use microseconds since page-load if supported
                r = (d2 + r)%16 | 0;
                d2 = Math.floor(d2/16);
            }
            return (c === 'x' ? r : (r & 0x3 | 0x8)).toString(16);
        });
    }

    static getFromRegistry(corrId) {
        let deferred = Deferred.deferredRegistry[corrId];
        if (deferred) {
            delete Deferred.deferredRegistry[corrId];
            //console.log(Deferred.deferredRegistry);
        }
        return deferred;
    }
}

window.oxzionEditor = null;
//CkEditor automatically calls window.onDialogEvent function when dialog events occur.
//Function must be named onDialogEvent - name is not configurable in CkEditor.
window.onDialogEvent = function(dialogEvent) {
    switch(dialogEvent.name) {
        case 'load':
            window.addEventListener('message', function(event) {
                window.handleDataResponse(event.data);
            }, false);
            //Dialog 'load' event contains reference to the editor opening this dialog.
            window.oxzionEditor = dialogEvent.editor;
            window.startWidgetEditorApp(window.oxzionEditor);
        break;
        case 'ok':
            //Reject 'ok' button click if user input validation fails.
            if (!window.widgetEditorApp.validateUserInput()) {
                throw 'User input validation failed.';
            }
            let data = window.widgetEditorApp.getWidgetState();
            window.oxzionEditor.plugins.oxzion.acceptUserData(window.oxzionEditor, data);
        break;
    }
}

const OXZION_CORRELATION_ID = 'OX_CORR_ID';
window.postDataRequest = function(url, params) {
    let deferred = new Deferred();
    params[OXZION_CORRELATION_ID] = deferred.corrId;
    window.top.postMessage({
        'action':'data', 
        'url':url, 
        'params':params
    });
    return deferred.promise;
}

window.handleDataResponse = function(response) {
    let corrId = response.params[OXZION_CORRELATION_ID];
    if (!corrId) {
        throw `Response object does not contain ${OXZION_CORRELATION_ID} parameter.`;
    }
    delete response.params[OXZION_CORRELATION_ID];
    let deferred = Deferred.getFromRegistry(corrId);
    if (!deferred) {
        console.warn('No deferred instance found. Unexpected REST response found:');
        console.warn(response);
        return;
    }
    switch(response.status) {
        case 'success':
            deferred.resolve(response);
        break;
        case 'error':
        case 'failure':
            deferred.reject(response);
        break;
    }
}

