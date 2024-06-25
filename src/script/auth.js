import { Api } from "./api.js";
import { Render } from "./render.js";

//declaration
var api = new Api();
var render = new Render(api);

api.getAuthError().then((response) => {
    console.log(response);
    if (response.authMsg) {
        render.showToast(response.authMsg, "blue");
    }
});
