function insertAfter(referenceNode, newNode) {
  referenceNode.parentNode.insertBefore(newNode, referenceNode.nextSibling);
}

var s = document.createElement("script");
s.src = "https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js";
s.onload = function (e) {
  var appendCustomButtonTimer = setInterval(() => {
    if (
      document.getElementById(
        "formio_loader_2b4a5099-4a2b-415a-b1d1-b539cbe1eee7"
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
              exit: true,
            },
            bubbles: false,
          });
          document
            .getElementById(
              "formio_loader_2b4a5099-4a2b-415a-b1d1-b539cbe1eee7"
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
