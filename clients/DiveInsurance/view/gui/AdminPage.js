import React from "react";
import AddEndorsementRate from "./AddEndorsementRate";
import EditSurplusLine from "./EditSurplusLine";
import "./DIAdminPageStyles.scss";

class AdminPage extends React.Component {
  constructor(props) {
    super(props);
    this.OxzionGUIComponents = this.props.components;
    this.defaultProductList = [
      "Individual Professional Liability",
      "Emergency First Response",
      "Dive Boat",
      "Dive Store",
      "Group Professional Liability"
    ];
    this.productList = this.props.productDropdown
      ? this.props.productDropdown.items
      : this.defaultProductList;
    this.state = {
      AdminComponent: this.props.adminItem,
      popupWindow: false,
      product: this.productList[0],
      year: null,
      yearList: [],
      windowVisible: false
    };
    this.gridRoute =
      "app/" +
      this.props.appId +
      "/command/delegate/" +
      this.props.gridConfig.delegate;
    this.loader = this.props.core.make("oxzion/splash");
    this.helper = this.props.core.make("oxzion/restClient");
    this.notif = React.createRef();
    this.parentGrid = React.createRef();
    this.childGrid = React.createRef();
  }

  componentDidMount() {
    this.getYearList(this.state.product);
  }

  async getYear(product) {
    let yearList = await this.helper.request(
      "v1",
      "/app/" + this.props.appId + "/command/delegate/YearList",
      {
        product: this.props.adminItem == "StateTax" ? null : product,
        type: this.props.adminItem,
        coverage: this.props.adminItem == "StateTax" ? product : null
      },
      "post"
    );
    return yearList;
  }

  async addRecord() {
    let policyRates = await this.helper.request(
      "v1",
      "/app/" +
        this.props.appId +
        "/command/delegate/" +
        this.props.gridConfig.delegate,
      {
        product: this.props.adminItem == "StateTax" ? null : this.state.product,
        coverage:
          this.props.adminItem == "StateTax" ? this.state.product : null,
        type: "addNew",
        year: parseInt(this.state.yearList[this.state.yearList.length - 1]) + 1
      },
      "post"
    );
    return policyRates;
  }

  async inlineUpdate(item) {
    this.loader.show();
    let delegateParams = {
      id: item.id
    };
    this.props.gridConfig.inlineDelegateParams.map(
      (param) => (delegateParams[param] = item[param])
    );
    let editedData = await this.helper.request(
      "v1",
      "/app/" +
        this.props.appId +
        "/command/delegate/" +
        this.props.gridConfig.inlineDelegate,
      delegateParams,
      "post"
    );
    if (editedData.status == "success") {
      this.props.gridConfig.inlineEdit
        ? this.parentGrid.current.refreshHandler()
        : this.childGrid.current.refreshHandler();
    } else {
      this.notif.current.notify(
        "Operation Failed",
        response.errors ? response.errors[0].message : response.message,
        "danger"
      );
    }
    this.loader.destroy();
  }

  async inlineRemove(item) {
    let editedData = await this.helper.request(
      "v1",
      "/app/" +
        this.props.appId +
        "/command/delegate/" +
        this.props.gridConfig.inlineRemoveDelegate,
      {
        id: item.id,
        type: "remove"
      },
      "post"
    );
    return editedData;
  }

  showEditWindow(dataItem, windowCompoent) {
    this.windowCompoent = React.createElement(windowCompoent, {
      adminItem: this.props.adminItem,
      core: this.props.core,
      notif: this.notif,
      GUIComponents: this.props.components,
      childGrid: this.childGrid,
      parentGrid: this.parentGrid,
      dataItem: {
        ...dataItem,
        appId: this.props.appId,
        product: this.state.product,
        year: this.state.year
      },
      cancel: () =>
        this.setState({
          windowVisible: false
        })
    });
    this.setState({
      windowVisible: true
    });
  }

  getYearList(product) {
    this.loader.show();
    this.getYear(product).then((response) => {
      this.setState({
        product: product ? product : this.state.product,
        yearList: response.data,
        year: response.data[response.data.length - 1]
      });
    });
    this.loader.destroy();
  }

  popupWarpper(config) {
    this.OxzionGUIComponents.PopupDialog.fire({
      title: "Are you sure?",
      text: config.displayText,
      imageUrl: config.image,
      imageWidth: 75,
      imageHeight: 75,
      confirmButtonText: config.confirmText,
      confirmButtonColor: "#d33",
      showCancelButton: true,
      cancelButtonColor: "#3085d6"
    }).then((result) => {
      if (result.value) {
        this.loader.show();
        config.trigger().then((response) => {
          if (response.status == "success") {
            config.successTrigger ? config.successTrigger() : null;
            this.loader.destroy();
          } else {
            this.loader.destroy();
            this.notif.current.notify(
              "Operation Failed",
              response.errors ? response.errors[0].message : response.message,
              "danger"
            );
          }
        });
      }
    });
  }

  renderRow(e) {
    var childColumnConfig = [
      { title: "Month", field: "month", editable: false },
      { title: "Premium", field: "premium", editor: "numeric" },
      { title: "Tax", field: "tax", editor: "numeric" },
      { title: "PADI Fee", field: "padi_fee", editor: "numeric" }
    ];
    var childInlineAction = {
      update: (dataItem) => this.inlineUpdate(dataItem),
      remove: false
    };
    var removeAction = (dataItem) => {
      this.popupWarpper({
        trigger: () => this.inlineRemove(dataItem),
        successTrigger: () => this.childGrid.current.refreshHandler(),
        confirmText: "Delete",
        displayText:
          "Do you really want to delete this record? This cannot be undone.",
        image: "https://image.flaticon.com/icons/svg/1632/1632714.svg"
      });
    };
    if (
      this.state.product == "Individual Professional Liability - Endorsement"
    ) {
      childColumnConfig.unshift({
        title: "Upgrade Coverage",
        field: "coverage",
        editable: false
      });
      childInlineAction.remove = (dataItem) => removeAction(dataItem);
    }

    if (
      this.state.product.includes("Dive") &&
      this.state.product.includes("Endorsement")
    ) {
      childColumnConfig = [
        { title: "Upgrade Coverage", field: "coverage", editable: false },
        { title: "Month", field: "month", editable: false }
      ];
      childInlineAction = {
        update: false,
        remove: (dataItem) => removeAction(dataItem)
      };
    }
    return (
      <this.OxzionGUIComponents.OX_Grid
        osjsCore={this.props.core}
        ref={this.childGrid}
        data={this.gridRoute}
        urlPostParams={{
          year: this.state.year,
          product: this.state.product,
          coverage: e.coverage,
          type: "subcoverage"
        }}
        columnConfig={childColumnConfig}
        inlineEdit={true}
        inlineActions={childInlineAction}
        gridToolbar={
          this.state.product.includes("Endorsement") ? (
            <button
              className="k-primary k-button k-grid-save-command"
              onClick={() => this.showEditWindow(e, AddEndorsementRate)}
            >
              <i className="fa fa-plus-circle editIcon"></i>
              <p className="buttonText">New Endorsement Rate</p>
            </button>
          ) : null
        }
      />
    );
  }

  render() {
    var columnConfig = this.props.gridConfig.columnConfig.map((column) =>
      column.action
        ? {
            title: "Actions",
            width: "100px",
            cell: (e) => (
              <abbr title={"Edit Surplus Lines"} key={23}>
                <button
                  className={"btn manage-btn k-button k-primary"}
                  onClick={() => this.showEditWindow(e, EditSurplusLine)}
                >
                  <i className="fa fa-pencil"></i>
                </button>
              </abbr>
            ),
            filterCell: {
              type: "empty"
            }
          }
        : column
    );
    console.log(columnConfig);

    return (
      <div className="customAdminPage">
        <div className="dropdownDiv">
          {this.props.adminItem != "CarrierPolicy" ? (
            <div className="col-md-6">
              <label style={{ fontSize: "20px" }}>
                {this.props.productDropdown
                  ? this.props.productDropdown.title
                  : "Product"}
              </label>
              <div>
                <this.OxzionGUIComponents.DropDown
                  filterable={false}
                  rawData={this.productList}
                  selectedItem={this.state.product}
                  onDataChange={(e) => {
                    this.getYearList(e.target.value);
                  }}
                  filterable={false}
                />
              </div>
            </div>
          ) : null}
          <div className="col-md-6">
            <label style={{ fontSize: "20px" }}>Year</label>
            <div>
              <this.OxzionGUIComponents.DropDown
                filterable={false}
                rawData={this.state.yearList}
                selectedItem={this.state.year}
                onDataChange={(e) => {
                  this.setState({
                    year: e.target.value
                  });
                }}
                filterable={false}
              />
            </div>
          </div>
        </div>
        <div className="col-md-12 adminPageGrid">
          {this.state.product && this.state.year && (
            <this.OxzionGUIComponents.OX_Grid
              ref={this.parentGrid}
              appId={this.props.appId}
              osjsCore={this.props.core}
              data={this.gridRoute}
              urlPostParams={{
                product:
                  this.props.adminItem == "StateTax"
                    ? null
                    : this.state.product,
                coverage:
                  this.props.adminItem == "StateTax"
                    ? this.state.product
                    : null,
                year: this.state.year,
                type: this.props.adminItem == "PremiumRates" ? "coverage" : null
              }}
              columnConfig={columnConfig}
              expandable={this.props.gridConfig.expandable ? true : false}
              rowTemplate={(e) =>
                this.props.gridConfig.expandable ? this.renderRow(e) : false
              }
              gridToolbar={
                <button
                  className="k-primary k-button k-grid-save-command"
                  onClick={() => {
                    let nextYear =
                      parseInt(
                        this.state.yearList[this.state.yearList.length - 1]
                      ) + 1;
                    this.popupWarpper({
                      trigger: () => this.addRecord(),
                      successTrigger: () =>
                        this.getYearList(this.state.product),
                      confirmText: "OK",
                      displayText:
                        "Do you really want to add the record for the year " +
                        nextYear,
                      image:
                        "https://image.flaticon.com/icons/svg/1632/1632603.svg"
                    });
                  }}
                >
                  <i className="fa fa-plus-circle"></i>
                  <p className="buttonText">
                    {"Add Record - " +
                      (parseInt(
                        this.state.yearList[this.state.yearList.length - 1]
                      ) +
                        1)}
                  </p>
                </button>
              }
              inlineEdit={this.props.gridConfig.inlineEdit}
              inlineActions={
                this.props.gridConfig.inlineEdit
                  ? {
                      update: (dataItem) => this.inlineUpdate(dataItem),
                      remove: false
                    }
                  : null
              }
              resizable={true}
            />
          )}
        </div>
        <this.OxzionGUIComponents.Notification ref={this.notif} />
        {this.state.windowVisible && this.windowCompoent}
      </div>
    );
  }
}

export default AdminPage;
