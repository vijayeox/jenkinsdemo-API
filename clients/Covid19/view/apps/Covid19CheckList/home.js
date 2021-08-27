import { React, ReactDOM, LeftMenuTemplate, FormRender } from "oxziongui";
import { appId as application_id } from "./metadata.json";
import "./index.scss";

class Home extends React.Component {
  constructor(props) {
    super(props);
    this.core = this.props.args;
    this.helper = this.core.make("oxzion/restClient");
    this.userprofile = this.core.make("oxzion/profile").get().key;
    this.params = this.props.params;
    this.proc = this.props.proc;
    this.state = {
      cacheID: undefined,
      formContent: undefined,
      cachePage: undefined,
      formID: undefined,
      cacheData: undefined,
      workflowId: undefined,
      showMenuPage: undefined
    };
  }

  componentDidMount() {
    this.getCacheData().then((cacheResponse) => {
      var cache = cacheResponse.data;
      if (cache) {
        if (cache.workflow_uuid) {
          this.setState({ workflowId: cache.workflow_uuid });
          this.getFormData(cache.workflow_uuid).then((formResponse) => {
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

  postSubmitCallback = (data) => {
    this.deleteCacheData().then((response) => {
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
      <div style={{ height: "inherit", overflow: "auto" }}>
        {this.state.formContent ? (
          <div className="formContent">
            <FormRender
              postSubmitCallback={this.postSubmitCallback}
              core={this.core}
              page={this.state.cachePage}
              appId={application_id}
              workflowId={this.state.workflowId}
              formId={this.state.formID}
              content={this.state.formContent}
              data={this.state.cacheData}
            />
          </div>
        ) : this.state.showMenuPage ? (
          <LeftMenuTemplate
            core={this.core}
            params={this.params}
            proc={this.proc}
            appId={application_id}
          />
        ) : null}
        <div id="floater">
          <img src="/apps/DiveInsurance/img/poweredby.png"></img>
          {this.userprofile.privileges.MANAGE_POLICY_APPROVAL_WRITE == true ? (
            <div className="helpText">
              <p>Helpline Ph: +1 216-452-0324 |</p>
              <p>Email: hub-support@eoxvantage.com</p>
            </div>
          ) : null}
        </div>
      </div>
    );
  }
}
export default Home;
