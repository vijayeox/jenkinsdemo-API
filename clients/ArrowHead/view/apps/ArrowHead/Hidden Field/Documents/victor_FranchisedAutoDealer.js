if (data.workbooksToBeGenerated.victor_FranchisedAutoDealer == true) {
  var falsePretenseLimit = 0;
  var checktotalLocationSalesRevenue = 0;
  var checklistCompaniesRepresented = [];
  var checkblanketcoverage = [];
  var locationSpecificData = [];



  if(data.locations.length == 1){
    checkblanketcoverage.push({result:'No'});
  }
  else if(data.locations.length>1){
    var currentLocation = 1;
    var currentLocationBuildingCount = 0
    var pushLocationsIntoArray = function(checkblanketcoverage,buildingCount){
      var promise = new Promise(function(resolve,reject){
        if(buildingCount>1){
          checkblanketcoverage.push(...Array(currentLocationBuildingCount).fill({result:"Premise Only"}));
          resolve();
        }
        else if(buildingCount == 1){
          checkblanketcoverage.push({result:"No"});
          resolve();
        }
        else{
          resolve();
        }
        console.log(checkblanketcoverage);
      })
      return promise;
    }
  
    for(var i=0;i<data.locations.length;i++){
      if(data.locations[i].locationNum==currentLocation){
        currentLocationBuildingCount+=1
        if(i==data.locations.length-1){
          pushLocationsIntoArray(checkblanketcoverage,currentLocationBuildingCount);
        }
      }
      else{
        pushLocationsIntoArray(checkblanketcoverage,currentLocationBuildingCount);
        currentLocationBuildingCount = 1;
        currentLocation+=1;
        if(i==data.locations.length-1){
          pushLocationsIntoArray(checkblanketcoverage,currentLocationBuildingCount);
        }
      }
    }
  }

  locationSpecificData = data.buildings.filter(building=>building.buildingNum == '1');

  checklistCompaniesRepresented = data.companiesRepresentedList.map(
    item => item.listcompaniesrepresented
  );

  checktotalLocationSalesRevenue += data.financialsYTDSalesTotal.total?data.financialsYTDSalesTotal.total:0;

  if (data.falsePretenseNumber <= 0) {
    falsePretenseLimit = "None";
  } else if (data.falsePretenseNumber <= 25000) {
    falsePretenseLimit = 25000;
  } else if (data.falsePretenseNumber <= 50000) {
    falsePretenseLimit = 50000;
  } else if (data.falsePretenseNumber <= 75000) {
    falsePretenseLimit = 75000;
  } else if (data.falsePretenseNumber <= 100000) {
    falsePretenseLimit = 100000;
  } else if (data.falsePretenseNumber <= 150000) {
    falsePretenseLimit = 150000;
  } else if (data.falsePretenseNumber <= 200000) {
    falsePretenseLimit = 200000;
  } else {
    falsePretenseLimit = 250000;
  }

  value = {
    checkblanketcoverage: checkblanketcoverage ? checkblanketcoverage : [],
    checkcompaniesrepresented: checklistCompaniesRepresented + "",
    checktotalLocationSalesRevenue: checktotalLocationSalesRevenue,
    checkfalsePretenseLimit: falsePretenseLimit,
    locationSpecificData: locationSpecificData,
    locationBuildingIndex: data.locations
    .map((i, index) => (index == 0 ? "invalid" : { result: i.locationNum }))
    .filter(i => i !== "invalid"),
    checkbuildingCoinsurance: data.locations.map(
      item => {
        return item.occupancyType == "vacantLand"
          ? { result: "" }
          : { result: "Agreed Amount" };
      }
    ),
    checkpropertyCoverageDeductible: data.locations.map(
      item => {
        return item.occupancyType == "vacantLand"
          ? { result: "" }
          : {
              result: data.propertyCoverageDeductible
                ? data.propertyCoverageDeductible
                : ""
            };
      }
    ),

    checkbuildingValuation: data.locations.map(i => {
      return { result: "Replacement Cost" };
    }),

    CityStateZip: data.city + "," + (data.state.name?data.state.name:data.state) + "," + data.zip,

    InsuranceContactNameandPhone: data.insurancecontactdataGrid
      ? data.insurancecontactdataGrid[0].genfirstname +
        " " +
        data.insurancecontactdataGrid[0].genlastname +
        "," +
        data.insurancecontactdataGrid[0].genphone
      : "",

    checkSecurityGuards: data.dol_Protection.some(
      locationItem => {
        if (locationItem.premisesLotProtection.securityGuards == "yes") {
          return true;
        } else {
          return false;
        }
      }
    ),

    checkdolaftrhrslighting: data.dol_Protection.some(
      locationItem => {
        if (locationItem.premisesLotProtection.afterHoursLighting == "yes") {
          return true;
        } else {
          return false;
        }
      }
    ),

    checkSurveillanceCamera: data.dol_Protection.some(
      locationItem => {
        if (locationItem.entranceQuestions.camerasMonitored == "yes") {
          return true;
        } else {
          return false;
        }
      }
    ),

    checkNotMonitoredSurveillanceCamera: data.dol_Protection.some(
      locationItem => {
        if (locationItem.entranceQuestions.camerasNotMonitored == "yes") {
          return true;
        } else {
          return false;
        }
      }
    ),

    checkdolpostnchain: data.dol_Protection.some(
      locationItem => {
        if (locationItem.entranceQuestions.postChain == "yes") {
          return true;
        } else {
          return false;
        }
      }
    ),

    checkdolflyfencdpremises: data.dol_Protection.some(
      locationItem => {
        if (locationItem.entranceQuestions.fullyFencedPremises == "yes") {
          return true;
        } else {
          return false;
        }
      }
    ),

    checknumberofservicebays: data.buildings.some(
      locationItem => {
        if (locationItem.numberofservicebays == "yes") {
          return true;
        } else {
          return false;
        }
      }
    ),

    

    checkEmployeetoolsOnPremises: data.buildings.map(
      item => {
        return {
          result:
            (item.businesselectronicequipment > 0
              ? item.businesselectronicequipment
              : 0) +
            (item.employeetools > 0 ? item.employeetools : 0) +
            (item.lightpoles > 0 ? item.lightpoles : 0) +
            (item.signsLessThan > 0 ? item.signsLessThan : 0) +
            (item.signsGreaterThan > 0 ? item.signsGreaterThan : 0) +
            (item.valulablePapers > 0 ? item.valulablePapers : 0) +
            (item.accountsreceivable > 0 ? item.accountsreceivable : 0) +
            (item.awaningsscanopies > 0 ? item.awaningsscanopies : 0)
        };
      }
    ),

    checkTotalbuildinglimit: data.buildings.map(
      item => {
        return {
          result:
            (item.signsLessThan > 0 ? item.signsLessThan : 0) +
            (item.signsGreaterThan > 0 ? item.signsGreaterThan : 0) +
            (item.buildingLimit > 0 ? item.buildingLimit : 0)
        };
      }
    ),
 
    checkOrdinanceAndUtility: data.buildings.map(
      item => {
        return {
          result:
            (item.utilityservices > 0
              ? "Utility Services Time Element: " + item.utilityservices
              : "") +
            (item.utilityservices > 0 && item.ordinancelawcoverage > 0
              ? ", "
              : "") +
            (item.ordinancelawcoverage > 0
              ? "Ordinance Law: " + item.ordinancelawcoverage
              : "")
        };
      }
    ),
    checkrepairpercentlabor:
      data.repairpercentlabor == 95 || data.repairpercentlabor == 85
        ? 90
        : data.repairpercentlabor,
    checkwhodeliversvehiclesonemployee:
      data.whodeliversvehicleson == "employee" ? "true" : "false",
    checkwhodeliversvehiclesonindependentContractor:
      data.whodeliversvehicleson == "independentContractor" ? "true" : "false"
  };

  var getGarageLiabilityGrid = function(){

    var currentLocation = 1;
    var garageLiabilityGrid = [];
  
    var items = Array(9).fill(0);
  
    for(var i=0;i<data.employeeList.length;i++)
    {
      var locationNum = data.employeeList[i].locationBuildingNum.split("-")
      locationNum = parseInt(locationNum[0])
  
      if(locationNum == currentLocation)
      {
        processBuildingEmployees(items,data.employeeList[i]);
        if(i==data.employeeList.length-1){
          garageLiabilityGrid.push(items);
        }

      }
  
      else{
        currentLocation+=1;
        garageLiabilityGrid.push(items);
        items = Array(9).fill(0);
        processBuildingEmployees(items,data.employeeList[i]);
        if(i==data.employeeList.length-1){
          garageLiabilityGrid.push(items);
        }
      }
    }
    return garageLiabilityGrid;
  }
  
  var processBuildingEmployees = function(itemArray,employeeList){
  
      itemArray[0] +=
        employeeList.total_fTEmployeesFurnishedAnAuto > 0
          ? employeeList.total_fTEmployeesFurnishedAnAuto
          : 0;
       itemArray[1]+=
        employeeList.total_pTEmployeesFurnishedAnAuto > 0
          ? employeeList.total_pTEmployeesFurnishedAnAuto
          : 0;
      itemArray[2] +=
        employeeList.total_fTEmployeesWhoAreNotFurnished > 0
          ? employeeList.total_fTEmployeesWhoAreNotFurnished
          : 0;
      itemArray[3] +=
        employeeList.total_pTEmployeesWhoAreNotFurnished > 0
          ? employeeList.total_pTEmployeesWhoAreNotFurnished
          : 0;
      itemArray[4] += employeeList.total_fTAllOtherEmployees > 0 ? employeeList.total_fTAllOtherEmployees : 0;
      itemArray[5] += employeeList.total_pTAllOtherEmployees > 0 ? employeeList.total_pTAllOtherEmployees : 0;
      itemArray[6] +=
        employeeList.total_nonEmployeesUnderTheAge > 0 ? employeeList.total_nonEmployeesUnderTheAge : 0;
      itemArray[7] +=
        employeeList.total_nonEmployeesYearsOldorolder > 0
          ? employeeList.total_nonEmployeesYearsOldorolder
          : 0;
      itemArray[8] +=
        employeeList.total_contractDriversNonEmployees > 0
          ? employeeList.total_contractDriversNonEmployees
          : 0;
  }

  value['garageLiabilityGrid']= getGarageLiabilityGrid();s
}


