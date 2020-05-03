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
            <div className="padi" style={{ float: "right" }}>
              <img src="../img/padi.png" />
              <h4>PADI ENDORSED INSURANCE</h4>
            </div>
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
                  <div id="formio"></div>
                </div>
                <div id="login">
                  <div className="loginBlock">
                    <div className="LoginDiv">
                      <h1>Welcome Back!</h1>
                      <div className="floating-label">
                        <input
                          required
                          id="username_field"
                          name="username"
                          title="Please enter your Username"
                          autoComplete="off"
                          placeholder="Username"
                        />
                        <label htmlFor="username_field">Username:</label>
                      </div>
                      <div className="floating-label">
                        <input
                          type="password"
                          id="password_field"
                          name="password"
                          title="Please enter your Password"
                          placeholder="Password"
                          required
                          autoComplete="off"
                        />
                        <label htmlFor="password_field">Password:</label>
                      </div>
                      <button title="Login" className="button button-block loginButton">
                        Log In
                      </button>
                      <a title="Forgot Password?Please click here to change Password" className="resetPassword">Forgot your password?</a>
                    </div>
                  </div>
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
