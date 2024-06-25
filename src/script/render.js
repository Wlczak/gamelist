export class Render {
    constructor(api) {
        this.api = api;
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
        buttonDiv.appendChild(doneButton);

        deleteButton.type = "button";
        deleteButton.className = "btn btn-danger";
        deleteButton.addEventListener("click", (e) => {
            this.api.removeTask(id, true).then((result) => {
                console.log(result);
                this.removeTask(id);
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
    showToast(content, color) {
        //create tags
        var toastContainer = document.getElementById("toast-container")
        var toast = document.createElement("div");
        var flex = document.createElement("div");
        var toastContent = document.createElement("div");
        var closeBtn = document.createElement("button");
        //setting properies
        //toastContainer.className = "toast-container position-fixed bottom-0 end-0 p-3";

        toast.className = "toast align-items-center text-bg-primary border-0";
        toast.id = "toast";
        toast.role = "alert";
        toast.setAttribute("aria-live", "assertive");
        toast.setAttribute("aria-atomic", "true");

        flex.className = "d-flex";

        toastContent.className = "toast-body";
        toastContent.innerHTML = content;
        flex.appendChild(toastContent);

        closeBtn.type = "button";
        closeBtn.className = "btn-close btn-close-white me-2 m-auto";
        closeBtn.setAttribute("data-bs-dismiss", "toast");
        closeBtn.setAttribute("aria-label", "Close");
        flex.appendChild(closeBtn);

        //append
        toast.appendChild(flex);
        toastContainer.appendChild(toast);

        const toastElement = document.getElementById("toast");
        const toastInstance = new bootstrap.Toast(toastElement);
        toastInstance.show();
    }
}
