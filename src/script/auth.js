import { Api } from "./api.js";
import { Render } from "./render.js";

//declaration
var api = new Api();
var render = new Render(api);

api.getAuthError().then((response) => {
    if (response.authMsg) {
        if (response.authError) {
            render.showToast(response.authMsg, "red");
        } else {
            render.showToast(response.authMsg, "green");
        }
    }
    if (response.authToken != undefined && response.authToken != null) {
        document.cookie = "authToken=" + response.authToken;
        setTimeout(() => {
            window.location.replace("/gamelist");
            
        }, 750);
    }
});
