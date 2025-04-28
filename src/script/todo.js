import { Api } from "./api.js";
import { Render } from "./render.js";
import { Router } from "./router.js";
//declaration
var api = new Api();
var render = new Render(api);
var router = new Router();

if (window.location.pathname == "/") {
    await swapper.fetch_body(window.location.origin + "/components/todo");
} else {
    await swapper.fetch_body(
        window.location.origin + "/components/" + window.location.pathname.split("/").pop()
    );
}

switch (window.location.pathname) {
    case "/":
        setupTodo();
}

function setupTodo() {
    render.refreshTasks();

    api.getPoints().then((response) => {
        render.changeScore(response.points);
    });
    document.getElementById("addTaskButton").addEventListener("click", (e) => {
        var score = document.getElementById("scoreValue").value;
        var content = document.getElementById("taskName").value;

        if (score == "" || content == "") {
            render.showToast("Please fill out all inputs", "red");
        } else {
            api.createTask(content, score).then((response) => {
                if (response.msg == undefined) {
                    render.showToast("Failed adding new task", "red");
                } else {
                    render.showToast("Task added succesfully", "green");
                    render.refreshTasks();
                }
            });
        }
    });
}
