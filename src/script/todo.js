import { Api } from "./api.js";
import { Render } from "./render.js";
import { Swapper } from "./body_swapper.js";

//declaration
var api = new Api();
var render = new Render(api);
var swapper = new Swapper();

//swapper.fetch_body(window.location.origin + "/components/" + window.location.pathname.split("/").pop());

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
