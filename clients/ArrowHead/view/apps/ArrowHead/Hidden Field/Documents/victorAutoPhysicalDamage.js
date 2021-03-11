if (data.workbooksToBeGenerated.victor_AutoPhysDamage == true) {
  var MixofInventoryNew =
    data.dol_12MonthAvgTotal.new + data.dol_12MonthAvgTotal.used;
  var NewFloorPlan = data.dol_12MonthAvgTotal.new;

  var sumNonEmployeesfurnished = 0;
  var indexValue = 0;
  var primarySecondaryOEM = [];
  var primarySecondaryOEM2 = [];
  var uniqueKeyControls = "";
  var uniqueLotProtection = "";
  var checkMixofInventoryNew = 0;
  var checklotprotectiondolsecguards = "";
  var checklotprotectiondolaftrhrslighting = "";
  var checklotprotectionpostChain = "";
  var checklotprotectionsurveillance = "";
  var checklotprotectionfullyFencedPremises = "";
  var checklotprotectionnone = "";
  var checkkeycontrolsnone = "";
  var checkkeycontrolslockbox = "";
  var checkkeycontrolscomputerizedKeyVault = "";
  var checkkeycontrolslockingKeyCabinet = "";
  var checkkeycontrolsdailyKeyInventory = "";
  var checkkeycontrolslockedInManagersOffice = "";
  var checkkeycontrolskeysInCars = "";
  var sumfurnishedAutoEmployee = 0;
  var checkBodyShop = "";

  if (NewFloorPlan > 0 && MixofInventoryNew > 0) {
    var row = NewFloorPlan / MixofInventoryNew;
    if (row <= 0.05) {
      checkMixofInventoryNew = 0;
    } else if (row > 0.05 && row <= 0.15) {
      checkMixofInventoryNew = 0.1;
    } else if (row > 0.15 && row <= 0.25) {
      checkMixofInventoryNew = 0.2;
    } else if (row > 0.25 && row <= 0.35) {
      checkMixofInventoryNew = 0.3;
    } else if (row > 0.35 && row <= 0.45) {
      checkMixofInventoryNew = 0.4;
    } else if (row > 0.45 && row <= 0.55) {
      checkMixofInventoryNew = 0.5;
    } else if (row > 0.55 && row <= 0.65) {
      checkMixofInventoryNew = 0.6;
    } else if (row > 0.65 && row <= 0.75) {
      checkMixofInventoryNew = 0.7;
    } else if (row > 0.75 && row <= 0.85) {
      checkMixofInventoryNew = 0.8;
    } else if (row > 0.85 && row <= 0.95) {
      checkMixofInventoryNew = 0.9;
    } else {
      checkMixofInventoryNew = 1;
    }
  }

  data.buildings.map((item) => {
    if (
      item.occupancyType == "autoSalesAndService" ||
      item.occupancyType == "newCarShowroomAndSales" ||
      item.occupancyType == "vehicleStorageParkingGarage" ||
      item.occupancyType == "autoStorageLot"
    ) {
      var tempSecondaryOEMStore = item.secondaryOEM
        ? item.secondaryOEM.length > 0
          ? item.secondaryOEM
          : []
        : [];
      primarySecondaryOEM2 = [
        ...primarySecondaryOEM2,
        item.primaryOEM,
        ...tempSecondaryOEMStore,
      ];
    }
  });

  var DealershipStorageLocationsArray = data.locations
    .map((locationItem) => {
      var resultOccupancyType = "";
      var resultPrimaryOEM = "";
      data.buildings
        .filter((i) => i.locationBuildingNum.split("-")[0] == locationItem.locationNum)
        .some((buildingItem) => {
          if (
            buildingItem.occupancyType == "autoSalesAndService" ||
            buildingItem.occupancyType == "newCarShowroomAndSales"
          ) {
            resultOccupancyType = "Dealership";
            return true;
          } else if (
            buildingItem.occupancyType == "vehicleStorageParkingGarage" ||
            buildingItem.occupancyType == "autoStorageLot"
          ) {
            resultOccupancyType = "Storage";
          } else {
            resultOccupancyType =
              resultOccupancyType == "" ? undefined : resultOccupancyType;
          }
        });
      tempSecondaryOEM = [];
      resultPrimaryOEM =
        [
          ...new Set(
            data.buildings
              .filter((i) => i.locationNum == locationItem.locationNum)
              .map((i) => (i.primaryOEM ? " " + i.primaryOEM : false))
              .filter((i) => i)
          ),
        ] + "";

      return resultOccupancyType
        ? {
            ...locationItem,
            ...data.dol_12MonthAvg[locationItem.locationNum - 1],
            ...data.dol_Protection[locationItem.locationNum - 1].keyControls,
            ...data.dol_Protection[locationItem.locationNum - 1]
              .entranceQuestions,
            new:
              data.dol_12MonthAvg[locationItem.locationNum - 1].new,
            used:
              data.dol_12MonthAvg[locationItem.locationNum - 1].used,
            demosFurnishedAutos:
              data.dol_12MonthAvg[locationItem.locationNum - 1].demosFurnishedAutos,
            loanersShopService:
              data.dol_12MonthAvg[locationItem.locationNum - 1].loanersShopService,
            floorNew:
              data.dol_12MonthAvg[locationItem.locationNum - 1].floor_new,
            floorUsed:
              data.dol_12MonthAvg[locationItem.locationNum - 1].floor_used,
            floorDemosFurnishedAutos:
              data.dol_12MonthAvg[locationItem.locationNum - 1].floor_demosFurnishedAutos,
            floorLoanersShopService:
              data.dol_12MonthAvg[locationItem.locationNum - 1].floor_loanersShopService,
            indexValue: (indexValue += 1),
            PrimaryOEMList: resultPrimaryOEM,
            DOLOccupancy: resultOccupancyType,
          }
        : false;
    })
    .filter((filterItem) => (filterItem ? filterItem : false));

  data.dol_Protection.map((item) => {
    if (item.locationNum > 1) {
      if (item.keyControls) {
        uniqueKeyControls += " Loc-" + item.locationNum + ": ";
        item.keyControls.none == true ? (uniqueKeyControls += "None,") : "";
        item.keyControls.lockbox == true
          ? (uniqueKeyControls += "Lock Boxes,")
          : "";
        item.keyControls.computerizedKeyVault == true
          ? (uniqueKeyControls += "Key Machine,")
          : "";
        item.keyControls.lockingKeyCabinet == true
          ? (uniqueKeyControls += "Key Board,")
          : "";
        item.keyControls.dailyKeyInventory == true
          ? (uniqueKeyControls += "Daily Key Inventory,")
          : "";
        item.keyControls.lockedInManagersOffice == true
          ? (uniqueKeyControls += "Key Board,")
          : "";
        item.keyControls.keysInCars == true
          ? (uniqueKeyControls += "Keys in Cars,")
          : "";
        uniqueKeyControls = uniqueKeyControls.slice(0, -1);
      }
    }
    if (item.locationNum == 1) {
      if (item.keyControls) {
        item.keyControls.none == true ? (checkkeycontrolsnone = "yes") : "";
        item.keyControls.lockbox == true
          ? (checkkeycontrolslockbox = "yes")
          : "";
        item.keyControls.computerizedKeyVault == true
          ? (checkkeycontrolscomputerizedKeyVault = "yes")
          : "";
        item.keyControls.lockingKeyCabinet == true
          ? (checkkeycontrolslockingKeyCabinet = "yes")
          : "";
        item.keyControls.dailyKeyInventory == true
          ? (checkkeycontrolsdailyKeyInventory = "yes")
          : "";
        item.keyControls.lockedInManagersOffice == true
          ? (checkkeycontrolslockedInManagersOffice = "yes")
          : "";
        item.keyControls.keysInCars == true
          ? (checkkeycontrolskeysInCars = "yes")
          : "";
      }
    }
  });

  data.dol_Protection.map((item) => {
    if (item.locationNum > 1) {
      uniqueLotProtection += " Loc-" + item.locationNum + ": ";
      if (item.premisesLotProtection.securityGuards == "yes") {
        uniqueLotProtection += "Night Watchman,";
      }
      if (item.premisesLotProtection.afterHoursLighting == "yes") {
        uniqueLotProtection += "Security Lighting,";
      }
      if (item.entranceQuestions.postChain == "yes") {
        uniqueLotProtection += "Post and Chains,";
      }
      if (
        item.entranceQuestions.camerasMonitored == "yes" ||
        item.entranceQuestions.camerasNotMonitored == "yes"
      ) {
        uniqueLotProtection += "Video Surveillance,";
      }
      if (item.entranceQuestions.fullyFencedPremises == "yes") {
        uniqueLotProtection += "Fenced,";
      }
      if (
        item.premisesLotProtection.securityGuards != "yes" &&
        item.premisesLotProtection.afterHoursLighting != "yes" &&
        item.entranceQuestions.postChain != "yes" &&
        item.entranceQuestions.camerasMonitored != "yes" &&
        item.entranceQuestions.camerasNotMonitored != "yes" &&
        item.entranceQuestions.fullyFencedPremises != "yes"
      ) {
        uniqueLotProtection += "None,";
      }
      uniqueLotProtection = uniqueLotProtection.slice(0, -1);
    }
    if (item.locationNum == 1) {
      if (item.premisesLotProtection.securityGuards == "yes") {
        checklotprotectiondolsecguards = "yes";
      }
      if (item.premisesLotProtection.afterHoursLighting == "yes") {
        checklotprotectiondolaftrhrslighting = "yes";
      }
      if (item.entranceQuestions.postChain == "yes") {
        checklotprotectionpostChain = "yes";
      }
      if (
        item.entranceQuestions.camerasMonitored == "yes" ||
        item.entranceQuestions.camerasNotMonitored == "yes"
      ) {
        checklotprotectionsurveillance = "yes";
      }
      if (item.entranceQuestions.fullyFencedPremises == "yes") {
        checklotprotectionfullyFencedPremises = "yes";
      }
      if (
        item.premisesLotProtection.securityGuards != "yes" &&
        item.premisesLotProtection.afterHoursLighting != "yes" &&
        item.entranceQuestions.postChain != "yes" &&
        item.entranceQuestions.camerasMonitored != "yes" &&
        item.entranceQuestions.camerasNotMonitored != "yes" &&
        item.entranceQuestions.fullyFencedPremises != "yes"
      ) {
        checklotprotectionnone = "yes";
      }
    }
  });

  data.buildings.some((locationItem) => {
    if (locationItem.occupancyType == "bodyShop") {
      checkBodyShop = "Yes";
      return true;
    } else {
      checkBodyShop = "No";
      return false;
    }
  });

  value = {
    checkAutobody: checkBodyShop ? checkBodyShop : "",
    checkfloodloss: data.dolflodlossdescib
      ? data.dolflodlossdescib.length > 0
        ? "true"
        : "false"
      : "",
    DealershipStorageLocationsArray: DealershipStorageLocationsArray,
    sumfurnishedAutoEmployee:
      data.employeeListTotal.fTEmployeesFurnishedAnAuto +
      data.employeeListTotal.pTEmployeesFurnishedAnAuto +
      data.employeeListTotal.nonEmployeesYearsOldorolder +
      data.employeeListTotal.nonEmployeesUnderTheAge,
    sumNonEmployeesfurnished:
      data.employeeListTotal.nonEmployeesYearsOldorolder +
      data.employeeListTotal.nonEmployeesUnderTheAge,

    primarySecondaryOEM: [...new Set(primarySecondaryOEM2)] + "",
    checkMixofInventoryNew: checkMixofInventoryNew,
    uniqueLotProtection:
      uniqueLotProtection.length > 0 ? uniqueLotProtection : "",
    uniqueKeyControls: uniqueKeyControls.length > 0 ? uniqueKeyControls : "",
    checklotprotectiondolsecguards: checklotprotectiondolsecguards
      ? checklotprotectiondolsecguards
      : "",
    checklotprotectiondolaftrhrslighting: checklotprotectiondolaftrhrslighting
      ? checklotprotectiondolaftrhrslighting
      : "",
    checklotprotectionpostChain: checklotprotectionpostChain
      ? checklotprotectionpostChain
      : "",
    checklotprotectionsurveillance: checklotprotectionsurveillance
      ? checklotprotectionsurveillance
      : "",
    checklotprotectionfullyFencedPremises: checklotprotectionfullyFencedPremises
      ? checklotprotectionfullyFencedPremises
      : "",
    checklotprotectionnone: checklotprotectionnone
      ? checklotprotectionnone
      : "",
    checkkeycontrolsnone: checkkeycontrolsnone ? checkkeycontrolsnone : "",
    checkkeycontrolslockbox: checkkeycontrolslockbox
      ? checkkeycontrolslockbox
      : "",
    checkkeycontrolscomputerizedKeyVault: checkkeycontrolscomputerizedKeyVault
      ? checkkeycontrolscomputerizedKeyVault
      : "",
    checkkeycontrolslockingKeyCabinet: checkkeycontrolslockingKeyCabinet
      ? checkkeycontrolslockingKeyCabinet
      : "",
    checkkeycontrolsdailyKeyInventory: checkkeycontrolsdailyKeyInventory
      ? checkkeycontrolsdailyKeyInventory
      : "",
    checkkeycontrolslockedInManagersOffice: checkkeycontrolslockedInManagersOffice
      ? checkkeycontrolslockedInManagersOffice
      : "",
    checkkeycontrolskeysInCars: checkkeycontrolskeysInCars
      ? checkkeycontrolskeysInCars
      : "",
  };
  console.log(value);
}
