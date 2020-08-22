function insertAfter(referenceNode, newNode) {
  referenceNode.parentNode.insertBefore(newNode, referenceNode.nextSibling);
}

var s = document.createElement("script");
s.src = "https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js";
s.onload = function (e) {
  var appendCustomButtonTimer = setInterval(() => {
    if (
      document.getElementById(
        "formio_loader_280dbc6d-638e-4fba-9095-8430c226ec7b"
      )
    ) {
      if (!document.getElementById("saveDraftCustomButton")) {
        let clone = $("ul[id*=nav]").children()[0].cloneNode(true);
        clone.children[0].textContent = "Save Draft";
        clone.children[0].id = "saveDraftCustomButton";
        insertAfter($("ul[id*=nav]").children()[0], clone);
        saveDraftCustomButton.onclick = function () {
          var closeForm = confirm(
            "Do you want to close the form after saving the data?"
          );
          let ev = new CustomEvent("customButtonAction", {
            detail: {
              timerVariable: appendCustomButtonTimer,
              formData: data,
              commands:
                '[{ "command": "fileSave", "entity_name": "Dealer Policy" }]',
              exit: closeForm,
              notification: "Form saved successfully"
            },
            bubbles: false
          });
          document
            .getElementById(
              "formio_loader_280dbc6d-638e-4fba-9095-8430c226ec7b"
            )
            .dispatchEvent(ev);
        };
      }
      var dataGridDeleteIcons = document.getElementsByClassName(
        "fa-times-circle-o"
      );
      dataGridDeleteIcons = Array.from(dataGridDeleteIcons);
      if (dataGridDeleteIcons.length > 0) {
        dataGridDeleteIcons.map((item) => {
          item.classList.add("fa-times-circle");
          item.classList.remove("fa-times-circle-o");
        });
      }

      if (
        [...document.querySelectorAll('[ref="modalSave"]')].some(
          (i) => i.innerText == "SAVE"
        )
      ) {
        [...document.querySelectorAll('[ref="modalSave"]')].map(
          (i) => i.innerText = "OK"
        );
      }
    } else {
      appendCustomButtonTimer ? clearInterval(appendCustomButtonTimer) : null;
    }
  }, 1000);
};
document.head.appendChild(s);

setTimeout(function () {
  data.workbooksToBeGenerated = {
    epli: false,
    rpsCyber: false,
    harco: false,
    dealerGuard_ApplicationOpenLot: false,
    victor_FranchisedAutoDealer: false,
    victor_AutoPhysDamage: false
  };
}, 2000);