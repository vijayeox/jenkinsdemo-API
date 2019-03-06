import React, { Component } from "react";

import { FaArrowLeft, FaPlusCircle } from "react-icons/fa";

import {
  Grid,
  GridColumn as Column,
  GridToolbar
} from "@progress/kendo-react-grid";

import ReactNotification from "react-notifications-component";

import DialogContainer from "./dialog/DialogContainerGroup";
import cellWithEditing from "./cellWithEditing";
import { orderBy } from "@progress/kendo-data-query";

class Group extends React.Component {
	constructor(props) {
		super(props);
		this.core = this.props.args;
	
		this.state = {
		  groupInEdit: undefined,
		  sort: [{ field: "name", dir: "asc" }],
		  products: [],
		  action: ""
		};
	
		this.addNotification = this.addNotification.bind(this);
		this.notificationDOMRef = React.createRef();
	
		this.getGroupData().then(response => {
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
		this.getGroupData().then(response => {
		  this.setState({ products: response.data });
		  this.addDataNotification(serverResponse);
		});
	  };
	
	  async getGroupData() {
		let helper = this.core.make("oxzion/restClient");
		let groupData = await helper.request("v1", "/group", {}, "get");
		return groupData;
	  }
	
	  edit = dataItem => {
		this.setState({
		  groupInEdit: this.cloneProduct(dataItem),
		  action: "edit"
		});
	  };
	
	  async deleteGroupData(dataItem) {
		let helper = this.core.make("oxzion/restClient");
		let delGroup = helper.request(
		  "v1",
		  "/group/" + dataItem,
		  {},
		  "delete"
		);
		return delGroup;
	  }
	
	  remove = dataItem => {
		this.deleteGroupData(dataItem.id).then(response => {
		  this.handler();
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
		const dataItem = this.state.groupInEdit;
		const products = this.state.products.slice();
	
		if (dataItem.id === undefined) {
		  products.unshift(this.newProduct(dataItem));
		} else {
		  const index = products.findIndex(p => p.id === dataItem.id);
		  products.splice(index, 1, dataItem);
		}
	
		this.setState({
		  products: products,
		  groupInEdit: undefined
		});
	  };
	
	  cancel = () => {
		this.setState({ groupInEdit: undefined });
	  };
	
	  insert = () => {
		this.setState({ groupInEdit: {}, action: "add" });
	  };

	render() {
		return (
			<div id="groupPage">
			<ReactNotification ref={this.notificationDOMRef} />
			<div style={{ margin: "10px 0px 10px 0px" }} className="row">
			  <div className="col s3">
				<a className="waves-effect waves-light btn" id="goBack2">
				  <FaArrowLeft />
				</a>
			  </div>
			  <center>
				<div className="col s6" id="pageTitle">
				  Manage Groups
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
				  <div style={{ fontSize: "20px" }}>Groups List</div>
				  <button
					onClick={this.insert}
					className="k-button"
					style={{ position: "absolute", top: "8px", right: "16px" }}
				  >
					<FaPlusCircle style={{ fontSize: "20px" }} />
	
					<p style={{ margin: "0px", paddingLeft: "10px" }}>
					  Add Group
					</p>
				  </button>
				</div>
			  </GridToolbar>
	
			  <Column field="id" title="ID" width="70px" />
			  <Column field="name" title="Name" />
	
			  <Column field="manager_id" title="Manager ID" />
			  <Column field="description" title="Description" />
			  <Column
				title="Edit"
				width="160px"
				cell={cellWithEditing(this.edit, this.remove)}
			  />
			</Grid>
	
			{this.state.groupInEdit && (
			  <DialogContainer
				args={this.core}
				dataItem={this.state.groupInEdit}
				save={this.save}
				cancel={this.cancel}
				formAction={this.state.action}
				action={this.handler}
			  />
			)}
		  </div>
		);
	}

	dialogTitle() {
		return `${this.state.groupInEdit.id === undefined ? "Add" : "Edit"} product`;
	  }
	
	  cloneProduct(product) {
		return Object.assign({}, product);
	  }
	
	  newProduct(source) {
		const newProduct = {
		  id: "",
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
	}

export default Group;
