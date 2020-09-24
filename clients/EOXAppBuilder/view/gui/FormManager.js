import React from "react";

class FormManager extends React.Component {
  constructor(props) {
    super(props);
    this.OxzionGUIComponents = this.props.components;
    this.core = this.props.core;
    this.helper = this.core.make("oxzion/restClient");
    this.applicationId = this.props.appId;
    var formId = null;
    var appId = null;
    var content = null;
    if (this.props.params) {
      formId = this.props.params.form_id;
      appId = this.props.params.app_uuid;
      if (this.props.params.content) {
        try {
          content = JSON.parse(this.props.params.content);
        } catch (error) {
          content = this.props.params.content;
        }
      }
    }
    this.state = {
      windowVisible: false,
      formId: formId,
      appId: appId,
      content: content,
    };
    this.appUrl = "/app/" + appId;
    this.saveForm = this.saveForm.bind(this);
    this.notif = React.createRef();
  }

  stepDownPage() {
    let ev = new CustomEvent("stepDownPage", {
      detail: {},
      bubbles: true,
    });
    if (document.getElementById("navigation_" + this.applicationId)) {
      document
        .getElementById("navigation_" + this.applicationId)
        .dispatchEvent(ev);
      this.props.refresh();
    }
  }
  async saveForm(form) {
    var response = await this.helper.request(
      "v1",
      this.appUrl + "/artifact/add/form",
      {
        [name + ".json"]: {
          body: new Blob([JSON.stringify(form)], { type: "application/json" }),
          name: form.name + ".json",
        },
      },
      "filepost"
    );
    response.status == "success"
      ? this.stepDownPage()
      : this.notif.current.notify("Form Save Failed", response.message, "danger");
  }

  render() {
    return (
      <div>
        <this.props.components.Notification ref={this.notif} />
        <React.Suspense fallback={<div>Loading...</div>}>
          <this.OxzionGUIComponents.FormBuilder
            core={this.core}
            saveForm={this.saveForm}
            content={this.state.content}
            formId={this.state.formId}
            appId={this.state.appId}
          />
        </React.Suspense>
      </div>
    );
  }
}

export default FormManager;
