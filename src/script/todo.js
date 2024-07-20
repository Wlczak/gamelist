import { Api } from "./api.js";
import { Render } from "./render.js";

//declaration
var api = new Api();
var render = new Render(api);
var pointCounter = document.getElementById("pointCounter");

api.getList(1).then((response) => {
    if (typeof response[0] == "object") {
        response.forEach((taskData) => {
            render.addTask(taskData["id"], taskData["content"], taskData["pointScore"]);
        });
    } else {
        //error has occured
        console.log(response);
    }
});

api.getPoints().then((response) => {
    console.log(response);
    pointCounter.innerHTML = response.points;
});
