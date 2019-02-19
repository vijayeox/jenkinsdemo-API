import React, { Component } from 'react';

import '../public/scss/app.css';
import '../public/scss/kendo.css';
import { FaArrowLeft, FaSyncAlt, FaPlusCircle } from 'react-icons/fa';

import { Grid, GridColumn as Column, GridToolbar } from '@progress/kendo-react-grid';

import { orgData } from './data/organizationData';

import DialogContainer from './dialog/DialogContainerOrg';
import cellWithEditing from './cellWithEditing';
import { orderBy } from '@progress/kendo-data-query';

class Organization extends React.Component {
	constructor(props) {
		super(props);
		this.core = this.props.args;

		this.state = {
			productInEdit: undefined,
			sort: [{ field: 'id', dir: 'desc' }],
			products: '',
		};

		this.getOrganizationData().then(response => {
			this.setState({ products: response.data });
		});
	}

	async getOrganizationData() {
		let helper = this.core.make('oxzion/restClient');
		let OrgData = await helper.request('v1', '/organization', {}, 'get');
		return OrgData;
	}

	edit = dataItem => {
		this.setState({ productInEdit: this.cloneProduct(dataItem) });
	};

	async deleteOrganizationData() {
		let helper = this.core.make('oxzion/restClient');
		let OrgData = await helper.request('v1', '/organization', { delId }, 'delete');
		return OrgData;
	}

	remove = dataItem => {
		var delId = 4;
		this.deleteOrganizationData(delId);

		const products = this.state.products;
		const index = products.findIndex(p => p.id === dataItem.id);
		if (index !== -1) {
			products.splice(index, 1);
			this.setState({
				products: products,
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
			productInEdit: undefined,
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
				<div className="container" id="organization">
					<div style={{ display: 'flex', marginBottom: '20px' }}>
						<button id="goBack5" className="btn btn-sq" style={{ marginRight: '20%' }}>
							<FaArrowLeft />
						</button>
						<center>
							<h3 className="mainHead">Manage Organizations</h3>
						</center>

						<button className="btn btn-sq" style={{ marginLeft: '20%' }}>
							<FaSyncAlt />
						</button>
					</div>

					<Grid
						style={{ height: '400px' }}
						data={orderBy(this.state.products, this.state.sort)}
						sortable
						sort={this.state.sort}
						onSortChange={e => {
							this.setState({
								sort: e.sort,
							});
						}}
					>
						<GridToolbar>
							<div>
								<h4>Organizations List</h4>
								<button
									onClick={this.insert}
									className="k-button"
									style={{ position: 'absolute', top: '8px', right: '16px' }}
								>
									<FaPlusCircle style={{ fontSize: '20px' }} />

									<p style={{ margin: '0px', paddingLeft: '10px' }}>Add Organization</p>
								</button>
							</div>
						</GridToolbar>

						<Column field="id" title="Org. ID" width="90px" />
						<Column field="name" title="Name" />
						<Column field="state" title="State" />
						<Column field="zip" title="Zip" />
						<Column title="Edit" width="150px" cell={cellWithEditing(this.edit, this.remove)} />
					</Grid>

					{this.state.productInEdit && (
						<DialogContainer
							args={this.core}
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
		return `${this.state.productInEdit.id === undefined ? 'Add' : 'Edit'} product`;
	}

	cloneProduct(product) {
		return Object.assign({}, product);
	}

	newProduct(source) {
		const newProduct = {
			ProductID: this.generateId(),
			name: '',
			address: '',
			city: '',
			state: '',
			zip: '',
			logo: '',
			languagefile: '',
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

export default Organization;
