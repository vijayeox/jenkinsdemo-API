import React, { useEffect, useState } from 'react'
import DatePicker from 'react-datepicker'
import { dashboard, dateFormat, dateTimeFormat } from '../metadata.json';
import { Form, Row, Button } from 'react-bootstrap'
import "react-datepicker/dist/react-datepicker.css";
import Select from 'react-select/creatable'

const customStyles = {
    control: base => ({
        ...base,
        height: 38,
        minHeight: 38
    }),
    valueContainer: base => ({
        ...base,
        height: 'inherit',
        minHeight: 'inherit'
    })

};

const FilterFields = function (props) {
    const { filters, index, fieldType, dataType, onUpdate, removeField, field, filterName, filterMode, dateFormat, isDefault } = props;
    const filtersOptions = {
        "dateoperator": [{ "Between": "gte&&lte" }, { "Less Than": "<" }, { "Greater Than": ">" }, { "This Month": "monthly" }, { "This Year": "yearly" }, { "MTD": "mtd" }, { "YTD": "ytd" }],
        "textoperator": [{ "Equals": "==" }, { "Not Equals": "NOT LIKE" }],
        "numericoperator": [{ "Less Than": "<" }, { "Greater Than": ">" }, { "Equals": "==" }, { "Not Equals": "!=" }]
    };
    const dataTypeOptions = [
        "numeric"
    ]

    const removeValue = (e, value) => {
        //remove the filter value on click
        let filterCopy = filters
        let values = filters[index]["value"]
        let filteredValues = values.filter((item) => item.value !== value)
        filterCopy[index]["value"] = filteredValues
        props.setFilterValues(filterCopy)
    }

    const CustomOption = (props) => {
        const {
            children,
            className,
            cx,
            getStyles,
            isDisabled,
            isFocused,
            isSelected,
            innerRef,
            innerProps,
        } = props;
        const { onClick } = innerProps;
        innerProps.onClick = (e) => {
            if (e.target.tagName !== "I") {
                onClick(e)
            }
            console.log("clicked")
        }
        return (
            <div
                ref={innerRef}
                className="custom-react-select-container"
                {...innerProps}
            >
                {/* DONOT CHANGE THE TAGS SPECIFIED BELOW */}
                <span>{children}</span><i class="far fa-times-circle" onClick={(e) => removeValue(e, children)}></i>
            </div>
        );
    };
    const disabledFields = filterMode == "APPLY"
    const visibility = filterMode == "CREATE"
    return (
        <Form.Row>
            <div className="dashboard-filter-field">
                <Form.Group  >
                    <Form.Label>Filter Description</Form.Label>
                    <Form.Control type="text" name="filterName" title={disabledFields ? "*The entered description will be displayed in dashboard viewer as filter name" : null} value={filterName} disabled={disabledFields} onChange={(e) => onUpdate(e, index)} />
                </Form.Group>
            </div>
            {visibility &&
                <div className="dashboard-filter-field">
                    <Form.Group  >
                        <Form.Label>Field Name</Form.Label>
                        <Form.Control type="text" name="field" value={field} disabled={disabledFields} onChange={(e) => onUpdate(e, index)} />
                    </Form.Group>
                </div>
            }
            {visibility &&
                <div className="dashboard-filter-field">
                    <Form.Group  >
                        <Form.Label>Data Type</Form.Label>
                        <Form.Control type="text" value={fieldType} disabled />
                    </Form.Group>
                </div>
            }

            <div className="dashboard-filter-field">
                <Form.Group >
                    <Form.Label>Operator</Form.Label>
                    {
                        dataType === "date" || dataType === "text" || dataType === "numeric"
                            ?
                            <Form.Control as="select" name={"operator"} onChange={(e) => onUpdate(e, index)} value={filters[index] !== undefined ? filters[index]["operator"] : ""}>
                                <option disabled key="-1" value=""></option>
                                {filtersOptions[dataType + 'operator'].map((item, mapindex) => {
                                    return (<option key={mapindex} value={Object.values(item)[0]}>{Object.keys(item)[0]}</option>)
                                })}
                            </Form.Control>
                            :
                            <Form.Control as="select" name={"operator"} onChange={(e) => onUpdate(e, index)} value={filters[index] !== undefined ? filters[index]["operator"] : ""}>
                                <option disabled key="-1" value=""></option>
                            </Form.Control>
                    }

                </Form.Group>
            </div>
            <div className="dashboard-filter-field">
                <Form.Group controlId="formGridPassword">
                    <Form.Label>Default Value</Form.Label><br />
                    {dataType === "date"
                        ?
                        (filters[index]["operator"] !== "gte&&lte" && filters[index]["dateRange"] === false) ?
                            <DatePicker
                                key={index}
                                dateFormat={dateFormat}
                                selected={Date.parse(filters[index]["startDate"])}
                                showMonthDropdown
                                showYearDropdown
                                dropdownMode="select"
                                popperPlacement="bottom"
                                popperModifiers={{
                                    flip: {
                                        behavior: ["bottom"] // don't allow it to flip to be above
                                    },
                                    preventOverflow: {
                                        enabled: false // tell it not to try to stay within the view (this prevents the popper from covering the element you clicked)
                                    },
                                    hide: {
                                        enabled: false // turn off since needs preventOverflow to be enabled
                                    }
                                }}
                                onChange={date => onUpdate(date, index, "startDate")}
                                name="startDate" />
                            :
                            <div className="dates-container">
                                <DatePicker
                                    selected={Date.parse(filters[index]["startDate"])}
                                    dateFormat={dateFormat}
                                    onChange={date => onUpdate(date, index, "startDate")}
                                    selectsStart
                                    startDate={Date.parse(filters[index]["startDate"])}
                                    endDate={Date.parse(filters[index]["endDate"])}
                                    showMonthDropdown
                                    showYearDropdown
                                    popperPlacement="bottom"
                                    popperModifiers={{
                                        flip: {
                                            behavior: ["bottom"] // don't allow it to flip to be above
                                        },
                                        preventOverflow: {
                                            enabled: false // tell it not to try to stay within the view (this prevents the popper from covering the element you clicked)
                                        },
                                        hide: {
                                            enabled: false // turn off since needs preventOverflow to be enabled
                                        }
                                    }}
                                    dropdownMode="select"
                                />
                                <DatePicker
                                    selected={Date.parse(filters[index]["endDate"])}
                                    dateFormat={dateFormat}
                                    onChange={date => onUpdate(date, index, "endDate")}
                                    selectsEnd
                                    startDate={Date.parse(filters[index]["startDate"])}
                                    endDate={Date.parse(filters[index]["endDate"])}
                                    minDate={Date.parse(filters[index]["startDate"])}
                                    showMonthDropdown
                                    showYearDropdown
                                    popperPlacement="bottom"
                                    popperModifiers={{
                                        flip: {
                                            behavior: ["bottom"] // don't allow it to flip to be above
                                        },
                                        preventOverflow: {
                                            enabled: false // tell it not to try to stay within the view (this prevents the popper from covering the element you clicked)
                                        },
                                        hide: {
                                            enabled: false // turn off since needs preventOverflow to be enabled
                                        }
                                    }}
                                    dropdownMode="select"
                                />
                            </div>
                        :
                        <Select
                            selected={filters[index]["value"]["selected"] ? filters[index]["value"].filter(option => option.value == filters[index]["value"]["selected"]) : ""}
                            components={filterMode == "CREATE" && { Option: CustomOption }}
                            styles={customStyles}
                            name="value"
                            id="value"
                            onChange={(e) => onUpdate(e, index, "defaultValue")}
                            value={filters[index]["value"]["selected"] ? filters[index]["value"].filter(option => option.value == filters[index]["value"]["selected"]) : ""}
                            options={filters[index]["value"]}

                        />

                        // <Form.Control type="text" name="value" onChange={(e) => onUpdate(e, index)} value={filters[index] !== undefined ? filters[index]["value"] : ""} />
                    }
                </Form.Group>
            </div>
            {visibility &&
                <div className="dashboard-filter-field">
                    <Form.Group  >
                        <Form.Label>Set Default</Form.Label>
                        <Form.Control type="checkbox" name="isDefault" className="form-checkbox" value={isDefault} defaultChecked={isDefault} onChange={(e) => onUpdate(e, index)} />
                    </Form.Group>
                </div>
            }
            <div className="dash-manager-buttons dashboard-filter-field" style={{ marginBottom: "1em", position: "relative", left: "0px" }}>
                <Form.Group>
                    <Form.Label></Form.Label>
                    <Button className="filter_remove_button" style={{
                        cursor: "pointer",
                        float: "left",
                        verticalAlign: "middle",
                        marginTop: "25px",
                        position: "relative",
                    }} onClick={(e) => removeField(index, fieldType)}><i className="fa fa-minus" aria-hidden="true"></i></Button>
                </Form.Group>
            </div>
        </Form.Row>)
}


class DashboardFilter extends React.Component {
    constructor(props) {
        super(props);
        this.core = this.props.core;
        this.userProfile = this.core.make("oxzion/profile").get();
        this.handleChange = this.handleChange.bind(this);
        this.handleSelect = this.handleSelect.bind(this);
        this.createField = this.createField.bind(this);
        this.removeField = this.removeField.bind(this);

        this.state = {
            input: {},
            focused: null,
            inputFields: [],
            startDate: new Date(),
            createFilterOption: [{ value: "text", label: "Text" }, { value: "date", label: "Date" }, { value: "numeric", label: "Number" }],
            applyFilterOption: this.props.applyFilterOption ? this.props.applyFilterOption : [],
            filters: this.props.filterConfiguration ? this.props.filterConfiguration : [],
            defaultFilters: [],
            applyFilters: [],
            dateFormat: this.userProfile.key.preferences.dateformat,
            dateTimeFormat: dateTimeFormat.title.en_EN,
            showDefaultValue: true,
            // userProfile: this.core.make("oxzion/profile").get()
        }

        console.log("Inside the filter Function: " + this.state.dateFormat);
    }
    

    componentDidUpdate(prevProps,prevState) {
        if ((prevProps.filterConfiguration != this.props.filterConfiguration) || (prevProps.applyFilterOption!=this.props.applyFilterOption)) {
        (this.props.filterMode!="CREATE" && this.props.dashboardStack.length==1) ? this.displayDefaultFilters() : this.setState({ filters: this.props.filterConfiguration, applyFilterOption: this.props.applyFilterOption })
        }
    }

    componentDidMount(props) {
        this.displayDefaultFilters()
    }

    //showing only default filters on load
    displayDefaultFilters(){
        let applyFilterOption = []
        let filters = []
        this.props.filterConfiguration && this.props.filterConfiguration.map((filter, index) => {
            if (filter.isDefault) {
                filters.push(filter)
            } else {
                // this.state.filters[index]["filterName"] && applyFilterOption.push({ label: this.state.filters[index]["filterName"], value: this.state.filters[index] })
                applyFilterOption.push({ label: filter["filterName"], value: filter })
            }
        })
        this.setState({ filters: filters, applyFilterOption: applyFilterOption })
    }

    removeField(index, field) {
        var availableOptions = [...this.state.createFilterOption];
        let filters = [...this.state.filters]

        if (index > -1) {
            if (this.props.filterMode === "CREATE") {
                // delete filters[index]
                filters.splice(index, 1);
                //add back the option to the filter list
                // if (field === "text" || field === "date") {
                //     let newItem = { value: field, label: field }
                //     availableOptions.push(newItem)
                //     this.setState({ filters: filters, createFilterOption: availableOptions })
                // }
                // else {
                this.setState({ filters: filters }, state => state)
                // }
            } else if (this.props.filterMode === "APPLY") {
                let applyFilterOption = [...this.state.applyFilterOption]
                applyFilterOption.push({ label: this.state.filters[index]["filterName"], value: this.state.filters[index] })
                filters.splice(index, 1);
                this.setState({ filters: filters, applyFilterOption: applyFilterOption })
            }
        }
    }

    createField(fieldType) {
        var availableOptions = [...this.state.createFilterOption];
        let filters = [...this.state.filters]
        let newoption = null
        let length = filters !== undefined ? filters.length : 0
        if (fieldType === "date") {
            filters.push({ filterName: '', field: '', fieldType: fieldType, dataType: "date", operator: "", value: new Date(this.state.dateFormat), key: length })
        } else if (fieldType === "text") {
            filters.push({ filterName: '', field: '', fieldType: fieldType, dataType: "text", operator: "", value: "", key: length })
        } else if (fieldType === "numeric") {
            filters.push({ filterName: '', field: '', fieldType: fieldType, dataType: "numeric", operator: "", value: "", key: length })
        } else {
            filters.push({ filterName: '', field: '', fieldType: fieldType, dataType: "", operator: "", value: "", key: length })
        }
        //removing filter from option list 
        // newoption = availableOptions.filter(function (obj) {
        //     return obj.value !== fieldname;
        // });
        // this.setState({ createFilterOption: newoption, filters: filters })
        if (this.props.filterMode === "CREATE") {

        }
        this.setState({ filters: filters })

    }

    handleChange(e) {
        let name = e.target.name;
        let value = e.target.value;
        this.setState({ input: { ...this.state.input, [name]: value } })
    }

    updateFilterRow(e, index, type) {
        this.setState({ showing: true })
        let name
        let value
        let defaultValues = []
        let filters = [...this.state.filters]
        filters[index]["dateRange"] = false
        if (type === "startDate" || type === "endDate") {
            name = type
            value = e
        }
        else if (type == "defaultValue") {
            let selectedoption = { "value": e.value, "label": e.value }
            name = "value"
            let filterValue = filters[index] ? filters[index][name] : []
            try {
                defaultValues = typeof filterValue == "string" ? JSON.parse(filterValue) : filterValue
            }
            catch (e) {
                console.error("Filter value found is a invalid json")
                defaultValues = []
            }

            if (defaultValues) {
                var valueExists = defaultValues.filter(filterdefault => filterdefault.value == e.value);
                //if option already exists in the list
                if (valueExists.length == 0) {
                    defaultValues.push(selectedoption)
                    defaultValues["selected"] = selectedoption.value
                }
                else {
                    defaultValues["selected"] = selectedoption.value
                }
            }
            value = defaultValues
        }
        else if (e.target.value === "today") {
            name = e.target.name
            value = e.target.value
            const today = new Date()
            filters[index]["startDate"] = today
            filters[index][name] = value
            filters[index]["dateRange"] = false
            this.setState({ showing: false })
        }
        else if (e.target.value === "monthly") {
            name = e.target.name
            value = e.target.value
            let date = new Date()
            let firstDay = new Date(date.getFullYear(), date.getMonth(), 1)
            let lastDay = new Date(date.getFullYear(), date.getMonth() + 1, 0)
            filters[index]["startDate"] = firstDay
            filters[index]["endDate"] = lastDay
            filters[index][name] = value
            filters[index]["dateRange"] = true
            this.setState({ showing: false })
        }
        else if (e.target.value === "yearly") {
            name = e.target.name
            value = e.target.value
            let date = new Date()
            let firstDay = new Date(date.getFullYear(), 0, 1)
            let lastDay = new Date(date.getFullYear(), 11, 31)
            filters[index]["startDate"] = firstDay
            filters[index]["endDate"] = lastDay
            filters[index][name] = value
            filters[index]["dateRange"] = true
            this.setState({ showing: false })
        }
        else if (e.target.value === "mtd") {
            name = e.target.name
            value = e.target.value
            let date = new Date()
            let firstDay = new Date(date.getFullYear(), date.getMonth(), 1)
            let lastDay = date
            filters[index]["startDate"] = firstDay
            filters[index]["endDate"] = lastDay
            filters[index][name] = value
            filters[index]["dateRange"] = true
            this.setState({ showing: false })
        }
        else if (e.target.value === "ytd") {
            name = e.target.name
            value = e.target.value
            let date = new Date()
            let firstDay = new Date(date.getFullYear(), 0, 1)
            let lastDay = date
            filters[index]["startDate"] = firstDay
            filters[index]["endDate"] = lastDay
            filters[index][name] = value
            filters[index]["dateRange"] = true
            this.setState({ showing: false })
        }
        else if (e.target.name === "isDefault") {
            name = e.target.name
            value = e.target.checked
        }
        else {
            name = e.target.name
            value = e.target.value
            filters[index]["dateRange"] = false
            this.setState({ showing: true })
        }
        filters[index][name] = value
        this.setState({ filters })
    }

    handleSelect(e) {
        let name = e.value;
        let value = e.label;
        if (this.props.filterMode === "CREATE") {
            if (e.__isNew__) {
                let filters = [...this.state.filters]
                let length = filters !== undefined ? filters.length : 0
                filters.push({ filterName: '', field: '', fieldType: name, dataType: "", operator: "", value: "", key: Math.floor(Math.random() * 1000) })
                this.setState({ input: { ...this.state.input, "filtertype": "" }, filters })
            } else {
                this.createField(e.value)
                this.setState({ input: { ...this.state.input, "filtertype": "" } })
            }
        }
        else if (this.props.filterMode === "APPLY") {
            let filters = [...this.state.filters]
            let applyFilterOption = [...this.state.applyFilterOption]
            filters.push(e.value)
            //removing selected option from the list
            let newoption = applyFilterOption.filter(function (obj) {
                return obj.label !== e.label;
            });
            applyFilterOption = newoption
            this.setState({ applyFilterOption: newoption, filters: filters })
        }
    }

    hideFilterDiv() {
        var element = document.getElementById("filter-form-container");
        element && element.classList.add("disappear");

        element = document.getElementById("filtereditor-form-container");
        element && element.classList.add("disappear");

        this.props.hideFilterDiv()
        document.getElementById("dashboard-container") && document.getElementById("dashboard-container").classList.remove("disappear")
        document.getElementById("dashboard-filter-btn") && (document.getElementById("dashboard-filter-btn").disabled = false)

    }

    saveFilter() {
        let restClient = this.props.core.make('oxzion/restClient');
        let filters
        if (this.state.filters !== undefined) {
            filters = this.state.filters.filter(function (obj) {
                return obj !== undefined && obj.value !== undefined;
            });
        }

        let formData = {}
        formData["dashboard_type"] = "html";
        formData["version"] = this.props.dashboardVersion;
        formData["filters"] = filters
        if (this.props.filterMode === "CREATE") {
            this.props.setFilter(filters)
            this.hideFilterDiv()
            this.props.notif.current.notify(
                "Filter Applied Successfully",
                "Please save the OI in order to keep the changes",
                "success"
            )
        } else if (this.props.filterMode === "APPLY") {
            this.props.setDashboardFilter(filters)
            this.hideFilterDiv()
        }
    }

    setFilterValues(filters) {
        this.setState({ filters })
    }

    render() {
        return (
            <div>
                <div className="row pull-right dash-manager-buttons" style={{ right: "22px", height: "30px", textAlign: "center", padding: "4px" }}>
                    <Button type="button" className="close btn btn-primary" aria-label="Close" onClick={() => this.hideFilterDiv()} style={{ padding: "5px" }}>
                        <i className="fa fa-close" aria-hidden="true"></i>
                    </Button>
                </div>
                <Form className="create-filter-form">
                    {this.state.filters.filter(obj => obj !== undefined).map((filterRow, index) => {
                        return <FilterFields
                            index={index}
                            dateFormat={this.state.dateFormat}
                            filters={this.state.filters}
                            setFilterValues={(filter) => this.setFilterValues(filter)}
                            input={this.state.input}
                            key={filterRow.key}
                            dataType={filterRow.dataType || ""}
                            value={filterRow.value || this.state.input[filterRow.fieldType + "value"]}
                            removeField={this.removeField}
                            fieldType={filterRow.fieldType}
                            isDefault={filterRow.isDefault}
                            field={filterRow.field}
                            filterName={filterRow.filterName}
                            onUpdate={(e, index, type) => this.updateFilterRow(e, index, type)}
                            onChange={(e) => this.handleChange(e)}
                            filterMode={this.props.filterMode}
                        />
                    })
                    }
                    {   // Rendered on dashboard Edtior
                        this.props.filterMode === "CREATE" &&
                        <Form.Group>
                            <Form.Label> Choose/Create Filters </Form.Label>
                            <Select
                                placeholder="Choose filters"
                                name="filtertype"
                                id="filtertype"
                                onChange={(e) => this.handleSelect(e)}
                                value={this.state.input["filtertype"]}
                                options={this.state.createFilterOption}
                            />
                        </Form.Group>
                    }
                    {   // Rendered on dashboard Viewer
                        this.props.filterMode === "APPLY" && (this.state.applyFilterOption.length!==0 || this.props.applyFilterOption.length !== 0) &&
                        < Form.Group >
                            <Form.Label> Choose/Apply Filters </Form.Label>
                            <Select
                                placeholder="Choose filters"
                                name="applyfiltertype"
                                id="applyfiltertype"
                                onChange={(e) => this.handleSelect(e)}
                                value={this.state.input["applyfiltertype"]}
                                options={this.state.applyFilterOption.length!==0?this.state.applyFilterOption:this.props.applyFilterOption}
                                style={{ marginleft: "0px" }}
                            />
                        </Form.Group>
                    }
                    <Form.Row>
                        <Button className="apply-filter-btn" onClick={() => this.saveFilter()}>Apply Filters</Button>

                    </Form.Row>

                </Form>
            </div >
        )
    }
}
export default DashboardFilter