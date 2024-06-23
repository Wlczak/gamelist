import { Api } from "./api.js";
import { Render } from "./render.js";

//declaration
var api = new Api();
var render = new Render();

api.getList(1, true).then((response) => {
    response.forEach((taskData) => {
        render.addTask(taskData["content"], taskData["pointScore"]);
    });
});
