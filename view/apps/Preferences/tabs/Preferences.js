import React, { Component } from "react";
import Timezones from "./Timezones";
import M from "materialize-css";

class Preferences extends Component {
  constructor(props) {
    super(props);
    this.core = this.props.args;
    this.userprofile = this.core.make('oxzion/profile').get();
    this.state = {
      file: null,
      timez: "",
      fields: {},
      errors: {},
      initialized: -1

    };


    this.getPreferences().then(response => {
      console.log(response);
      this.setState({fields :response.key.preferences});
      console.log(this.state.fields);
  
   });

    this.handleChange = this.handleChange.bind(this);
    this.handleSubmit = this.handleSubmit.bind(this);
  }

async getPreferences() {
    // call to api using wrapper
    let userpreferences = await this.core.make("oxzion/profile").get();

    if (this.state.initialized < 0) {
      this.setState({ initialized: this.state.initialized + 1 });
    }
    return userpreferences;
  }

  componentDidMount(){
  var selectElems = document.querySelectorAll("select");
  var instances = M.FormSelect.init(selectElems, { classes: "createSelect" });

  
  var selectElems1 = document.querySelectorAll(".tooltipped");
  var instances1 = M.Tooltip.init(selectElems1, { position: 'right' });

  }

  
  handleChange(e) {
    let fields = this.state.fields;
    fields[e.target.name] = e.target.value;
    this.setState({
      fields
    });
  }

  handleSubmit(event) {
    event.preventDefault();
  
    const formData = {};
      Object.keys(this.state.fields).map(key => {
        formData[key] = this.state.fields[key];
      });
    console.log("-->", formData);

    let preferencedata={"preferences":JSON.stringify(formData)};
    console.log(preferencedata);


    let helper = this.core.make("oxzion/restClient");

      let pref = helper.request(
        "v1",
        "/user/" + this.userprofile.key.id,preferencedata,
        "put"
      );
      console.log("done");
      if (pref.status == "error") {
        alert(pref.message);
      }else{
        alert("Successfully Updated");
        

      }

  }


  init() {}
  render() {
    const self = this;
    window.setTimeout(function() {
      if (self.state.initialized === 0) {
        var selectTime = document.querySelectorAll("select");
        var instances = M.FormSelect.init(selectTime, {
          classes: "createSelect"
        });

        self.setState({ initialized: 1 });
      }
    }, 0);
    return (
      <div>
          <form onSubmit={this.handleSubmit}>
          <div className="row">
            <div className="col s12">
              <div className="input-field col s3">
                <label id="name" style={{paddingTop:"6px", fontSize: "15px", color:"#00004d", fontFamily:"trajan"}}><b>Sound Notification</b></label>
              </div>
              <div className="input-field col s1">
                <label id="name">
                  <input
                    type="radio"
                    name="soundnotification"
                    value="true"
                    onChange={this.handleChange}
                    ref="soundnotification"
                    checked={this.state.fields['soundnotification'] == "true"}
                    
                  />
                  <span className="m-2">On</span>
                </label>
              </div>
              <div className="input-field col s1">
                <label id="name">
                  <input
                    type="radio"
                    name="soundnotification"
                    value="false"
                    onChange={this.handleChange}
                    ref="soundnotification"
                    checked={this.state.fields['soundnotification'] == "false"}
                  />
                  <span>Off</span>
                </label>
              </div>
            </div>
          </div>

          <div>
            <div className="row">
              <div className="col s12">
                <div className="input-field col s3">
                  <label id="name" style={{paddingTop:"6px", fontSize: "15px", color:"#00004d", fontFamily:"trajan"}}><b>Email Alerts</b></label>
                </div>
                <div className="input-field col s1">
                  <label id="name">
                    <input
                      type="radio"
                      name="emailalerts"
                      value="true"
                      onChange={this.handleChange}
                      ref="emailalerts"
                      checked={this.state.fields['emailalerts'] == "true"}
                    />
                    <span className="m-2">On</span>
                  </label>
                </div>
                <div className="input-field col s1">
                  <label id="name">
                    <input
                      type="radio"
                      name="emailalerts"
                      value="false"
                      onChange={this.handleChange}
                      ref="emailalerts"
                      checked={this.state.fields['emailalerts'] == "fasle"}
                    />
                    <span>Off</span>
                  </label>
                </div>
              </div>
            </div>
          </div>

          <div className="row" id="row1" style={{paddingBottom:0}}>
            <div className="col s12">
            <div className="input-field col s3">

              <label id="name" style={{paddingTop:"10px", fontSize: "15px", color:"#00004d", fontFamily:"trajan"}}><b>Local Time Zone</b></label>
              </div>
              <div className="input-field col s3">
              <select
                value={this.state.fields['timezone']}
                onChange={this.handleChange}
                ref="timezone"
                name="timezone"
                className="timezone"
              >
                {Timezones.map((timezone, key) => (
                  <option key={key} value={timezone.name}>
                    {timezone.name}
                  </option>
                ))}
              </select>
            </div>
            </div>
          </div>

          <div className="row" id="row2" style={{paddingBottom:0}}>
                <div className="col s12">
                <div className="input-field col s3">

                <label id="name" style={{paddingTop:"10px", fontSize: "15px", color:"#00004d", fontFamily:"trajan"}}><b>Date Format</b></label>
                  </div>
                <div className="input-field col s3">
                <input
                  type="text"
                  name="dateformat"
                  ref="dateformat"
                  pattern={"['d','m','y']{1,4}"+"['/','-']"+"['d','m','y']{1,4}"+"['/','-']"+"['d','m','y']{1,4}"}
                  value={this.state.fields['dateformat']}
                  onChange={this.handleChange}
                  className="validate"
                />               

                </div>
                <div className="input-field col s2">
                <a className="btn-floating waves-effect waves-light tooltipped" data-html="true" data-position="right" data-tooltip="dd-mm-yyyy - 01-02-2012<br/>
                                                                                                  d-mmm-yyyy - 1-Feb-2012<br/>
                                                                                                  yy-m-dd - 12-2-01<br/>
                                                                                                  dd/mmmm/yyyy - 01/Febraury/2012<br/>
                                                                                                  Use either / or -"><i className="material-icons">info_outline</i></a>
                </div> 
                </div>
          </div>

          <div className="row">
            <div className="col s12 input-field">
              <button className="btn waves-effect waves-light waves-effect black" type="submit">
                Save
              </button>
            </div>
          </div>
        </form>
      </div>
    );
  }
}
export default Preferences;