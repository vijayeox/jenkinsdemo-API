import React from "react";
import PropTypes from "prop-types";
import {
  Grid,
  GridCell,
  GridColumn,
  GridDetailRow,
  GridNoRecords,
  GridToolbar
} from "@progress/kendo-react-grid";
import { Button, DropDownButton } from "@progress/kendo-react-buttons";
import $ from "jquery";
import JsxParser from "react-jsx-parser";
import moment from "moment";

import DataLoader from "./components/Grid/DataLoader";
import DataOperation from "./components/Grid/DataOperation";
import CustomFilter from "./components/Grid/CustomFilter";

import "./components/Grid/customStyles.scss";
import "@progress/kendo-theme-bootstrap/dist/all.css";

export default class OX_Grid extends React.Component {
  constructor(props) {
    super(props);
    this.child = React.createRef();
    this.userprofile = this.props.osjsCore.make("oxzion/profile").get().key;
    this.rawDataPresent = typeof this.props.data == "object" ? true : false;
    this.state = {
      gridData: this.rawDataPresent ? this.props.data : [],
      dataState: this.props.gridDefaultFilters
        ? this.props.gridDefaultFilters
        : {},
      apiActivityCompleted: this.rawDataPresent ? true : false
    };
  }

  componentDidMount() {
    $(document).ready(function() {
      $(".k-textbox").attr("placeholder", "Search");
    });
    if (!document.getElementsByClassName("PageRender")) {
      this.gridHeight =
        document.getElementsByClassName("PageRender")[0].clientHeight - 50;
    }
  }

  dataStateChange = e => {
    this.setState({
      ...this.state,
      dataState: e.data
    });
  };

  dataRecieved = data => {
    this.setState({
      gridData: data,
      apiActivityCompleted: true
    });
  };

  componentWillReceiveProps(props) {
    if (props.gridDefaultFilters) {
      this.setState({ dataState: props.gridDefaultFilters });
    }
  }

  parseDefaultFilters() {
    var splitUrl = this.props.data.split("?");
    if (splitUrl[1]) {
      apiUrl = splitUrl[0];
      var getUrlParams = decodeURI(splitUrl[1])
        .replace("?", "")
        .split("&")
        .map(param => param.split("="))
        .reduce((values, [key, value]) => {
          values[key] = value;
          return values;
        }, {});
      if (getUrlParams.filter) {
        try {
          defaultFilters = JSON.parse(getUrlParams.filter);
        } catch (e) {
          console.log(getUrlParams.filter);
          console.log(e);
          defaultFilters = getUrlParams.filter;
        }
      } else {
        apiUrl = this.props.data;
      }
    }
  }

  createColumns = () => {
    let table = [];
    this.props.checkBoxSelection
      ? table.push(
          <GridColumn
            field="selected"
            filterable={false}
            // headerSelectionValue={
            //   this.state.gridData.findIndex(
            //     dataItem => dataItem.selected === false
            //   ) === -1
            // }
            key={Math.random() * 20}
            locked={true}
            reorderable={false}
            orderIndex={0}
            width="50px"
          />
        )
      : null;
    this.props.columnConfig.map((dataItem, i) => {
      table.push(
        <GridColumn
          cell={
            dataItem.cell
              ? item => (
                  <CustomCell
                    cellTemplate={dataItem.cell}
                    dataItem={item.dataItem}
                    type={"cellTemplate"}
                    userProfile={this.userprofile}
                  />
                )
              : undefined
          }
          children={dataItem.children ? dataItem.children : undefined}
          className={dataItem.className ? dataItem.className : undefined}
          field={dataItem.field ? dataItem.field : undefined}
          filter={dataItem.filter ? dataItem.filter : "text"}
          filterable={dataItem.filterable}
          filterCell={
            dataItem.filterCell ? CustomFilter(dataItem.filterCell) : undefined
          }
          groupable={dataItem.groupable ? dataItem.groupable : undefined}
          headerClassName={
            dataItem.headerClassName ? dataItem.headerClassName : undefined
          }
          headerCell={dataItem.headerCell ? dataItem.headerCell : undefined}
          key={i}
          locked={dataItem.locked ? dataItem.locked : undefined}
          minResizableWidth={
            dataItem.minResizableWidth ? dataItem.minResizableWidth : undefined
          }
          orderIndex={dataItem.orderIndex ? dataItem.orderIndex : undefined}
          reorderable={dataItem.reorderable ? dataItem.reorderable : undefined}
          resizable={dataItem.resizable ? dataItem.resizable : undefined}
          width={dataItem.width ? dataItem.width : undefined}
          title={dataItem.title ? dataItem.title : undefined}
        />
      );
    });
    return table;
  };

  expandChange = event => {
    event.dataItem.expanded = !event.dataItem.expanded;
    this.forceUpdate();
  };

  headerSelectionChange = event => {
    const checked = event.syntheticEvent.target.checked;
    this.state.gridData.forEach(item => (item.selected = checked));
    this.forceUpdate();
  };

  noRecordsJSX() {
    return (
      <GridNoRecords>
        {this.props.gridNoRecords ? (
          this.props.gridNoRecords
        ) : (
          <div className="grid-no-records">
            <ul className="list-group" style={{ listStyle: "disc" }}>
              <div
                href="#"
                className="list-group-item list-group-item-action bg-warning"
                style={{
                  display: "flex",
                  width: "110%",
                  alignItems: "center"
                }}
              >
                <div style={{ marginLeft: "10px" }}>
                  <i className="fas fa-info-circle"></i>
                </div>
                <div
                  style={{ fontSize: "medium", paddingLeft: "30px" }}
                  className="noRecords"
                >
                  No Records Available
                </div>
              </div>
            </ul>
          </div>
        )}
      </GridNoRecords>
    );
  }

  selectionChange = event => {
    event.dataItem.selected = !event.dataItem.selected;
    this.forceUpdate();
    var selectedItems = [];
    this.state.gridData.data.map(dataItem => {
      dataItem.selected ? selectedItems.push(dataItem) : null;
    });
    this.props.checkBoxSelection(selectedItems);
  };

  updatePageContent = config => {
    let eventDiv = document.getElementsByClassName(
      this.props.appId + "_breadcrumbParent"
    )[0];

    let ev = new CustomEvent("updateBreadcrumb", {
      detail: config,
      bubbles: true
    });
    eventDiv.dispatchEvent(ev);

    let ev2 = new CustomEvent("updatePageView", {
      detail: config.details,
      bubbles: true
    });
    eventDiv.dispatchEvent(ev2);
  };

  renderListOperations = config => {
    var operationsList = [];
    var listData = this.state.gridData.data;
    config.actions.map(i => {
      let result = eval(i.rule);
      result ? operationsList.push(i) : null;
    });
    if (operationsList.length > 1) {
      return (
        <DropDownButton
          text={config.title ? config.title : "Options"}
          textField="name"
          className="gridOperationDropdown"
          iconClass={config.icon ? config.icon : null}
          onItemClick={e => {
            this.updatePageContent(e.item);
          }}
          popupSettings={{ popupClass: "dropDownButton" }}
          items={operationsList}
          primary={true}
          style={{ right: "10px", float: "right" }}
        />
      );
    } else if (operationsList.length == 1) {
      return (
        <Button
          style={{ right: "10px", float: "right" }}
          primary={true}
          onClick={e => this.updatePageContent(operationsList[0])}
        >
          {operationsList[0].name}
        </Button>
      );
    }
    return null;
  };

  render() {
    return (
      <div style={{ height: "100%" }} className="GridCustomStyle">
        {this.rawDataPresent ? (
          <DataOperation
            args={this.props.osjsCore}
            gridData={this.props.data}
            total={this.props.data.length}
            dataState={this.state.dataState}
            onDataRecieved={this.dataRecieved}
          />
        ) : (
          <DataLoader
            ref={this.child}
            args={this.props.osjsCore}
            url={this.props.data}
            dataState={this.state.dataState}
            onDataRecieved={this.dataRecieved}
            columnConfig={this.props.columnConfig}
          />
        )}
        <Grid
          data={this.state.gridData.data}
          total={
            this.state.gridData.total
              ? parseInt(this.state.gridData.total)
              : null
          }
          detail={
            this.props.rowTemplate
              ? dataItem => (
                  <DetailComponent
                    rowTemplate={this.props.rowTemplate}
                    dataItem={dataItem.dataItem}
                  />
                )
              : undefined
          }
          filterable={this.props.filterable}
          style={this.props.gridStyles}
          pageable={this.props.pageable}
          resizable={this.props.resizable}
          reorderable={this.props.reorderable}
          sortable={this.props.sortable}
          onDataStateChange={this.dataStateChange}
          onExpandChange={this.props.expandable ? this.expandChange : null}
          onHeaderSelectionChange={this.headerSelectionChange}
          onSelectionChange={this.selectionChange}
          onRowClick={e => {
            this.props.onRowClick ? this.props.onRowClick(e) : null;
          }}
          selectedField="selected"
          expandField={this.props.expandable ? "expanded" : null}
          {...this.state.dataState}
        >
          {(this.props.gridToolbar || this.props.gridOperations) &&
          this.state.apiActivityCompleted ? (
            <GridToolbar>
              <div
                style={{
                  display: "flex",
                  justifyContent: "space-between",
                  alignItems: "center"
                }}
              >
                {typeof this.props.gridToolbar == "string" ||
                typeof this.props.gridToolbar == "undefined" ? (
                  <JsxParser
                    bindings={{ gridData: this.state.gridData.data }}
                    jsx={this.props.gridToolbar}
                  />
                ) : (
                  <GridToolbar>{this.props.gridToolbar}</GridToolbar>
                )}
                <div>
                  {this.props.gridOperations
                    ? this.renderListOperations(this.props.gridOperations)
                    : null}
                </div>
              </div>
            </GridToolbar>
          ) : null}
          {this.createColumns()}
          {/* {this.noRecordsJSX()} */}
        </Grid>
      </div>
    );
  }
}

class CustomCell extends GridCell {
  render() {
    let checkType = typeof this.props.cellTemplate;
    if (checkType == "function") {
      var cellTemplate = this.props.cellTemplate(this.props.dataItem);
      if (this.props.type == "filterTemplate") {
        return (
          <div
            style={{
              display: "flex",
              justifyContent: "space-evenly",
              cursor: "default"
            }}
          >
            {cellTemplate}
          </div>
        );
      } else {
        return (
          <td
            style={{
              display: "flex",
              justifyContent: "space-evenly",
              cursor: "default"
            }}
          >
            {cellTemplate}
          </td>
        );
      }
    } else if (checkType == "string") {
      return (
        <JsxParser
          bindings={{
            item: this.props.dataItem,
            moment: moment,
            profile: this.props.userProfile
          }}
          jsx={this.props.cellTemplate}
        />
      );
    }
  }
}

class DetailComponent extends GridDetailRow {
  render() {
    const dataItem = this.props.dataItem;
    return <React.Fragment>{this.props.rowTemplate(dataItem)}</React.Fragment>;
  }
}

OX_Grid.defaultProps = {
  data: []
};

OX_Grid.propTypes = {
  data: PropTypes.oneOfType([PropTypes.string, PropTypes.object]).isRequired,
  checkBoxSelection: PropTypes.func,
  columnConfig: PropTypes.array.isRequired,
  gridDefaultFilters: PropTypes.object,
  gridToolbar: PropTypes.oneOfType([PropTypes.string, PropTypes.func]),
  gridNoRecords: PropTypes.element,
  gridStyles: PropTypes.object,
  groupable: PropTypes.bool,
  onRowClick: PropTypes.oneOfType([PropTypes.bool, PropTypes.func]),
  osjsCore: PropTypes.object,
  pageable: PropTypes.oneOfType([PropTypes.bool, PropTypes.object]),
  resizable: PropTypes.bool,
  reorderable: PropTypes.bool,
  rowTemplate: PropTypes.func,
  sortable: PropTypes.oneOfType([PropTypes.bool, PropTypes.object]),
  expandable: PropTypes.bool
};

// Send selected value as true in data array to enable selected field css background
// (Wont be applied for customRenderedCells)
// Example:  {
//   name: "prajwal",
//   address: "test",
//   selected: true
// }
//
// Send gridDefaultFilters in the following format
// {
//   "filter":{
//   "logic":"and",
//   "filters":[
//   {
//   "field":"workflow_name",
//   "operator":"contains",
//   "value":"ipl"
//   }
//   ]
//   },
//   "sort":[
//   {
//   "field":"workflow_name",
//   "dir":"desc"
//   }
//   ],
//   "skip":0,
//   "take":50
// }
