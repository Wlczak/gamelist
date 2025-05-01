export class Api {
    apiQuery(array) {
        var data = JSON.stringify(array);
        return fetch(window.location.origin + window.APP_CONFIG.appUrl + "/api", {
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
    getPoints() {
        var request = {
            requestType: "getPoints",
        };
        return this.apiQuery(request);
    }
    doneTask(taskId, taskScore) {
        var request = {
            requestType: "doneTask",
            taskId: taskId,
            taskScore: taskScore,
        };
        return this.apiQuery(request);
    }
    createTask(taskContent, taskScore) {
        var request = {
            requestType: "createTask",
            taskContent: taskContent,
            taskScore: taskScore,
        };
        return this.apiQuery(request);
    }

    createItem(itemContent, itemScore, itemCount) {
        var request = {
            requestType: "createItem",
            itemContent: itemContent,
            itemScore: itemScore,
            itemCount: itemCount,
        };
        return this.apiQuery(request);
    }
    boughtItem(id, pointScore) {
        var request = {
            requestType: "boughtItem",
            itemId: id,
            pointScore: pointScore,
        };
        return this.apiQuery(request);
    }
}
