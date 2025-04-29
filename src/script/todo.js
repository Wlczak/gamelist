import { Api } from "./api.js";
import { Render } from "./render.js";
import { Router } from "./router.js";
import { Shop } from "./shop.js";
//declaration
var api = new Api();
var render = new Render(api);
var router = new Router();

router.addPath(
    "/",
    () => {
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
    },
    true,
    "Home"
);

router.addPath("/shop", () => {
   let shop = new Shop();
}, true, "Shop");

router.run();
