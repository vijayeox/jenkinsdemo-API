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
              "\n   .logo{\n    text-align: center;\n    margin:50px;\n  }\n  .logo img{\n    width:auto;\n    height: 150px;\n  }\n  .mockup-content {\n    text-align: center;\n  }\n"
          }}
        />
        <div className="container">
          <div className="logo">
            <img src="../img/logo.png" />
          </div>
          <section>
            <div className="form">
              <ul className="tab-group">
                <li className="tab active">
                  <a href="#signup">Register</a>
                </li>
                <li className="tab">
                  <a href="#login">Log In</a>
                </li>
              </ul>
              <div className="tab-content">
                <div id="signup">
                  <h1>Register</h1>
                  <div id="formio"></div>
                </div>
                <div id="login">
                  <h1>Welcome Back!</h1>
                  <form action="/" method="post">
                    <div className="field-wrap">
                      <label>
                        Username<span className="req">*</span>
                      </label>
                      <input type="email" required autoComplete="off" />
                    </div>
                    <div className="field-wrap">
                      <label>
                        Password<span className="req">*</span>
                      </label>
                      <input type="password" required autoComplete="off" />
                    </div>
                    <p className="forgot">
                      <a href="#">Forgot Password?</a>
                    </p>
                    <button className="button button-block">Log In</button>
                  </form>
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
