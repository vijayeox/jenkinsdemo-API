import React from "react";
import { appId as application_id } from "./metadata.json";
import LeftMenuTemplate from "OxzionGUI/LeftMenuTemplate";
import FormRender from "OxzionGUI/components/App/FormRender";
import "./index.scss";

class Home extends React.Component {
  constructor(props) {
    super(props);
    this.core = this.props.args;
    this.helper = this.core.make("oxzion/restClient");
    this.params = this.props.params;
    this.state = {
      cacheID: undefined,
      formContent: undefined,
      cachePage: undefined,
      formID: undefined,
      cacheData: undefined,
      showMenuPage: undefined
    };
  }

  componentDidMount() {
    this.getCacheData().then(cacheResponse => {
      var cache = cacheResponse.data;
      if (cache) {
        if (cache.workflow_uuid) {
          this.getFormData(cache.workflow_uuid).then(formResponse => {
            if (formResponse.data) {
              this.setState({
                formContent: JSON.parse(formResponse.data.template),
                cachePage: cache.page,
                formID: formResponse.data.id,
                cacheData: cache
              });
            } else {
              this.setState({
                showMenuPage: true
              });
            }
          });
        } else {
          this.setState({
            showMenuPage: true
          });
        }
      } else {
        this.setState({
          showMenuPage: true
        });
      }
    });
  }

  async getCacheData() {
    let cacheData = await this.helper.request(
      "v1",
      "/app/" + application_id + "/cache",
      {},
      "get"
    );
    return cacheData;
  }

  async deleteCacheData() {
    let cacheData = await this.helper.request(
      "v1",
      "/app/" + application_id + "/deletecache",
      {},
      "delete"
    );
    return cacheData;
  }

  async updateCacheData() {
    let cacheData = await this.helper.request(
      "v1",
      "/app/" + application_id + "/deletecache",
      {},
      "delete"
    );
    return cacheData;
  }

  async getFormData(workflow_id) {
    let formData = await this.helper.request(
      "v1",
      "/app/" + application_id + "/workflow/" + workflow_id + "/startform",
      {},
      "get"
    );
    return formData;
  }

  postSubmitCallback = data => {
    this.deleteCacheData().then(response => {
      if (response.status == "success") {
        this.setState({
          formContent: undefined,
          formID: undefined,
          showMenuPage: true
        });
      }
    });
  };

  render() {
    return (
      <div style={{ height: "inherit" }}>
        {this.state.formContent ? (
          <div className="formContent">
            <FormRender
              postSubmitCallback={this.postSubmitCallback}
              core={this.core}
              page={this.state.cachePage}
              appId={application_id}
              formId={this.state.formID}
              content={this.state.formContent}
              data={this.state.cacheData}
            />
          </div>
        ) : this.state.showMenuPage ? (
          <LeftMenuTemplate
            core={this.core}
            params={this.params}
            appId={application_id}
          />
        ) : null}
      </div>
    );
  }
}
export default Home;
