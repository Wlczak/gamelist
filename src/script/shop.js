import { Api } from "./api.js";
import { Render } from "./render.js";

export class Shop {
    setup() {
        let api = new Api();
        let render = new Render(api);
        this.setupModal();
        api.getPoints().then((response) => {
            render.setScore(response.points);
        });
        new Render(new Api()).refreshItems();
    }

    setupModal() {
        let api = new Api();
        let render = new Render(api);
        document.getElementById("addItemButton").addEventListener("click", () => {
            let score = document.getElementById("scoreValue").value;
            let content = document.getElementById("itemName").value;
            let count = document.getElementById("itemCount").value;

            if (score == "" || content == "" || count == "") {
                render.showToast("Please fill out all inputs", "red");
            } else {
                api.createItem(content, score, count).then((response) => {
                    if (response.msg == undefined) {
                        render.showToast("Failed adding new task", "red");
                    } else {
                        render.showToast("Task added succesfully", "green");
                        render.refreshItems();
                    }
                });
            }
        });
    }
}
