$(".form")
  .find("input, textarea")
  .on("keyup blur focus", function (e) {
    var $this = $(this),
      label = $this.prev("label");

    if (e.type === "keyup") {
      if ($this.val() === "") {
        label.removeClass("active highlight");
      } else {
        label.addClass("active highlight");
      }
    } else if (e.type === "blur") {
      if ($this.val() === "") {
        label.removeClass("active highlight");
      } else {
        label.removeClass("highlight");
      }
    } else if (e.type === "focus") {
      if ($this.val() === "") {
        label.removeClass("highlight");
      } else if ($this.val() !== "") {
        label.addClass("highlight");
      }
    }
  });

$(".tab a").on("click", function (e) {
  e.preventDefault();

  $(this)
    .parent()
    .addClass("active");
  $(this)
    .parent()
    .siblings()
    .removeClass("active");

  target = $(this).attr("href");

  $(".tab-content > div")
    .not(target)
    .hide();

  $(target).fadeIn(600);
});

document.addEventListener("DOMContentLoaded", function () {
  $(".loginButton").on("click", function (e) {
    var username = document.getElementsByClassName("userNameField")[0].value;
    var password = document.getElementsByClassName("passwordField")[0].value;
    const formData = new FormData();
    formData.append("username", username);
    formData.append("password", password);
    let response = fetch(baseUrl + "auth", {
      body: formData,
      method: "POST",
      mode: "cors"
    }).then(response => {
      return response.json();
    });
    response.then(res => {
      if (res.status == "success") {
        autoLogin(res.data);
      } else {
        document.getElementById("wrongPassword").style.display = "block";
        document.getElementById("wrongPassword").style.color = "red";
      }
    });
  });

  function autoLogin(data) {
    localStorage.clear();
    localStorage.setItem(
      "User",
      JSON.stringify({ key: data.username, timestamp: new Date() })
    );
    localStorage.setItem(
      "AUTH_token",
      JSON.stringify({ key: data.jwt, timestamp: new Date() })
    );
    localStorage.setItem(
      "REFRESH_token",
      JSON.stringify({ key: data.refresh_token, timestamp: new Date() })
    );
    window.location.href = window.location.origin;
  }

  Formio.createForm(
    document.getElementById("formio"),
    JSON.parse(formContent)
  ).then(function (form) {
    // Prevent the submission from going to the form.io server.
    form.nosubmit = true;
    // Triggered when they click the submit button.
    form.on("submit", function (submission) {
      submission.data.app_id = appId;
      var response = fetch(baseUrl + "register", {
        body: JSON.stringify(submission.data),
        headers: {
          "content-type": "application/json"
        },
        method: "POST",
        mode: "cors"
      }).then(response => {
        form.emit("submitDone", submission);
        return response.json();
      });
      response.then(res => {
        autoLogin(res.data);
      });
    });
    form.on("callDelegate", changed => {
      var component = form.getComponent(event.target.id);
      if (component) {
        var properties = component.component.properties;
        if (properties) {
          if (properties["delegate"]) {
            $.ajax({
              type: "POST",
              async: false,
              url:
                baseUrl +
                "app/" +
                appId +
                "/delegate/" +
                properties["delegate"],
              data: changed,
              success: function (response) {
                if (response.data) {
                  form.submission = { data: response.data };
                  form.triggerChange();
                }
              }
            });
          }
        }
      }
    });
    form.on("change", changed => {
      if (changed && changed.changed) {
        var component = changed.changed.component;
        var properties = component.properties;
        if (properties) {
          if (properties["delegate"]) {
            $.ajax({
              type: "POST",
              async: true,
              url:
                baseUrl +
                "app/" +
                appId +
                "/delegate/" +
                properties["delegate"],
              data: changed.data,
              success: function (response) {
                if (response.data) {
                  form.submission = { data: response.data };
                  form.triggerChange();
                }
              }
            });
          }
        }
      }
    })
  });
});
