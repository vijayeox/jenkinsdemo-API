import React from "react";

class FormManager extends React.Component {
  constructor(props) {
    super(props);
    this.OxzionGUIComponents = this.props.components;
    this.core = this.props.core;
    this.helper = this.core.make("oxzion/restClient");
    this.applicationId = this.props.appId;
    var formId=null;
    var appId=null;
    var content = null;
    if(this.props.params){
      formId=this.props.params.form_id;
      appId=this.props.params.app_uuid;
      if(typeof this.props.params.content == 'string' && this.props.params.content!=undefined){
        content = JSON.parse(this.props.params.content);
      }
    }
    this.state = {
      windowVisible: false,
      formId:formId,
      appId:appId,
      content:content
    };
    this.appUrl = "/app/"+appId;
    this.saveForm = this.saveForm.bind(this);
  }

  componentDidMount() {

  }
  stepDownPage(){
    let ev = new CustomEvent("stepDownPage", {
      detail: {},
      bubbles: true
    });
    if(document.getElementById("navigation_"+this.applicationId)){
      document.getElementById("navigation_"+this.applicationId).dispatchEvent(ev);
      if(this.props){
        try{
          this.props.postSubmitCallback();
        } catch(e){
          console.log("Unable to Handle Callback");
        }
      }
    }
  }
  async saveForm(form){
    var name = form.name;
    var body = new Blob([JSON.stringify(form)], { type: 'application/json' });
    var fileParams = [];
    fileParams[name+'.json'] = {body:body,name:name+'.json'};
    var response = await this.helper.request("v1",this.appUrl + "/artifact/add/form",fileParams,"filepost");
    this.stepDownPage();
  }

  render() {
    return (
      <div>
        <React.Suspense fallback={<div>Loading...</div>}>
        <this.OxzionGUIComponents.FormBuilder ref={this.notif} core={this.core} saveForm={this.saveForm} content={this.state.content} formId={this.state.formId} appId={this.state.appId} />
        </React.Suspense>
      </div>
    );
  }
}

export default FormManager;
