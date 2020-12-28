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

var financeFieldList = [
  {
    key: "financialsYTDSales",
    children: [
      "bodyShop",
      "fAndI",
      "newAutos",
      "parts",
      "service",
      "usedAutos",
    ],
  },
];

var sumObjectsByKey = (...objs) => {
  return objs.reduce((a, b) => {
    for (let k in b) {
      if (b.hasOwnProperty(k)) a[k] = (a[k] || 0) + b[k];
    }
    return a;
  }, {});
};

financeFieldList.map((field) => {
  var cloneItem = [...data[field.key]];
  var accountTotal = {};
  var locationTotal = 0;
  var fieldTotal = {};
  cloneItem = cloneItem.map((building, buildingIndex) => {
    building.total = field.children
      .map((i) => (building[i] > 0 ? building[i] : 0))
      .reduce((a, b) => a + b, 0);
    locationTotal += building.total;
    fieldTotal = sumObjectsByKey(fieldTotal, building);
    if (building.lastBuilding) {
      var cleanfieldTotal = {};
      field.children.map((i) => {
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
});
