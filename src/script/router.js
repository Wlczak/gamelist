import { Swapper } from "./body_swapper.js";

export class Router {
    swapper;
    routes = [];
    constructor() {
        this.swapper = new Swapper();

        window.addEventListener("pushstate", () => {
            this.run();
        });
    }

    async fetch_body() {
        if (window.location.pathname == "/todo") {
            await this.swapper.fetch_body(window.location.origin + "/components/todo");
        } else {
            await this.swapper.fetch_body(
                window.location.origin + "/components/" + window.location.pathname.split("/").pop()
            );
            //console.log("hi");
        }
    }

    run() {
        this.routes.forEach((element) => {
            if (window.location.pathname == element.path) {
                this.fetch_body().then(() => element.callback());
            }
        });
    }

    addPath(path, callback, addButton = false, name = "") {
        this.routes.push({ path: path, callback: callback });
        if (addButton) {
            const button = document.createElement("button");
            if (name != "") {
                button.innerHTML = name;
            } else {
                button.innerHTML = path.split("/").pop();
            }
            button.className = "btn btn-primary";
            button.addEventListener("click", () => {
                history.pushState({}, "", path);
                window.dispatchEvent(new Event("pushstate"));
            });
            document.getElementById("nav-buttons").appendChild(button);
        }
    }
}
