export class Render {
    constructor(api) {
        this.api = api;
    }
    refreshTasks() {
        this.api.getList(1).then((response) => {
            document.getElementById("taskParent").innerHTML = "";
            if (typeof response[0] == "object") {
                response.forEach((taskData) => {
                    this.addTask(taskData["id"], taskData["content"], taskData["pointScore"]);
                });
            } else {
                //error has occured
                console.log(response);
            }
        });
    }

    addTask(id, content, pointScore) {
        var taskParent = document.getElementById("taskParent");
        var taskDiv = document.createElement("div");
        var contentDiv = document.createElement("div");
        var contentTag = document.createElement("h4");
        var buttonDiv = document.createElement("div");
        var doneButton = document.createElement("button");
        var deleteButton = document.createElement("button");

        taskDiv.className = "row border justify-content-between";
        taskDiv.setAttribute("style", "transition: margin-top 1s;");
        taskDiv.id = id;
        //taskDiv.innerHTML =
        // "<div class='col'><h2>task</h2></div><div class='btn-group col-md-5 mt-1 mb-1'><button type='button' class='btn btn-success'>Done</button><button type='button' class='btn btn-danger'>Delete</button></div>";

        //content
        contentDiv.className = "col d-flex align-items-center";
        contentTag.className = "";
        contentTag.innerHTML = content;
        contentDiv.appendChild(contentTag);

        //buttons
        buttonDiv.className = "btn-group col-md-5 mt-1 mb-1";
        doneButton.type = "button";
        doneButton.className = "btn btn-success";
        doneButton.innerHTML = "+" + pointScore;
        doneButton.value = pointScore;
        doneButton.addEventListener("click", (e) => {
            var taskScore = e.target.value;
            this.api.doneTask(id, taskScore).then((result) => {
                console.log(result);
                if (result.status) {
                    this.doneTask(id, taskScore);
                } else {
                    location.reload();
                }
            });
        });
        buttonDiv.appendChild(doneButton);

        deleteButton.type = "button";
        deleteButton.className = "btn btn-danger";
        deleteButton.addEventListener("click", (e) => {
            this.api.removeTask(id).then((result) => {
                console.log(result);
                if (result.status) {
                    this.removeTask(id);
                } else {
                    location.reload();
                }
            });
        });
        deleteButton.innerHTML = "Delete";
        buttonDiv.appendChild(deleteButton);

        //append
        taskDiv.appendChild(contentDiv);
        taskDiv.appendChild(buttonDiv);

        taskParent.append(taskDiv);
    }
    removeTask(id) {
        const task = document.getElementById(id);
        // task.remove();
        task.animate(
            [
                { scale: 1, opacity: 1 },
                { scale: 0, opacity: 0 },
            ],
            { duration: 250, fill: "forwards", easing: "ease-in" }
        ).onfinish = function () {
            task.remove();
        };
    }
    doneTask(id, taskScore) {
        const task = document.getElementById(id);
        task.animate(
            [
                { scale: 1, opacity: 1 },
                { scale: 10, opacity: 0 },
            ],
            { duration: 250, fill: "forwards", easing: "ease-in" }
        ).onfinish = () => {
            task.remove();
            this.changeScore(taskScore);
        };
    }
    async changeScore(score) {
        //console.log(score);

        var i = 1;
        var pointCounter = document.getElementById("pointCounter");
        var counter = 0;
        var time = 1000 / score; //time in ms
        var add = 10;

        if (score >= 0) {
            while (i <= score) {
                if (score - i < 250) add = 1;
                counter = parseInt(pointCounter.innerHTML) + add;
                pointCounter.innerHTML = counter;
                i = i + add;
                await this.delay(time);
            }
        } else {
            while (i >= score) {
                if (score + i < 250) add = 1;
                counter = parseInt(pointCounter.innerHTML) - add;
                pointCounter.innerHTML = counter;
                i = i - add;
                await this.delay(time);
            }
        }
    }

    delay(ms) {
        return new Promise((resolve) => setTimeout(resolve, ms));
    }

    showToast(content, color) {
        var toastName = content + "-" + color + Date.now();

        //create tags
        var toastContainer = document.getElementById("toast-container");
        var toast = document.createElement("div");
        var flex = document.createElement("div");
        var toastContent = document.createElement("div");
        var closeBtn = document.createElement("button");
        //setting properies
        //toastContainer.className = "toast-container position-fixed bottom-0 end-0 p-3";
        var colorClassName;
        switch (color) {
            case "blue":
                colorClassName = "text-bg-primary";
                break;
            case "red":
                colorClassName = "text-bg-danger";
                break;
            case "green":
                colorClassName = "text-bg-success";
                break;

            default:
                break;
        }

        toast.className = "toast align-items-center border-0 " + colorClassName;
        toast.id = toastName;
        toast.role = "alert";
        toast.setAttribute("aria-live", "assertive");
        toast.setAttribute("aria-atomic", "true");

        flex.className = "d-flex";

        toastContent.className = "toast-body";
        toastContent.innerHTML = content;
        flex.appendChild(toastContent);

        closeBtn.type = "button";
        closeBtn.className = "btn-close btn-close-white me-2 m-auto";
        closeBtn.setAttribute("data-bs-dismiss", toastName);
        closeBtn.setAttribute("aria-label", "Close");
        flex.appendChild(closeBtn);

        //append
        toast.appendChild(flex);
        toastContainer.appendChild(toast);

        const toastElement = document.getElementById(toastName);
        const toastInstance = new bootstrap.Toast(toastElement);
        toastInstance.show();
    }
}
