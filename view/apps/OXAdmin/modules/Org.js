import React, { Component } from "react";
import osjs from "osjs";
import ReactDOM from "react-dom";

import "../App.css";
import "./all.css";
import { FaArrowLeft } from "react-icons/fa";

import { library } from "@fortawesome/fontawesome-svg-core";
import { FontAwesomeIcon } from "@fortawesome/react-fontawesome";
import { faPlusCircle } from "@fortawesome/free-solid-svg-icons";
library.add(faPlusCircle);

import {
  Grid,
  GridColumn as Column,
  GridToolbar
} from "@progress/kendo-react-grid";

import { orgData } from "./data/organisationData";

import DialogContainer from "./dialog/DialogContainerOrg";
import cellWithEditing from "./cellWithEditing";
import { orderBy } from "@progress/kendo-data-query";

class Org extends React.Component {
  constructor(props) {
    super(props);
    this.core = this.props.args;

    this.state = {
      productInEdit: undefined,
      sort: [{ field: "id", dir: "asc" }],
      products: ""
    };

    this.getAnnouncements().then(response => {
      this.setState({ products: response.data });
      console.log(this.state.products);
    });
  }

  async getAnnouncements() {
    // call to api using wrapper
    let helper = this.core.make("oxzion/restClient");
    let announ = await helper.request("v1", "/organization", {}, "get");
    return announ;
  }

  edit = dataItem => {
    this.setState({ productInEdit: this.cloneProduct(dataItem) });
  };

  remove = dataItem => {
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
    const dataItem = this.state.productInEdit;
    const products = this.state.products.slice();

    if (dataItem.id === undefined) {
      products.unshift(this.newProduct(dataItem));
    } else {
      const index = products.findIndex(p => p.id === dataItem.id);
      products.splice(index, 1, dataItem);
    }

    this.setState({
      products: products,
      productInEdit: undefined
    });
  };

  cancel = () => {
    this.setState({ productInEdit: undefined });
  };

  insert = () => {
    this.setState({ productInEdit: {} });
  };

  render() {
    return (
      <div>
        <div className="container" id="organisation">
          <div style={{ display: "flex", marginBottom: "20px" }}>
            <button
              id="goBack5"
              className="btn btn-sq"
              style={{ marginRight: "20%" }}
            >
              <FaArrowLeft />
            </button>
            <center>
              <h3 className="mainHead">Manage Organisations</h3>
            </center>
          </div>

          <Grid
            style={{ height: "475px" }}
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
                <h4>Organisations List</h4>
                <button
                  onClick={this.insert}
                  className="k-button"
                  style={{ position: "absolute", top: "8px", right: "16px" }}
                >
                  <FontAwesomeIcon
                    icon="plus-circle"
                    style={{ fontSize: "20px" }}
                  />
                  <p style={{ margin: "0px", paddingLeft: "10px" }}>
                    Add Organisation
                  </p>
                </button>
              </div>
            </GridToolbar>

            <Column field="id" title="Org. ID" width="90px" />
            <Column field="name" title="Name" />
            <Column field="state" title="State" />
            <Column field="zip" title="Zip" />
            <Column
              title="Edit"
              width="150px"
              cell={cellWithEditing(this.edit, this.remove)}
            />
          </Grid>

          {this.state.productInEdit && (
            <DialogContainer
              dataItem={this.state.productInEdit}
              save={this.save}
              cancel={this.cancel}
            />
          )}
        </div>
      </div>
    );
  }

  dialogTitle() {
    return `${
      this.state.productInEdit.id === undefined ? "Add" : "Edit"
    } product`;
  }
  cloneProduct(product) {
    return Object.assign({}, product);
  }

  newProduct(source) {
    const newProduct = {
      ProductID: this.generateId(),
      name: "",
      address: "",
      city: "",
      state: "",
      zip: "",
      logo: "",
      languagefile: ""
    };

    return Object.assign(newProduct, source);
  }

  generateId() {
    let id = 1;
    this.state.products.forEach(p => {
      id = Math.max((p.id || 0) + 1, id);
    });
    return id;
  }
}

export default Org;
