import React, { Component } from "react";

import { FaArrowLeft, FaPlusCircle } from "react-icons/fa";

import {
  Grid,
  GridColumn as Column,
  GridToolbar
} from "@progress/kendo-react-grid";

import ReactNotification from "react-notifications-component";
import { Button } from '@progress/kendo-react-buttons';
import DialogContainer from "./dialog/DialogContainerRole";
import cellWithEditing from "./manage/cellWithEditing";
import { orderBy } from "@progress/kendo-data-query";

class Permissionallowed extends React.Component {
  render() {
    if(this.props.perm == 7 || this.props.perm == 15){
      return (
        <button
        onClick={this.props.args}
        className="k-button"
        style={{ position: "absolute", top: "8px", right: "16px" }}
      >
        <FaPlusCircle style={{ fontSize: "20px" }} />

        <p style={{ margin: "0px", paddingLeft: "10px" }}>
          Add Organization
        </p>
      </button>
      );
    }
    else{
     return(
       <div></div>
     )
    }
  }
}

class Role extends React.Component {
  constructor(props) {
    super(props);
    this.core = this.props.args;

    this.state = {
      roleInEdit: undefined,
      sort: [{ field: "name", dir: "asc" }],
      products: [],
      action: "",
      permission:"15"
    };

    this.addNotification = this.addNotification.bind(this);
    this.notificationDOMRef = React.createRef();

    this.getRoleData().then(response => {
      this.setState({ products: response.data });
    });
  }

  addDataNotification(serverResponse) {
    this.notificationDOMRef.current.addNotification({
      title: "Operation Successful",
      message: "Entry created with ID:" + serverResponse,
      type: "success",
      insert: "top",
      container: "bottom-right",
      animationIn: ["animated", "bounceIn"],
      animationOut: ["animated", "bounceOut"],
      dismiss: { duration: 5000 },
      dismissable: { click: true }
    });
  }

  addNotification(serverResponse) {
    this.notificationDOMRef.current.addNotification({
      title: "All Done!!!  👍",
      message: "Operation succesfully completed.",
      type: "success",
      insert: "top",
      container: "bottom-right",
      animationIn: ["animated", "bounceIn"],
      animationOut: ["animated", "bounceOut"],
      dismiss: { duration: 5000 },
      dismissable: { click: true }
    });
  }

  handler = serverResponse => {
    this.getRoleData().then(response => {
      this.setState({ products: response.data });
      this.addDataNotification(serverResponse);
    });
  };

  async getRoleData() {
    let helper = this.core.make("oxzion/restClient");
    let RoleData = await helper.request("v1", "/role", {}, "get");
    let helper2 = this.core.make("oxzion/restClient");
    await helper2.request("v1", "/privilege", {}, "get");
    return RoleData;
  }

  edit = dataItem => {
    this.setState({
      roleInEdit: this.cloneProduct(dataItem),
      action: "edit"
    });
  };

  async deleteRoleData(dataItem) {
    let helper = this.core.make("oxzion/restClient");
    let delRole = helper.request("v1", "/role/" + dataItem, {}, "delete");
    return delRole;
  }

  remove = dataItem => {
    this.deleteRoleData(dataItem.id).then(response => {
      this.addNotification();
    });

    const products = this.state.products;
    const index = products.findIndex(p => p.id === dataItem.id);
    if (index !== -1) {
      products.splice(index, 1);
      this.setState({
        products: products
      });
    }
  };

  save = () => {
    const dataItem = this.state.roleInEdit;
    const products = this.state.products.slice();

    if (dataItem.id === undefined) {
      products.unshift(this.newProduct(dataItem));
    } else {
      const index = products.findIndex(p => p.id === dataItem.id);
      products.splice(index, 1, dataItem);
    }

    this.setState({
      products: products,
      roleInEdit: undefined
    });
  };

  cancel = () => {
    this.setState({ roleInEdit: undefined });
  };

  insert = () => {
    this.setState({ roleInEdit: {}, action: "add" });
  };


  disp(){
    console.log(this.state.permission)
    if(this.state.permission!=1){
      console.log(this.state.permission);
      return(
    <Column
    title="Edit"
    width="160px"
    cell={cellWithEditing(this.edit, this.remove, this.state.permission)}
    filterCell={this.searchUnavailable}
  />
      );
    } else {
      console.log(
        "No Permissions"
      )
    }
  }

  render() {
    return (
      <div>
        <div id="rolePage">
          <ReactNotification ref={this.notificationDOMRef} />
          <div style={{ paddingTop: '12px' }} className="row">
            <div className="col s3">
              <Button className="goBack" primary={true} style={{ width: '45px', height: '45px' }}>
                <FaArrowLeft />
              </Button>
            </div>
            <center>
              <div className="col s6" id="pageTitle">
                Manage Roles
              </div>
            </center>
          </div>

          <Grid
            data={orderBy(this.state.products, this.state.sort)}
            sortable
            sort={this.state.sort}
            onSortChange={e => {
              this.setState({
                sort: e.sort
              });
            }}
          >
            <GridToolbar>
              <div>
                <div style={{ fontSize: "20px" }}>Role List</div>
                <Permissionallowed
               args={this.insert}
               perm={this.state.permission}
               />
              </div>
            </GridToolbar>

            <Column field="id" title="ID" width="70px" />
            <Column field="name" title="Name" />
            <Column field="description" title="Description" />

            {this.disp()}

          </Grid>

          {this.state.roleInEdit && (
            <DialogContainer
              args={this.core}
              dataItem={this.state.roleInEdit}
              save={this.save}
              cancel={this.cancel}
              formAction={this.state.action}
              action={this.handler}
            />
          )}
        </div>
      </div>
    );
  }

  dialogTitle() {
    return `${this.state.roleInEdit.id === undefined ? "Add" : "Edit"} product`;
  }

  cloneProduct(product) {
    return Object.assign({}, product);
  }

  newProduct(source) {
    const newProduct = {
      id: "",
      name: "",
      description: "",
      ReadG: false,
      WriteG: false,
      CreateG: false,
      DeleteG: false,
      ReadO: false,
      WriteO: false,
      CreateO: false,
      DeleteO: false,
      ReadR: false,
      WriteR: false,
      CreateR: false,
      DeleteR: false,
      ReadU: false,
      WriteU: false,
      CreateU: false,
      DeleteU: false
    };

    return Object.assign(newProduct, source);
  }
}

export default Role;
