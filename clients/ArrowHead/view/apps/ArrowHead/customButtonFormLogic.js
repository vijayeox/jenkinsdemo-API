function insertAfter(referenceNode, newNode) {
  referenceNode.parentNode.insertBefore(newNode, referenceNode.nextSibling);
}

var s = document.createElement("script");
s.src = "https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js";
s.onload = function (e) {
  var appendCustomButtonTimer = setInterval(() => {
    if (
      document.getElementById(
        "formio_loader_e980dac2-2ef7-467a-8e0d-863078c458e0"
      )
    ) {
      if (!document.getElementById("saveDraftCustomButton")) {
        let clone = $("ul[id*=nav]").children()[0].cloneNode(true);
        clone.children[0].textContent = "Save Draft";
        clone.children[0].id = "saveDraftCustomButton";
        insertAfter($("ul[id*=nav]").children()[0], clone);
        saveDraftCustomButton.onclick = function () {
          let ev = new CustomEvent("customButtonAction", {
            detail: {
              timerVariable: appendCustomButtonTimer,
              formData: data,
              commands:
                '[{ "command": "fileSave", "entity_name": "Dealer Policy" }]',
              exit: true
            },
            bubbles: false
          });
          document
            .getElementById(
              "formio_loader_e980dac2-2ef7-467a-8e0d-863078c458e0"
            )
            .dispatchEvent(ev);
        };
      }
    } else {
      appendCustomButtonTimer ? clearInterval(appendCustomButtonTimer) : null;
    }
  }, 1000);
};
document.head.appendChild(s);

fetch("https://api.npoint.io/ac5a62e2a6c18c0dbb6c")
  .then((response) => response.json())
  .then((jsondata) => {
    setTimeout(() => {
      for (item in jsondata) {
        data[item] = jsondata[item];
      }
    }, 2000);
  });
