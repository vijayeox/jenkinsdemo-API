import React from "react";
import { Window } from "@progress/kendo-react-dialogs";
import TextareaAutosize from "react-textarea-autosize";

export default class AddNewItemDialog extends React.Component {
  constructor(props) {
    super(props);
    this.core = this.props.args;
    this.state = {
      surplusLine: this.props.dataItem.surplusLine
    };
    this.helper = this.props.core.make("oxzion/restClient");
  }

  async updateItem(e) {
    e.preventDefault();
    let editedData = await this.helper.request(
      "v1",
      "/app/" +
        this.props.dataItem.appId +
        "/command/delegate/UpdateSurplusLines",
      {
        product: this.props.dataItem.product,
        year: this.props.dataItem.year,
        state: this.props.dataItem.state,
        surplusLine: this.state.surplusLine
      },
      "post"
    );
    if (editedData.status == "success") {
      this.props.parentGrid.current.refreshHandler();
      this.props.cancel();
    } else {
      this.props.notif.current.notify(
        "Operation Failed",
        editedData.errors ? editedData.errors[0].message : editedData.message,
        "danger"
      );
    }
  }

  render() {
    return (
      <Window
        onClose={this.props.cancel}
        stage={"FULLSCREEN"}
        modal={true}
        dragabble={false}
        closeButton={false}
        minimizeButton={false}
        title={"Edit Surplus Lines - " + this.props.dataItem.state}
        left={0}
        top={0}
        width={
          document.getElementsByClassName("customAdminPage")[0].offsetWidth - 75
        }
        height={
          document.getElementsByClassName("customAdminPage")[0].offsetHeight
        }
      >
        <form id="editSurplusLines" onSubmit={(e) => this.updateItem(e)}>
          <div className="form-group">
            <div className="form-row">
              <TextareaAutosize
                type="text"
                className="form-control textArea"
                name="surplusLine"
                maxLength="5000"
                value={this.state.surplusLine || ""}
                onChange={(e) =>
                  this.setState({
                    surplusLine: e.target.value
                  })
                }
                placeholder="Enter Surplus Line"
                required={true}
              />
            </div>
          </div>
        </form>
        <button
          type="submit"
          form={"editSurplusLines"}
          className="btn btn-success col-sm-2 mr-3"
        >
          Save
        </button>
        <button
          type="button"
          className="btn btn-danger col-sm-2 ml-3"
          onClick={this.props.cancel}
        >
          Cancel
        </button>
      </Window>
    );
  }
}
