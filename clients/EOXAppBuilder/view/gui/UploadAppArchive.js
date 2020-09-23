import React, { Suspense } from "react";

class UploadAppArchive extends React.Component {
  constructor(props) {
    super(props);
    this.state = {
      uploadFile: [],
    };
    this.core = this.props.core;
    this.loader = this.core.make("oxzion/splash");
    this.notif = React.createRef();
  }

  async uploadZip() {
    let helper = this.core.make("oxzion/restClient");
    let response = await helper.request(
      "v1",
      "/app/archive/upload",
      { file: this.state.uploadFile[0].getRawFile() },
      "filepost"
    );
    return response;
  }

  stepDownPage() {
    let stepDownPage = new CustomEvent("stepDownPage", {
      detail: {},
      bubbles: true,
    });
    let handleGridRefresh = new CustomEvent("handleGridRefresh", {
      detail: {},
      bubbles: true,
    });
    if (document.getElementById("navigation_" + this.props.appId)) {
      document
        .getElementById("navigation_" + this.props.appId)
        .dispatchEvent(stepDownPage);
      document
        .getElementById("navigation_" + this.props.appId)
        .dispatchEvent(handleGridRefresh);
    }
  }

  onFileChange = (event) => {
    let fileError = false;
    let validFiles = event.newState.filter((item) => {
      if (item.validationErrors) {
        if (item.validationErrors.length > 0) {
          fileError = true;
          return false;
        }
      } else {
        return true;
      }
    });

    if (validFiles) {
      this.setState({
        uploadFile: validFiles,
      });
    }
    fileError
      ? this.notif.current.notify(
          "Unsupported File",
          "Please select a valid zip archive.",
          "danger"
        )
      : null;
  };

  render() {
    return (
      <div>
        <this.props.components.Notification ref={this.notif} />
        <div className="col-md-8">
          <h5
            class="alert alert-warning"
            role="alert"
            style={{ paddingBottom: "10px" }}
          >
            Please verify the zip archive contains valid appilcaion.yml file
            before proceeding with the import
          </h5>
        </div>
        <Suspense fallback={<div />}>
          <div className="col-md-10">
            <this.props.components.KendoFileUploader.Upload
              accept=".zip"
              autoUpload={false}
              multiple={false}
              showActionButtons={false}
              files={this.state.uploadFile}
              onAdd={this.onFileChange}
              onRemove={this.onFileChange}
              restrictions={{
                allowedExtensions: [".zip"],
                maxFileSize: 35000000,
              }}
            />
          </div>
        </Suspense>
        <div style={{ paddingTop: "10px" }}>
          <button
            type="button"
            class="btn btn-primary"
            disabled={this.state.uploadFile.length == 0}
            onClick={() => {
              this.loader.show();
              this.uploadZip().then((response) => {
                this.loader.destroy();
                if (response.status == "success") {
                  this.notif.current.notify(
                    "Upload Completed",
                    "You can now Edit or Deploy the application.",
                    "success"
                  );
                  this.stepDownPage();
                } else {
                  this.notif.current.notify(
                    "Import Failed",
                    response.message,
                    "danger"
                  );
                }
              });
            }}
          >
            Upload Zip
          </button>
        </div>
      </div>
    );
  }
}

export default UploadAppArchive;
