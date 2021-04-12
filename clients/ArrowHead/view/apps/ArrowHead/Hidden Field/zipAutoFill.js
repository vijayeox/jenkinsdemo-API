if (data.zip != null && data.zip != "") {
  if (data.zip.toString().length === 5) {
    form.getComponent("city").deleteValue();
    form.getComponent("state").deleteValue();
    Formio.fetch("https://api.npoint.io/2220d9339d3d11eb5795", {
      method: "GET",
    }).then(function (response) {
      response.json().then(function (result) {
        let gotState = false;

        const statecityData = Object.entries(result);
        for (var i = 0; i < statecityData.length; i++) {
          for (var j = 0; j < statecityData[i].length; j++) {
            if (
              typeof statecityData[i][j] === "object" &&
              statecityData[i][j] !== null
            ) {
              if ("cities" in statecityData[i][j]) {
                var citiesData = Object.entries(statecityData[i][j].cities);
                if (typeof citiesData === "object" && citiesData !== null) {
                  for (k = 0; k < citiesData.length; k++) {
                    var a = citiesData[k][1].indexOf(parseInt(data.zip));
                    if (a > -1) {
                      for (
                        var stateLength = 0;
                        stateLength < data.stateListJson.length;
                        stateLength++
                      ) {
                        if (
                          statecityData[i][j].name ===
                          data.stateListJson[stateLength].name
                        ) {
                          form
                            .getComponent("state")
                            .setValue(data.stateListJson[stateLength]);
                          form.setDisabled(form.getComponent("state"));
                        }
                      }

                      form.getComponent("city").setValue(citiesData[k][0]);
                      form.setDisabled(form.getComponent("city"));

                      gotState = true;
                      break;
                    }
                  }
                }
              }
            }
          }
        }
        gotState ? null : alert("Entered Zipcode is not Found.");
      });
    });
  } else {
    alert("Please enter a valid zipcode.");
  }
}

// Location Details Page
if (
  data.locations[rowIndex].zip != null &&
  data.locations[rowIndex].zip != ""
) {
  if (data.locations[rowIndex].zip.toString().length === 5) {
    Formio.fetch("https://api.npoint.io/2c71ded6354de7428006", {
      method: "GET",
    }).then(function (response) {
      response.json().then(function (result) {
        let gotState = 0;
        const object = result;
        const statecityData = Object.entries(object);
        for (var i = 0; i < statecityData.length; i++) {
          for (var j = 0; j < statecityData[i].length; j++) {
            if (
              typeof statecityData[i][j] === "object" &&
              statecityData[i][j] !== null
            ) {
              if ("cities" in statecityData[i][j]) {
                var citiesData = Object.entries(statecityData[i][j].cities);
                if (typeof citiesData === "object" && citiesData !== null) {
                  for (k = 0; k < citiesData.length; k++) {
                    var a = citiesData[k][1].indexOf(
                      parseInt(data.locations[rowIndex].zip)
                    );
                    if (a > -1) {
                      for (
                        var stateLength = 0;
                        stateLength < data.stateListJson.length;
                        stateLength++
                      ) {
                        if (
                          statecityData[i][j].name ===
                          data.stateListJson[stateLength].name
                        ) {
                          data.locations[rowIndex].stateName =
                            data.stateListJson[stateLength];
                          row.stateName = data.stateListJson[stateLength];
                          data.locations[rowIndex].state =
                            data.stateListJson[stateLength].abbreviation;
                          row.state =
                            data.stateListJson[stateLength].abbreviation;
                        }
                      }
                      gotState = 1;
                      data.locations[rowIndex].city = citiesData[k][0];
                      row.city = citiesData[k][0];
                      form.getComponent("locations").redraw();
                      break;
                    }
                  }
                }
              }
            }
          }
        }
        if (gotState.toString() === "0") {
          alert("Entered Zipcode is not Found.");
        }
      });
    });
  }
}
