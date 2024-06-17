var array = {
    key1: "value1",
    key2: "value2",
    key3: "value3",
};
apiQuery(array).then((data) => {
    console.log(data);
});

function apiQuery(array) {
    var data = JSON.stringify(array);
    return fetch(window.location.protocol + "//" + window.location.hostname + "/gamelist/api", { // Added return here
        method: "POST",
        headers: {
            'Content-Type': 'application/json'
        },
        body: data,
    })
        .then((response) => response.json())
        .then((data) => {
            // Handle the response from the PHP script if needed
            return data;
        })
        .catch((error) => {
            // Handle any errors that occurred during the fetch or processing
            console.error("Error in API call:", error);
            throw error; // Re-throw the error to propagate it to the caller
        });
}


