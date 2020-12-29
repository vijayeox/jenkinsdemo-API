// ########################
// Show/Hide Location Hidden Field
// ########################

var getLastBuilding = (data, currentLocation) => {
  if (data.locations && data.locations.length > 0) {
    var buildingList = data.locations.filter(
      (item) => item.locationNum == currentLocation
    );
    return buildingList[buildingList.length - 1].buildingNum;
  } else {
    return 1;
  }
};
var currentLocation = parseInt(row.locationBuildingNum.split("")[0]);
var lastBuilding = getLastBuilding(data, currentLocation);
console.log(lastBuilding);
show = lastBuilding == parseInt(row.locationBuildingNum.split("")[2]);

// ########################
// Calculate Button
// ########################

console.log("click");

var financeFieldList = [
  {
    key: "financialsYTDSales",
  },
  {
    key: "financialsYTDGrossProfits",
  },
  {
    key: "financialsYTDSalesAnnualized",
  },
  {
    key: "financialsYTDGrossProfitsAnnualized",
  },
];

var childFields = [
  "bodyShop",
  "fAndI",
  "newAutos",
  "parts",
  "service",
  "usedAutos",
  "rentalLeasing",
];

var sumObjectsByKey = (...objs) => {
  return objs.reduce((a, b) => {
    for (let k in b) {
      if (b.hasOwnProperty(k)) a[k] = (a[k] || 0) + b[k];
    }
    return a;
  }, {});
};

var monthVariable =
  data.financialStatementMonths > 0 ? 12 / data.financialStatementMonths : 1;

financeFieldList.map((field) => {
  var cloneItem = [
    ...data[
      field.key.includes("Annual") ? field.key.split("Annual")[0] : field.key
    ],
  ];
  var accountTotal = {};
  var locationTotal = 0;
  var fieldTotal = {};

  cloneItem = cloneItem.map((building) => {
    field.key.includes("Annual")
      ? childFields.map((i) => {
          building[i] > 0 ? (building[i] = building[i] * monthVariable) : null;
        })
      : null;
    building.total = childFields
      .map((i) => (building[i] > 0 ? building[i] : 0))
      .reduce((a, b) => a + b, 0);
    locationTotal += building.total;

    fieldTotal = sumObjectsByKey(fieldTotal, building);

    if (building.lastBuilding) {
      var cleanfieldTotal = {};
      childFields.map((i) => {
        cleanfieldTotal["total_" + i] = fieldTotal[i] ? fieldTotal[i] : 0;
        accountTotal[i] =
          (accountTotal[i] ? accountTotal[i] : 0) +
          (fieldTotal[i] ? fieldTotal[i] : 0);
      });
      building.total_total = locationTotal;
      building = { ...building, ...cleanfieldTotal };
      accountTotal.total =
        (accountTotal.total ? accountTotal.total : 0) + locationTotal;
      locationTotal = 0;
      fieldTotal = {};
    }

    return building;
  });

  form.getComponent(field.key).setValue(cloneItem);
  form.getComponent(field.key + "Total").setValue(accountTotal);

  if (field.key == "financialsYTDSales") {
    cloneItem.map((item, index) => {
      if (data.buildings[index].coinsuranceamount) {
        var buildingCoinsurance = parseFloat(
          data.buildings[index].coinsuranceamount
        );
        if (buildingCoinsurance > 0) {
          
        }
      }
    });
  }
});
