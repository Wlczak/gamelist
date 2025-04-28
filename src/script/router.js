import { Swapper } from "./body_swapper.js";

export class Router {
    swapper;
    constructor() {
        this.swapper = new Swapper();
    }

    async fetch_body() {
        if (window.location.pathname == "/") {
            await this.swapper.fetch_body(window.location.origin + "/components/todo");
        } else {
            await this.swapper.fetch_body(
                window.location.origin + "/components/" + window.location.pathname.split("/").pop()
            );
            console.log("hi")
        }
    }

    addPath(path, callback) {
        if (window.location.pathname == path) {
            this.fetch_body().then(() => callback());
        }
    }
}
