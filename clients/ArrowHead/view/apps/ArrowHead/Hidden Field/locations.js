// ########################
// Add Location Event
// ########################

var getLocationNumber = (data) => {
  if (data.locations && data.locations.length > 0) {
    var lastItem = data.locations[data.locations.length - 1];
    var lastLocation = parseInt(lastItem.locationNum);
    return lastLocation ? lastLocation + 1 : 1;
  } else {
    return 1;
  }
};

var locationsClone = [...data.locations];
var newLocationNumber = getLocationNumber(data);
var rowValue = {
  ...component.defaultValue[0],
  locationNum: newLocationNumber,
  locationBuildingNum: newLocationNumber + "-1",
};
locationsClone.push(rowValue);
value = locationsClone;

// ########################
// Add Building Event
// ########################

var getLocationIndex = (data, rowInfo, rowIndex) => {
  if (data.locations && data.locations.length > 0) {
    var buildingList = data.locations.filter(
      (item) => item.locationNum == rowInfo.locationNum
    );
    var lastBuilding = buildingList[buildingList.length - 1].buildingNum;
    return rowIndex + lastBuilding;
  } else {
    return 1;
  }
};

if (result.length > 0) {
  var rowInfo = result[0].row;
  var rowIndex = result[0].rowIndex;

  var locationsClone = [...data.locations];
  var locationIndex = getLocationIndex(data, rowInfo, rowIndex);

  var rowValue = {
    ...component.defaultValue[0],
    locationNum: rowInfo.locationNum,
    buildingNum: locationIndex - rowIndex + 1,
    locationBuildingNum:
      rowInfo.locationNum + "-" + (locationIndex - rowIndex + 1),
  };
  locationsClone.splice(locationIndex, 0, rowValue);
  value = locationsClone;
}

// ########################
// Delete Building Event
// ########################

var rowInfo = result[0].row;
var rowIndex = result[0].rowIndex;

var locationsClone = [...data.locations];

locationsClone.splice(rowIndex, 1);
currentBuildingsSize = locationsClone.filter(
  (i) => i.locationNum == rowInfo.locationNum
).length;

value = locationsClone.map((loc) => {
  if (
    loc.locationNum == rowInfo.locationNum &&
    loc.buildingNum > rowInfo.buildingNum
  ) {
    return {
      ...loc,
      buildingNum: loc.buildingNum - 1,
      locationBuildingNum: loc.locationNum + "-" + (loc.buildingNum - 1),
    };
  } else if (
    loc.locationNum > rowInfo.locationNum &&
    currentBuildingsSize == 0
  ) {
    return {
      ...loc,
      locationNum: loc.locationNum - 1,
      locationBuildingNum: loc.locationNum - 1 + "-" + loc.buildingNum,
    };
  } else {
    return loc;
  }
});
