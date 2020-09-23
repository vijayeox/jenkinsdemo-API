if (data.workbooksToBeGenerated.victor_AutoPhysDamage == true) {
  var sumNonEmployeesfurnishedAuto = 0;
  var indexValue = 0;
  var primarySecondaryOEM = [];
  var primarySecondaryOEM2 = [];
  var uniqueKeyControls = [];
  var uniqueLotProtection = [];
  var MixofInventoryNew = 0;
  var NewFloorPlan = 0;
  var checkMixofInventoryNew = 0;

  data.locationSchedule.map(row => {
    MixofInventoryNew +=
      (row.monthAvgNew ? row.monthAvgNew : 0) -
      (row.insuredThroughFloorPlanNew ? row.insuredThroughFloorPlanNew : 0) +
      ((row.monthAvgUsed ? row.monthAvgUsed : 0) -
        (row.insuredThroughFloorPlanUsed
          ? row.insuredThroughFloorPlanUsed
          : 0));

    NewFloorPlan +=
      (row.monthAvgNew ? row.monthAvgNew : 0) -
      (row.insuredThroughFloorPlanNew ? row.insuredThroughFloorPlanNew : 0);
  });

  if (NewFloorPlan > 0 && MixofInventoryNew > 0) {
    var row = NewFloorPlan / MixofInventoryNew;
    if (row <= 0) {
      checkMixofInventoryNew = 0;
    } else if (row > 0.0 && row <= 0.1) {
      checkMixofInventoryNew = 0.1;
    } else if (row > 0.1 && row <= 0.2) {
      checkMixofInventoryNew = 0.2;
    } else if (row > 0.2 && row <= 0.3) {
      checkMixofInventoryNew = 0.3;
    } else if (row > 0.3 && row <= 0.4) {
      checkMixofInventoryNew = 0.4;
    } else if (row > 0.4 && row <= 0.5) {
      checkMixofInventoryNew = 0.5;
    } else if (row > 0.5 && row <= 0.6) {
      checkMixofInventoryNew = 0.6;
    } else if (row > 0.6 && row <= 0.7) {
      checkMixofInventoryNew = 0.7;
    } else if (row > 0.7 && row <= 0.8) {
      checkMixofInventoryNew = 0.8;
    } else if (row > 0.8 && row <= 0.9) {
      checkMixofInventoryNew = 0.9;
    } else {
      checkMixofInventoryNew = 1;
    }
  }

  data.genericData.locationScheduleGridData.map(item => {
    if (
      item.occupancyType == "autoSalesAndService" ||
      item.occupancyType == "newCarShowroomAndSales" ||
      item.occupancyType == "vehicleStorageParkingGarage" ||
      item.occupancyType == "autoStorageLot"
    ) {
      tempSecondaryOEMStore = item.secondaryOEM
        ? item.secondaryOEM.length > 0
          ? item.secondaryOEM
          : []
        : [];
      primarySecondaryOEM2 = [
        ...primarySecondaryOEM2,
        item.primaryOEM,
        ...tempSecondaryOEMStore
      ];
    }
  });

  var DealershipStorageLocationsArray = data.locationSchedule
    .map(locationItem => {
      var resultOccupancyType = "";
      var resultPrimaryOEM = "";
      locationItem.buildingDetails.some(buildingItem => {
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
      var furnishedAutoEmployee = 0;
      var nonEmployeesfurnishedAuto = 0;
      locationItem.buildingDetails.map(building => {
        furnishedAutoEmployee +=
          building.fTEmployeesFurnishedAnAuto +
          building.pTEmployeesFurnishedAnAuto +
          building.nonEmployeesUnderTheAge +
          building.nonEmployeesYearsOldorolder;
        nonEmployeesfurnishedAuto +=
          building.nonEmployeesUnderTheAge +
          building.nonEmployeesYearsOldorolder;
      });
      resultOccupancyType
        ? (sumNonEmployeesfurnishedAuto += nonEmployeesfurnishedAuto)
        : null;
      tempSecondaryOEM = [];
      resultPrimaryOEM =
        [
          ...new Set(
            locationItem.buildingDetails
              .map(i => (i.primaryOEM ? " " + i.primaryOEM : false))
              .filter(i => i)
          )
        ] + "";
      var copylocationItem = { ...locationItem };
      copylocationItem.buildingDetails = "";

      return resultOccupancyType
        ? {
            ...copylocationItem,
            checkAvgExposure:
              (locationItem.monthAvgNew > 0 ? locationItem.monthAvgNew : 0) +
              (locationItem.monthAvgUsed > 0 ? locationItem.monthAvgUsed : 0) +
              (locationItem.monthAvgRoadDemosFurnishedAutos > 0
                ? locationItem.monthAvgRoadDemosFurnishedAutos
                : 0) +
              (locationItem.monthAvgRoadLoanersShopService > 0
                ? locationItem.monthAvgRoadLoanersShopService
                : 0),
            indexValue: (indexValue += 1),
            PrimaryOEMList: resultPrimaryOEM,
            DOLOccupancy: resultOccupancyType,
            furnishedAutoEmployee: furnishedAutoEmployee,
            nonEmployeesfurnishedAuto: nonEmployeesfurnishedAuto
          }
        : false;
    })
    .filter(filterItem => (filterItem ? filterItem : false));

  data.dolkeycntrlsameallloc == "no"
    ? DealershipStorageLocationsArray.map(item => {
        if (item.locationGarageLiabilityKeyControls) {
          item.locationGarageLiabilityKeyControls.none
            ? uniqueKeyControls.push("None")
            : "";
          item.locationGarageLiabilityKeyControls.lockbox
            ? uniqueKeyControls.push("Lock Boxes")
            : "";
          item.locationGarageLiabilityKeyControls.computerizedKeyVault
            ? uniqueKeyControls.push("Key Machine")
            : "";
          item.locationGarageLiabilityKeyControls.lockingKeyCabinet
            ? uniqueKeyControls.push("Key Board")
            : "";
          item.locationGarageLiabilityKeyControls.dailyKeyInventory
            ? uniqueKeyControls.push("Daily Key Inventory")
            : "";
          item.locationGarageLiabilityKeyControls.lockedInManagersOffice
            ? uniqueKeyControls.push("Key Board")
            : "";
          item.locationGarageLiabilityKeyControls.keysInCars
            ? uniqueKeyControls.push("Keys in Cars")
            : "";
        }
      })
    : "";

  data.dollotprotection == "no"
    ? data.locationSchedule.map(item => {
        if (item.dolsecguards == "yes") {
          uniqueLotProtection.push("Night Watchman");
        }
        if (item.dolaftrhrslighting == "yes") {
          uniqueLotProtection.push("Security Lighting");
        }
        if (item.dolEntranceQuestions.postChain == "yes") {
          uniqueLotProtection.push("Post and Chains");
        }
        if (item.dolsurvcammoniintrunotifi == "yes" || item.dolsurcamnotmoniotrd == "yes") {
          uniqueLotProtection.push("Video Surveillance");
        }
        if (item.dolEntranceQuestions.fullyFencedPremises == "yes") {
          uniqueLotProtection.push("Fenced");
        }
        if (
          item.dolsecguards != "yes" &&
          item.dolaftrhrslighting != "yes" &&
          item.dolEntranceQuestions.postChain != "yes" &&
          item.dolsurvcammoniintrunotifi != "yes" &&
          item.dolsurcamnotmoniotrd != "yes" &&
          item.dolEntranceQuestions.fullyFencedPremises != "yes"
        ) {
          uniqueLotProtection.push("None");
        }
      })
    : "";

  value = {
    checkSecurityGuards: data.genericData.locationScheduleGridData.some(
      locationItem => {
        if (locationItem.dolsecguards == "yes") {
          return true;
        } else {
          return false;
        }
      }
    ),
    checkAfterHoursLighting: data.genericData.locationScheduleGridData.some(
      locationItem => {
        if (locationItem.dolaftrhrslighting == "yes") {
          return true;
        } else {
          return false;
        }
      }
    ),
    checkSurveillanceCamera: data.genericData.locationScheduleGridData.some(
      locationItem => {
        if (locationItem.dolsurvcammoniintrunotifi == "yes") {
          return true;
        } else {
          return false;
        }
      }
    ),
    checkNotMonitoredSurveillanceCamera: data.genericData.locationScheduleGridData.some(
      locationItem => {
        if (locationItem.dolsurcamnotmoniotrd == "yes") {
          return true;
        } else {
          return false;
        }
      }
    ),
    checkdolpostnchain: data.genericData.locationScheduleGridData.some(
      locationItem => {
        if (locationItem.dolEntranceQuestions.postChain == "yes") {
          return true;
        } else {
          return false;
        }
      }
    ),
    checkdolflyfencdpremises: data.genericData.locationScheduleGridData.some(
      locationItem => {
        if (locationItem.dolEntranceQuestions.fullyFencedPremises == "yes") {
          return true;
        } else {
          return false;
        }
      }
    ),
    checkAutobody: data.genericData.locationScheduleGridData.some(
      locationItem => {
        if (locationItem.occupancyType == "bodyShop") {
          return true;
        } else {
          return false;
        }
      }
    ),
    checkfloodloss: data.dolflodlossdescib
      ? data.dolflodlossdescib.length > 0
        ? "true"
        : "false"
      : "",
    DealershipStorageLocationsArray: DealershipStorageLocationsArray,
    sumNonEmployeesfurnished: sumNonEmployeesfurnishedAuto,
    primarySecondaryOEM: [...new Set(primarySecondaryOEM2)] + "",
    checkMixofInventoryNew: checkMixofInventoryNew,
    uniqueLotProtection:
      uniqueLotProtection.length > 0
        ? [...new Set(uniqueLotProtection)] + ""
        : "",
    uniqueKeyControls:
      uniqueKeyControls.length > 0 ? [...new Set(uniqueKeyControls)] + "" : ""
  };

  console.log(value);
}
