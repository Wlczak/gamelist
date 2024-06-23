import { Api } from "./api.js";
var api = new Api();

api.getList(1, true).then((response) => {
    console.log(response);
});

/*api.apiQuery(array).then((data) => {
    console.log(data);
});*/
addNewTask("hello", 69);
function addNewTask(content, poinScore) {
    var div = document.createElement("div");
    div.className = "row border justify-content-between";
    div.innerHTML =
        "<div class='col'><h2>task</h2></div><div class='btn-group col-md-5 mt-1 mb-1'><button type='button' class='btn btn-success'>Done</button><button type='button' class='btn btn-danger'>Delete</button></div>";

    document.getElementById("taskParent").append(div);
}
