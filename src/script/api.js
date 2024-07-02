export class Api {
    apiQuery(array) {
        var data = JSON.stringify(array);
        return fetch(window.location.protocol + "//" + window.location.hostname + "/gamelist/api", {
            // Added return here
            method: "POST",
            headers: {
                "Content-Type": "application/json",
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
    getList(listId) {
        var request = {
            requestType: "getList",
            listId: listId,
        };
        return this.apiQuery(request);
    }
    removeTask(taskId) {
        var request = {
            requestType: "removeTask",
            taskId: taskId,
        };
        return this.apiQuery(request);
    }
    getAuthError() {
        var request = {
            requestType: "getAuthError",
        };
        return this.apiQuery(request);
    }
}
