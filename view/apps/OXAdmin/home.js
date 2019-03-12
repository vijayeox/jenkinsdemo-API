import React from "react";
import ReactDOM from "react-dom";

import "jquery/dist/jquery.js";
import $ from "jquery";

import Organization from "./modules/Organization";
import Project from "./modules/Project";
import User from "./modules/User";
import Group from "./modules/Group";
import Role from "./modules/Roles";

class Home extends React.Component {
  constructor(props) {
    super(props);
    this.core = this.props.args;
    this.state = {
      value: "5"
    };
    this.handleChange = this.handleChange.bind(this);
  }

  componentDidMount() {
    M.AutoInit();

    $(document).ready(function () {
      $("#componentsBox").hide();

      $(document).on("click", ".orgButton", function () {
        $(".DashBG").fadeOut(),
          $("#componentsBox").show(),
          $("#organization").fadeIn(),
          $("#groupPage").hide(),
          $("#project").hide(),
          $("#userPage").hide();
        $("#rolePage").hide();
      });

      $(document).on("click", ".groupButton", function () {
        $(".DashBG").fadeOut(),
          $("#componentsBox").show(),
          $("#organization").hide(),
          $("#groupPage").fadeIn(),
          $("#project").hide(),
          $("#userPage").hide();
        $("#rolePage").hide();
      });

      $(document).on("click", ".prjButton", function () {
        $(".DashBG").fadeOut(),
          $("#componentsBox").show(),
          $("#project").show(),
          $("#userPage").hide(),
          $("#organization").hide(),
          $("#userPage").hide();
        $("#rolePage").hide();
        $("#groupPage").hide();
      });

      $(document).on("click", ".userButton", function () {
        $(".DashBG").fadeOut(),
          $("#componentsBox").show(),
          $("#project").hide(),
          $("#organization").hide(),
          $("#groupPage").hide(),
          $("#userPage").show();
        $("#rolePage").hide();
      });

      $(document).on("click", ".roleButton", function () {
        $(".DashBG").fadeOut(),
          $("#componentsBox").show(),
          $("#project").hide(),
          $("#organization").hide(),
          $("#groupPage").hide(),
          $("#userPage").hide();
        $("#rolePage").show();
      });

      $(document).on("click", ".goBack", function () {
        $("#componentsBox").hide(), $(".DashBG").show();
      });
    });
  }

  handleChange(event) {
    this.setState({
      value: event.target.value
    });
  }

  createBlock = () => {
    let table = [];

    if (this.state.value == 1) {
      table.push(
        <div key="1" style={{ display: "inline-grid" }}>
          <div className="block d1" onClick={this.orgClick}>
            <img src="apps/OXAdmin/org.svg" className="orgButton App-logo" />
          </div>
          <div className="titles">Organization</div>
        </div>
      );
    } else if (this.state.value == 2) {
      table.push(
        <div key="2">
          <div style={{ display: "inline-grid" }}>
            <div className="block d1" onClick={this.orgClick}>
              <img src="apps/OXAdmin/org.svg" className="orgButton App-logo" />
            </div>
            <div className="titles">Organization</div>
          </div>

          <div style={{ display: "inline-grid" }}>
            <div className="block d1" onClick={this.groupClick}>
              <img
                src="apps/OXAdmin/group.svg"
                className="groupButton App-logo"
              />
            </div>
            <div className="titles">Groups</div>
          </div>
        </div>
      );
    } else if (this.state.value == 4) {
      table.push(
        <div key="4" >
          <div style={{ display: "inline-grid" }}>
            <div className="block d1" onClick={this.orgClick}>
              <img src="apps/OXAdmin/org.svg" className="orgButton App-logo" />
            </div>
            <div className="titles">Organization</div>
          </div>

          <div style={{ display: "inline-grid" }}>
            <div className="block d1" onClick={this.groupClick}>
              <img
                src="apps/OXAdmin/group.svg"
                className="groupButton App-logo"
              />
            </div>
            <div className="titles">Groups</div>
          </div>

          <div style={{ display: "inline-grid" }}>
            <div className="block d1" onClick={this.prjClick} >
              <img
                src="apps/OXAdmin/101-project.svg"
                className="prjButton App-logo"
              />
            </div>
            <div className="titles">Projects</div>
          </div>

          <div style={{ display: "inline-grid" }}>
            <div className="block d1" onClick={this.userClick}>
              <img
                src="apps/OXAdmin/115-manager.svg"
                className="userButton App-logo"
              />
            </div>
            <div className="titles">Users</div>
          </div>
        </div>
      );
    } else if (this.state.value == 5) {
      table.push(
        <div key="5">
          <div style={{ display: "inline-grid" }}>
            <div className="block d1" onClick={this.orgClick}>
              <img src="apps/OXAdmin/org.svg" className="orgButton App-logo" />
            </div>
            <div className="titles">Organization</div>
          </div>

          <div style={{ display: "inline-grid" }}>
            <div className="block d1" onClick={this.groupClick}>
              <img
                src="apps/OXAdmin/group.svg"
                className="groupButton App-logo"
              />
            </div>
            <div className="titles">Groups</div>
          </div>

          <div style={{ display: "inline-grid" }}>
            <div className="block d1" onClick={this.prjClick}>
              <img
                src="apps/OXAdmin/101-project.svg"
                className="prjButton App-logo"
              />
            </div>
            <div className="titles">Projects</div>
          </div>

          <div style={{ display: "inline-grid" }}>
            <div className="block d1" onClick={this.userClick}>
              <img
                src="apps/OXAdmin/115-manager.svg"
                className="userButton App-logo"
              />
            </div>
            <div className="titles">Users</div>
          </div>
          <div style={{ display: "inline-grid" }}>
            <div className="block d1" onClick={this.roleClick}>
              <img
                src="apps/OXAdmin/005-workflow.svg"
                className="roleButton App-logo"
              />
            </div>
            <div className="titles">Roles</div>
          </div>
        </div>
      );
    } else if (this.state.value == 6) {
      table.push(
        <div key="6">
          <div style={{ display: "inline-grid" }}>
            <div className="block d1" onClick={this.orgClick}>
              <img src="apps/OXAdmin/org.svg" className="orgButton App-logo" />
            </div>
            <div className="titles">Organization</div>
          </div>

          <div style={{ display: "inline-grid" }}>
            <div className="block d1" onClick={this.groupClick}>
              <img
                src="apps/OXAdmin/group.svg"
                className="groupButton App-logo"
              />
            </div>
            <div className="titles">Groups</div>
          </div>

          <div style={{ display: "inline-grid" }}>
            <div className="block d1" onClick={this.prjClick}>
              <img
                src="apps/OXAdmin/101-project.svg"
                className="prjButton App-logo"
              />
            </div>
            <div className="titles">Projects</div>
          </div>

          <div style={{ display: "inline-grid" }}>
            <div className="block d1" onClick={this.userClick}>
              <img
                src="apps/OXAdmin/115-manager.svg"
                className="userButton App-logo"
              />
            </div>
            <div className="titles">Users</div>
          </div>
          <div style={{ display: "inline-grid" }}>
            <div className="block d1" onClick={this.roleClick}>
              <img
                src="apps/OXAdmin/005-workflow.svg"
                className="roleButton App-logo"
              />
            </div>
            <div className="titles">Roles</div>
          </div>

          <div style={{ display: "inline-grid" }}>
            <div className="block d1 d2">
              <img src="apps/OXAdmin/056-development-1.svg" />
            </div>
            <div className="titles">App Builder</div>
          </div>
        </div>
      );
    }

    return table;
  };


  orgClick = (e) => {
    ReactDOM.render(<Organization args={this.core}  unmountMe={this.handleChildUnmount}/>,document.getElementById('componentsBox'));
  }

  groupClick = (e) => {
    ReactDOM.render(<Group args={this.core} />,document.getElementById('componentsBox'));
  }

  prjClick = (e) => {
    ReactDOM.render(<Project args={this.core} />,document.getElementById('componentsBox'));
  }

  userClick = (e) => {
    ReactDOM.render(<User args={this.core} />,document.getElementById('componentsBox'));
  }

  roleClick = (e) => {
    ReactDOM.render(<Role args={this.core} />,document.getElementById('componentsBox'));
  }

  render() {
    return (
      <div>
        <div
          className="DashBG"
          style={{
            paddingBottom: "200px",
            backgroundImage: "url(apps/OXAdmin/wait.jpg)",
            backgroundSize: "cover"
          }}
        >
          <center>
            <div>
              <div id="adminHeader">Admin Control Center</div>
            </div>
            <div className="container">
              <select
                defaultValue="0"
                id="dropdown"
                onChange={this.handleChange}
              >
                <option value="0" disabled>
                  Choose your role in the company
                </option>
                <option value="1">Employee</option>
                <option value="2">IT Support</option>
                <option value="4">Manager</option>
                <option value="5">Admin</option>
                <option value="6">Super Admin</option>
              </select>
            </div>
            <div className="container">
            {this.createBlock()}
            </div>
          </center>
        </div>
        <div id="componentsBox">
        </div>
      </div>
    );
  }
}
export default Home;
