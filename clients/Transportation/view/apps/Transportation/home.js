import {React,ReactDOM,LeftMenuTemplate,FormRender} from "oxziongui";
import { appId as application_id } from "./metadata.json";
import "./index.scss";

class Home extends React.Component {
  constructor(props) {
    super(props);
    this.core = this.props.args;
    this.helper = this.core.make("oxzion/restClient");
    this.params = this.props.params;
    this.proc = this.props.proc;
    this.state = {};
  }

  render() {
    return (
      <div style={{ height: "inherit" }}>
        
          <LeftMenuTemplate
            core={this.core}
            params={this.params}
            appId={application_id}
            proc={this.proc}
          />
      </div>
    );
  }
}
export default Home;
