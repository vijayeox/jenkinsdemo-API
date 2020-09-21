import React from "react";
import { Tab, Tabs, TabList, TabPanel } from "react-tabs";
// import {FormRender} from "@oxzion/gui";

export default class Register extends React.Component {
  constructor(props) {
    super(props);
    this.core = this.props.core;
    this.proc = this.props.proc;
    // var metadata = JSON.parse(this.proc.metadata);
    this.productName = this.props.productName;
    this.appId = this.proc.metadata.appId;
    this.formContent = this.props.form;
  }
  render() {
    return (
      <div>
        <style
          type="text/css"
          dangerouslySetInnerHTML={{
            __html:
              "\n   .logo{\n    text-align: center;\n    margin:25px;\n    height:100px;\n  }\n  .logo > img{\n    width:auto;\n    height: 100px;\n  }\n  .mockup-content {\n    text-align: center;\n  }\n"
          }}
        />
        <div className="container">
          <div className="logo">
            <img src="../img/logo.png" style={{ float: "left" }} />
          </div>
          <section>
            <div className="form">
              <ul className="tab-group">
                <li className="tab active">
                  <a href="#signup">Visitor Checklist</a>
                </li>
              </ul>
              <div className="tab-content">
                <div id="signup">
                  <div id="formio"></div>
                </div>
              </div>
            </div>
          </section>
        </div>
      </div>
    );
  }
}

module.exports = Register;
