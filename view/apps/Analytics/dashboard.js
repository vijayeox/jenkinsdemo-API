import React from 'react';
import ReactDOM from 'react-dom';
import { dashboard as section } from './metadata.json';
import osjs from 'osjs';
import Swal from "sweetalert2";
import {Notification ,DashboardViewer} from './GUIComponents'
import { Button, Form, Col, Row } from 'react-bootstrap'
import '../../gui/src/public/css/sweetalert.css';
import Flippy, { FrontSide, BackSide } from 'react-flippy';
import DashboardEditorModal from '../../gui/src/components/Modals/DashboardEditorModal'
import DashboardEditor from "./dashboardEditor"

class Dashboard extends React.Component {
  constructor(props) {
    super(props);
    this.core = this.props.args;
    this.userProfile = this.core.make("oxzion/profile").get();

    this.props.setTitle(section.title.en_EN);
    this.state = {
      showModal: false,
      modalType: "",
      modalContent: {},
      flipped: false,
      uuid: "",
      dashList: [],
      inputs: {}
    };
    this.appId = this.props.app;
    this.proc = this.props.proc;
    this.refresh = React.createRef();
    this.notif = React.createRef();
    this.restClient = osjs.make('oxzion/restClient');
    this.loader = null;
  }
  componentWillMount() {
    this.fetchDashboards()
  }
  async getUserDetails(uuid) {
    let helper2 = this.core.make("oxzion/restClient");
    let rolesList = await helper2.request(
      "v1",
      "organization/" + this.props.selectedOrg + "/user/" + uuid + "/profile",
      {},
      "get"
    );
    return rolesList;
  }
  dashboardOperation = (e, operation) => {
    if (operation === "Delete" || operation === "Activate") {
      this.setState({ showModal: true, modalContent: e, modalType: operation })
    }
    else {
      this.setState({ showModal: true, modalContent: e, modalType: operation, uuid: e.uuid })
    }
  }

  async fetchDashboards() {
    let that = this
    let helper = this.restClient;
    let inputs = this.state.inputs !== undefined ? this.state.inputs : undefined
    let response = await helper.request('v1', 'analytics/dashboard', {}, 'get');
    if (response.data.length >= 0) {
      that.setState({ dashList: response.data, uuid: '' })
      inputs["dashname"] !== undefined ?
        response.data.map(dash => {
          dash.name === inputs["dashname"]["name"] ?
            (inputs["dashname"] = dash, that.setState({ inputs, dashList: response.data, uuid: dash.uuid }))
            : that.setState({ inputs: this.state.inputs })
        })
        : null
    }
  }
  setTitle(title) { }

  handleChange(event) {
    let inputs = {}
    inputs = { ...this.state.inputs }
    let name
    let value
    if (event.target.name === "dashname") {
      name = event.target.name
      value = JSON.parse(event.target.value)
      
      var element = document.getElementById("dashboard-editor-div");
      element.classList.add("hide-dash-editor");
    }
    else {
      name = event.target.name
      value = event.target.value
    }
    inputs[name] = value
    this.setState({ inputs: inputs, uuid: value["uuid"] })
  }

  deleteDashboard() {
    let inputs = this.state.inputs
    inputs["dashname"] = undefined
    this.setState({ inputs })
  }
  editDashboard() {
    var element = document.getElementById("dashboard-editor-div");
    element.classList.remove("hide-dash-editor"); //fixes dropdown bug in mozilla firefox cused due to charts
    this.setState({ flipped: true, uuid: this.state.uuid })
  }

  createDashboard() {
    var element = document.getElementById("dashboard-editor-div");
    element.classList.remove("hide-dash-editor"); //fixes dropdown bug in mozilla firefox cused due to charts
    let inputs = { ...this.state.inputs }
    inputs["dashname"] !== undefined ? delete inputs.dashname : null
    this.setState({ flipped: true, uuid: "", inputs: inputs })
  }
  render() {
    return (
      <div className="dashboard">
        <Notification ref={this.notif} />
        <Flippy
          flipDirection="horizontal" // horizontal or vertical
          isFlipped={this.state.flipped}
          flipOnClick={false}
          style={{ width: '100%', height: '100vh' }} /// these are optional style, it is not necessary
        >
          <FrontSide>
            {this.userProfile.key.privileges.MANAGE_DASHBOARD_CREATE ?
              <Button className="create-dash-btn" onClick={() => this.createDashboard()} title="Add New Dashboard"><i class="fa fa-plus" aria-hidden="true"></i> Create Dashboard</Button>
              : null
            }
            {this.state.dashList != undefined ? this.state.dashList.length > 0 ?
            <>
            <div className="dash-manager-bar">
              <Form className="dashboard-manager-items">
                <Row>
                  <Col lg="4" md="4" sm="4">
                    <Form.Group as={Row}>
                      <Col>
                          <Form.Control
                            as="select"
                            onChange={(e) => this.handleChange(e)}
                            name="dashname"
                            value={this.state.inputs["dashname"] !== undefined && this.state.inputs["dashname"] !== {} ? JSON.stringify(this.state.inputs["dashname"])["name"] : -1}
                          >
                            <option disabled value={-1} key={-1} style={{ color: "grey" }}>Select Dashboard</option>
                            {this.state.dashList !== undefined ? this.state.dashList.map((option, index) => (
                              <option key={option.uuid} value={JSON.stringify(option)}>{option.name}</option>
                            )) : null}
                          </Form.Control>
                      </Col>
                    </Form.Group>
                  </Col>
                  <div className="dash-manager-buttons">
                    {this.state.uuid !== "" ?
                      <>
                        {this.userProfile.key.privileges.MANAGE_DASHBOARD_WRITE ?
                          <Button onClick={() => this.editDashboard()} title="Edit Dashboard">
                            <i class="fa fa-pen" aria-hidden="true"></i>
                          </Button>
                          : null
                        }
                        {
                          this.userProfile.key.privileges.MANAGE_DASHBOARD_DELETE ?
                            <Button onClick={() => this.dashboardOperation(this.state.inputs["dashname"], "Delete")} title="Delete Dashboard">
                              <i class="fa fa-trash" aria-hidden="true"></i>
                            </Button>
                            : null
                        }
                      </>
                      : null
                    }
                  </div>

                </Row>
              </Form>
            </div>
            
            <div className="dashboard-viewer-div">
              <div className="dashboard-preview-tab">
                 <span>Dashboard Previewer</span>
              </div>
              <div className="dasboard-viewer-content">
              <DashboardViewer
                key={this.state.uuid}
                uuid={this.state.uuid}
                core={this.core}
                setTitle={this.props.setTitle}
                proc={this.props.proc}
              />
              </div>
             
            </div>
            </>
            :
            <div className="dashboard-viewer-div" style={{textAlign:"center",fontWeight:"bolder",fontSize:"20px"}}>
                NO DASHBOARDS FOUND
          </div>
            :null}
          </FrontSide>
          <BackSide>
            <div id="dashboard-editor-div">
            <DashboardEditor
              args={this.core}
              notif={this.notif}
              setTitle={this.setTitle}
              key={this.state.uuid}
              dashboardId={this.state.uuid}
              flipCard={(status) => {
                if (status === "Saved") {
                  //refreshing the dashboardData
                  this.fetchDashboards()
                }
                else if (status === "") {
                  var element = document.getElementById("dashboard-editor-div");
                  element.classList.add("hide-dash-editor");
                }
                this.setState({ flipped: false })
              }}
            />
            </div>
          </BackSide>
        </Flippy>
        <DashboardEditorModal
          osjsCore={this.core}
          modalType={this.state.modalType}
          show={this.state.showModal}
          onHide={() => { this.fetchDashboards(), this.setState({ showModal: false }) }}
          content={this.state.modalContent}
          notification={this.notif}
          refreshGrid={this.refresh}
          deleteDashboard={() => this.deleteDashboard()}
        />
      </div>
    );
  }
}

export default Dashboard;

