var DOLFieldList = [
  {
    key: "dol_12MonthAvg",
    children: ["new", "used", "demosFurnishedAutos", "loanersShopService"],
  },
  {
    key: "dol_inventory",
    children: ["maxInventory", "maxUnits", "maxIndoor"],
  },
];

DOLFieldList.map((field) => {
  var cloneItem = [...data[field.key]];
  cloneItem = cloneItem.map((location) => {
    var lineTotal = {
      total: 0,
      floor_total: 0,
    };
    field.children.map((i) => {
      location["total_" + i] =
        (location[i] > 0 ? location[i] : 0) +
        (location["floor_" + i] > 0 ? location["floor_" + i] : 0);
      lineTotal.total += location[i] ? location[i] : 0;
      lineTotal.floor_total += location["floor_" + i]
        ? location["floor_" + i]
        : 0;
    });
    return {
      ...location,
      ...lineTotal,
      total_total: lineTotal.total + lineTotal.floor_total,
    };
  });
  form.getComponent(field.key).setValue(cloneItem);
});
