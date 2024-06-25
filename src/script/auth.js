import { Api } from "./api.js";
import { Render } from "./render.js";

//declaration
var api = new Api();
var render = new Render(api);

api.getAuthError().then((response) => {
    console.log(response);
    render.showToast("hey there-_-","blue");
});

