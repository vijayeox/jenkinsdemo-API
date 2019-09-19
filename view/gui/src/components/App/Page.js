import React from "react";
import FormRender from "./FormRender";
import Document from "./Document.js";
import OX_Grid from "../../OX_Grid";
import DocumentViewer from "../../DocumentViewer";
import Loader from "react-loader-spinner";
import "react-loader-spinner/dist/loader/css/react-spinner-loader.css";
import "./Styles/PageComponentStyles.scss";

class Page extends React.Component {
  constructor(props) {
    super(props);
    this.core = this.props.core;
    this.appId = this.props.app;
    this.state = {
      pageContent: [],
      pageId: this.props.pageId,
      submission: this.props.submission
    };
    this.contentDiv = "root_" + this.appId + "_" + this.state.pageId;
    this.loadPage(this.props.pageId);
    this.itemClick = this.itemClick.bind(this);
  }

  componentDidMount() {
    document
      .getElementsByClassName("PageRender")[0]
      .addEventListener("updatePageView", this.updatePageView, false);
  }

  updatePageView = e => {
    this.setState({
      pageContent: this.renderContent(e.detail)
    });
  };

  loadPage(pageId) {
    this.getPageContent(pageId).then(response => {
      if (response.status == "success") {
        this.setState({
          pageContent: this.renderContent(response.data.content)
        });
        event = new CustomEvent("updateBreadcrumb", {
          detail: response.data,
          bubbles: true
        });
        document.getElementsByClassName("PageRender")[0].dispatchEvent(event);
      } else {
        this.setState({ pageContent: this.renderContent([]) });
      }
    });
  }

  buttonAction = action => {
    if (action.page_id) {
      this.itemClick(undefined, action.page_id);
    } else if (action.content) {
      if (action.content[0].form_id) {
        this.getFormContents(action.content[0].form_id).then(response => {
          this.setState({
            pageContent: this.renderContent([
              {
                form_id: action.content[0].form_id,
                content: response,
                type: "Form"
              }
            ])
          });
        });
      } else {
        this.setState({
          pageContent: this.renderContent(action.content)
        });
      }
    }
    event = new CustomEvent("updateBreadcrumb", {
      detail: action,
      bubbles: true
    });
    document.getElementsByClassName("PageRender")[0].dispatchEvent(event);
  };

  itemClick = (dataItem, itemContent) => {
    this.props.updatePage(itemContent);
  };

  renderEmpty() {
    return [<React.Fragment key={1} />];
  }

  renderButtons = (e, action) => {
    var actionButtons = [];
    Object.keys(action).map(function(key, index) {
      actionButtons.push(
        <abbr title={action[key].name} key={index}>
          <button
            type="button"
            className=" btn manage-btn k-grid-edit-command"
            onClick={() => this.buttonAction(action[key])}
          >
            {action[key].icon ? (
              <i className={action[key].icon + " manageIcons"}></i>
            ) : (
              action[key].name
            )}
          </button>
        </abbr>
      );
    }, this);
    return actionButtons;
  };

  prepareDataRoute = data => {
    if (typeof data == "string") {
      var result = data.replace("{{app_id}}", this.appId);
      return result;
    } else {
      return data;
    }
  };

  async getFormContents(form_id) {
    let helper = this.core.make("oxzion/restClient");
    let formData = await helper.request(
      "v1",
      "/app/" + this.appId + "/form/" + form_id,
      {},
      "get"
    );
    return JSON.parse(formData.data.template);
  }

  async getPageContent(pageId) {
    // call to api using wrapper
    let helper = this.core.make("oxzion/restClient");
    let pageContent = await helper.request(
      "v1",
      "/app/" + this.appId + "/page/" + pageId,
      {},
      "get"
    );
    return pageContent;
  }

  componentDidUpdate(prevProps) {
    if (this.props.pageId !== prevProps.pageId) {
      this.setState({ pageContent: [] });
      this.setState({ pageId: this.props.pageId });
      this.loadPage(this.props.pageId);
    }
  }

  renderContent(data) {
    var content = [];
    for (var i = 0; i < data.length; i++) {
      switch (data[i].type) {
        case "Form":
          content.push(
            <FormRender
              key={i}
              core={this.core}
              appId={this.appId}
              content={data[i].content}
              formId={data[i].form_id}
              config={this.menu}
            />
          );
          break;
        case "List":
          var itemContent = data[i].content;
          var columnConfig = itemContent.columnConfig;
          if (itemContent.actions) {
            if (columnConfig[columnConfig.length - 1].title == "Actions") {
              null;
            } else {
              columnConfig.push({
                title: "Actions",
                cell: e => this.renderButtons(e, itemContent.actions),
                filterCell: e => this.renderEmpty()
              });
            }
          }
          var dataString = this.prepareDataRoute(itemContent.data);
          content.push(
            <OX_Grid
              key={i}
              osjsCore={this.core}
              data={dataString}
              // onRowClick={dataItem => {
              //   itemContent.actions
              //     ? this.itemClick(dataItem, itemContent.actions.view)
              //     : null;
              // }}
              filterable={itemContent.filterable}
              reorderable={itemContent.reorderable}
              resizable={itemContent.resizable}
              pageable={itemContent.pageable}
              sortable={itemContent.sortable}
              columnConfig={columnConfig}
            />
          );
          break;
        case "DocumentViewer":
          var itemContent = data[i].content;
          var url =
            "app/" + this.appId + "/file/" + itemContent.fileId + "/document";
          console.log(url);
          content.push(
            <DocumentViewer key={i} osjsCore={this.core} url={url} />
          );
          break;
        default:
          content.push(
            <Document
              key={i}
              core={this.core}
              key={i}
              appId={this.appId}
              content={data[i].content}
              config={this.menu}
            />
          );
          break;
      }
    }
    if (content.length > 0) {
      return content;
    } else {
      content.push(<h2>No Content Available</h2>);
    }
    return content;
  }

  render() {
    if (this.state.pageContent && this.state.pageContent.length > 0) {
      return (
        <div id={this.contentDiv} className="AppBuilderPage">
          {this.state.pageContent}
        </div>
      );
    }
    return (
      <div className="loaderAnimation">
        <Loader type="Circles" color="#00BFFF" height={100} width={100} />
      </div>
    );
  }
}

export default Page;
