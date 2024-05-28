$("#submit-btn").click(function() {

    let _name = $("#name").val()
    let _password = $("#password").val()

    let dataToSend = JSON.stringify(
        {
            name: _name,
            password: _password
        }
    )

    console.log(dataToSend);

    fetch("server.php", {
        "method" : "POST",
        "headers" : {
            "Content-Type" : "application/json; charset=utf-8"
        },
        "body" : dataToSend
      }).then(function(response) {
        console.log(response);
        return response.json();
      }).then(function(data) {
        console.log(data);
        if(data["type"]) {
          console.log("TRUE");
          } else {
          console.log("FALSE");
        }
      });
})