import React, { Suspense } from "react";

class UploadAppArchive extends React.Component {
  constructor(props) {
    super(props);
    this.state = {
      uploadFile: [],
    };
    this.notif = React.createRef();
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

        <h5 class="alert alert-warning" role="alert">
          Please verify the zip archive contains valid appilcaion.yml file
          before proceeding with the import
        </h5>
        <Suspense fallback={<div />}>
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
              maxFileSize: 25000000,
            }}
          />
        </Suspense>
      </div>
    );
  }
}

export default UploadAppArchive;
