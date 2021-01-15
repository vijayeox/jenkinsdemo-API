// ########################
// Add Tank Event
// ########################

var getTankIndex = (data) => {
  if (data.storageTankLocation > 0 && data.storageTanks) {
    var tankList = data.storageTanks.filter(
      (item) => item.locationNum == data.storageTankLocation
    );
    return tankList.length == 0
      ? {
          tankIndex: 1,
          tankInsertPosition: data.storageTanks.length,
        }
      : {
          tankIndex: tankList[tankList.length - 1].tankIndex + 1,
          tankInsertPosition:
            data.storageTanks.findIndex(
              (i) =>
                i.LocTankIndex == tankList[tankList.length - 1].LocTankIndex
            ) + 1,
        };
  } else {
    return 1;
  }
};

var storageTankClone = data.storageTanks
  ? [...data.storageTanks].filter((i) => i.locationNum > 0)
  : [];

var { tankIndex, tankInsertPosition } = getTankIndex(data);

var rowValue = {
  locationNum: data.storageTankLocation,
  tankIndex: tankIndex,
  LocTankIndex: data.storageTankLocation + "-" + tankIndex,
};
storageTankClone.splice(tankInsertPosition, 0, rowValue);
value = storageTankClone;

// ########################
// Delete Tank Event
// ########################
