var array = {
    key1: "value1",
    key2: "value2",
    key3: "value3",
};

function sendArray(array) {
    var data = JSON.stringify(array);
    fetch(window.location.protocol + "//" + window.location.hostname + "/gamelist/api", {
        method: "POST",
        body: data,
    })
        .then((response) => response.json())
        .then((data) => {
            // Handle the response from the PHP script if needed
            console.log(data);
        });
}
