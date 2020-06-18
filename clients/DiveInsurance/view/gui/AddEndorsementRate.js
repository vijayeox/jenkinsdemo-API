import React from "react";
import { Window } from "@progress/kendo-react-dialogs";

export default class AddNewItemDialog extends React.Component {
  constructor(props) {
    super(props);
    this.core = this.props.args;
    this.monthNames = [
      "January",
      "February",
      "March",
      "April",
      "May",
      "June",
      "July",
      "August",
      "September",
      "October",
      "November",
      "December"
    ];
    this.state = {
      coverageList: [],
      dataItem: this.props.dataItem,
      month: this.monthNames[0],
      coverage: null,
      premium: null,
      tax: null,
      padiFee: null
    };
    this.helper = this.props.core.make("oxzion/restClient");
  }

  componentDidMount() {
    this.props.adminItem == "PremiumRates"
      ? this.getCoverage().then((response) =>
          this.setState({ coverageList: response.data })
        )
      : null;
  }

  async getCoverage() {
    let response = await this.helper.request(
      "v1",
      "/app/" + this.props.dataItem.appId + "/command/delegate/CoverageList",
      this.props.dataItem,
      "post"
    );
    return response;
  }

  async addItem() {
    let helper = this.core.make("oxzion/restClient");
    let editedData = await helper.request(
      "v1",
      "/app/" +
        this.state.dataItem.appId +
        "/command/delegate/AddOrRemovePolicyRates",
      {},
      "post"
    );
    if (editedData.status == "success") {
      this.props.cancel();
    } else {
      var error = editedData.errors
        ? editedData.errors[0].message
        : editedData.message;
      this.notif.current.notify("Operation Failed", error, "danger");
    }
  }

  async updateItem(e) {
    e.preventDefault();
    let editedData = await this.helper.request(
      "v1",
      "/app/" +
        this.props.dataItem.appId +
        "/command/delegate/AddOrRemovePolicyRates",

      {
        product: this.props.dataItem.product,
        year: this.props.dataItem.year,
        month: this.state.month,
        previous_coverage: this.props.dataItem.coverage,
        coverage: this.state.coverage,
        premium: this.state.premium || 0,
        tax: this.state.tax || 0,
        padi_fee: this.state.padiFee || 0,
        total: this.state.total || 0,
        coverage_category: this.props.dataItem.coverage_category,
        type: "add"
      },
      "post"
    );
    if (editedData.status == "success") {
      this.props.childGrid.current.refreshHandler();
      this.props.cancel();
    } else {
      this.props.notif.current.notify(
        "Operation Failed",
        editedData.errors ? editedData.errors[0].message : editedData.message,
        "danger"
      );
    }
  }

  onDialogInputChange = (event) => {
    let target = event.target;
    let name = target.props ? target.props.name : target.name;
    this.setState({
      [name]: target.value
    });
  };

  generateRateFields() {
    return (
      <React.Fragment>
        <div className="form-group">
          <div className="form-row">
            <div className="col-sm-6">
              <label className="required-label">Premium</label>
              <this.props.GUIComponents.KendoReactInput.Input
                type="number"
                className="form-control"
                name="premium"
                value={this.state.premium || 0}
                onChange={this.onDialogInputChange}
                placeholder="Enter Premium Amount"
                maxLength="5"
                required={true}
                validationMessage={"Please enter the Premium amount"}
              />
            </div>

            <div className="col-sm-6">
              <label>Tax</label>
              <this.props.GUIComponents.KendoReactInput.Input
                type="number"
                className="form-control"
                name="tax"
                value={this.state.tax || 0}
                onChange={this.onDialogInputChange}
                placeholder="Enter Tax"
                maxLength="50"
              />
            </div>
          </div>
        </div>

        <div className="form-group">
          <div className="form-row">
            <div className="col-sm-6">
              <label>PADI Fee</label>
              <this.props.GUIComponents.KendoReactInput.Input
                type="number"
                className="form-control"
                name="padiFee"
                value={this.state.padiFee || 0}
                onChange={this.onDialogInputChange}
                placeholder="Enter PADI Fee"
                maxLength="50"
              />
            </div>

            <div className="col-sm-6">
              <label>Total</label>
              <this.props.GUIComponents.KendoReactInput.Input
                type="number"
                className="form-control"
                value={this.calculateTotal()}
                readOnly={true}
              />
            </div>
          </div>
        </div>
      </React.Fragment>
    );
  }

  calculateTotal() {
    return (
      (parseFloat(this.state.premium) || 0) +
      (parseFloat(this.state.tax) || 0) +
      (parseFloat(this.state.padiFee) || 0)
    );
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
        title={"Add New Endorsement Rate"}
        left={0}
        top={0}
        width={
          document.getElementsByClassName("customAdminPage")[0].offsetWidth - 75
        }
        height={
          document.getElementsByClassName("customAdminPage")[0].offsetHeight
        }
      >
        <form id="endorsementRateForm" onSubmit={(e) => this.updateItem(e)}>
          <div className="form-group">
            <div className="form-row">
              <div className="col-sm-6">
                <label className="required-label">Month</label>
                <this.props.GUIComponents.DropDown
                  rawData={this.monthNames}
                  selectedItem={this.state.month}
                  onDataChange={(e) => {
                    this.setState({
                      month: e.target.value
                    });
                  }}
                  required={true}
                  filterable={false}
                />
              </div>
              <div className="col-sm-6">
                <label className="required-label">Year</label>
                <this.props.GUIComponents.KendoReactInput.Input
                  type="text"
                  className="form-control"
                  value={this.state.dataItem.year}
                  readOnly={true}
                />
              </div>
            </div>
          </div>

          <div className="form-group">
            <div className="form-row">
              <div className="col-sm-6">
                <label className="required-label">Coverage</label>
                <this.props.GUIComponents.KendoReactInput.Input
                  type="text"
                  className="form-control"
                  value={this.props.dataItem.coverage}
                  readOnly={true}
                />
              </div>
              <div className="col-sm-6">
                <label className="required-label">Upgrade Coverage</label>
                <this.props.GUIComponents.DropDown
                  rawData={this.state.coverageList}
                  selectedItem={this.state.coverage}
                  onDataChange={(e) => {
                    this.setState({
                      coverage: e.target.value
                    });
                  }}
                  required={true}
                  filterable={false}
                />
              </div>
            </div>
          </div>

          {this.props.dataItem.product ==
          "Individual Professional Liability - Endorsement"
            ? this.generateRateFields()
            : null}
        </form>
        <button
          type="submit"
          form={
            this.props.adminItem == "PremiumRates"
              ? "endorsementRateForm"
              : "surplusLinesForm"
          }
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
