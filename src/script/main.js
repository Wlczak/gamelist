import { Api } from "./api.js";
import { Render } from "./render.js";

//declaration
var api = new Api();
var render = new Render(api);

api.getList(1, true).then((response) => {
    response.forEach((taskData) => {
        render.addTask(taskData["id"],taskData["content"], taskData["pointScore"]);
    });
});
