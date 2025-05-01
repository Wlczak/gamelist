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
                if (result.status) {
                    gtag("event", "task_done");
                    this.doneTask(id, taskScore);
                } else {
                    location.reload();
                }
            });
        });
        buttonDiv.appendChild(doneButton);

        deleteButton.type = "button";
        deleteButton.className = "btn btn-danger";
        deleteButton.addEventListener("click", () => {
            this.api.removeTask(id).then((result) => {
                if (result.status) {
                    gtag("event", "task_deleted");
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

    refreshItems() {
        this.api.getList(2).then((response) => {
            document.getElementById("itemParent").innerHTML = "";
            if (typeof response[0] == "object") {
                response.forEach((itemData) => {
                    this.addItem(
                        itemData["id"],
                        itemData["content"],
                        itemData["pointScore"],
                        itemData["count"]
                    );
                });
            } else {
                //error has occured
            }
        });
    }

    addItem(id, content, pointScore, count) {
        const taskParent = document.getElementById("itemParent");
        const taskDiv = document.createElement("div");
        taskDiv.className = "row border justify-content-between";
        taskDiv.id = id;

        // Content column
        const contentCol = document.createElement("div");
        contentCol.className = "col";
        const innerDiv = document.createElement("div");
        const heading = document.createElement("h2");
        const nameSpan = document.createElement("span");
        nameSpan.className = "itemName";
        nameSpan.textContent = content;
        const countSpan = document.createElement("span");
        countSpan.className = "itemCount";
        countSpan.textContent = count;
        heading.appendChild(nameSpan);
        heading.appendChild(document.createTextNode(" x "));
        heading.appendChild(countSpan);
        innerDiv.appendChild(heading);
        contentCol.appendChild(innerDiv);

        // Edit icon column
        const editCol = document.createElement("div");
        editCol.className = "col-1";
        const svg = document.createElementNS("http://www.w3.org/2000/svg", "svg");
        svg.setAttribute("class", "bi");
        svg.setAttribute("width", "32");
        svg.setAttribute("height", "32");
        svg.setAttribute("fill", "currentColor");
        svg.setAttribute("style", "margin-top: 5px;");
        const use = document.createElementNS("http://www.w3.org/2000/svg", "use");
        use.setAttributeNS(
            "http://www.w3.org/1999/xlink",
            "xlink:href",
            "vendor/twbs/bootstrap-icons/bootstrap-icons.svg#pencil"
        );
        svg.appendChild(use);
        editCol.appendChild(svg);

        // Buttons column
        const buttonDiv = document.createElement("div");
        buttonDiv.className = "btn-group col-md-5 mt-1 mb-1";

        const buyButton = document.createElement("button");
        buyButton.type = "button";
        buyButton.className = "btn btn-success";
        buyButton.textContent = "Buy";
        buyButton.addEventListener("click", () => {
            this.api.doneTask(id, pointScore).then((result) => {
                if (result.status) {
                    gtag("event", "item_bought");
                    this.doneTask(id, pointScore);
                } else {
                    location.reload();
                }
            });
        });
        buttonDiv.appendChild(buyButton);

        const deleteButton = document.createElement("button");
        deleteButton.type = "button";
        deleteButton.className = "btn btn-danger";
        deleteButton.textContent = "Delete";
        deleteButton.addEventListener("click", () => {
            this.api.removeTask(id).then((result) => {
                if (result.status) {
                    gtag("event", "item_deleted");
                    this.removeTask(id);
                } else {
                    location.reload();
                }
            });
        });
        buttonDiv.appendChild(deleteButton);

        taskDiv.appendChild(contentCol);
        taskDiv.appendChild(editCol);
        taskDiv.appendChild(buttonDiv);
        taskParent.appendChild(taskDiv);
    }

    async changeScore(score) {
        var i = 1;
        var pointCounter = document.getElementById("pointCounter");
        var counter = 0;
        var add = 1;
        while (Math.abs(score) / 100 > add) {
            add = add * 10;
        }
        var time = 1000 / score; //time in ms

        if (score >= 0) {
            while (i <= score) {
                if ((score - i) / 10 < add && add > 1) {
                    add = add / 10;
                } else {
                    counter = parseInt(pointCounter.innerHTML) + add;
                    pointCounter.innerHTML = counter;
                    i = i + add;
                    await this.delay(time);
                }
            }
        } else {
            score = Math.abs(score);

            while (i <= score) {
                if ((score - i) / 10 < add && add > 1) {
                    add = add / 10;
                } else {
                    counter = parseInt(pointCounter.innerHTML) - add;
                    pointCounter.innerHTML = counter;
                    i = i + add;
                    await this.delay(time);
                }
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
