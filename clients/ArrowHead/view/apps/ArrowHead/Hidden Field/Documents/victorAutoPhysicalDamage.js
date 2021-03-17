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
  var requestedCoverage = {};
  var chkDolaVechilePark = "";
  var allLotProtection = [];	
  var dolalllocdoesnotincld = [];	
  var dollotprotection = "no";	
  var dolkeycontrol = "no";

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
      allLotProtection:allLotProtection,
      dolkeycontrol:dolkeycontrol,
      dollotprotection:dollotprotection,
      dolalllocdoesnotincld:dolalllocdoesnotincld,
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
    chkParkGarageOrRoof: chkDolaVechilePark,
    requestedCoverage: requestedCoverage,
  };
  console.log("===============");
  console.log(value);
}
