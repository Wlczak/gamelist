import { Api } from "./api.js";
import { Render } from "./render.js";

export class Shop {
    setup() {
        this.setupModal();
    }

    setupModal() {
        let api = new Api();
        let render = new Render(api);
        document.getElementById("addItemButton").addEventListener("click", () => {
            let score = document.getElementById("scoreValue").value;
            let content = document.getElementById("taskName").value;
            let count = document.getElementById("itemCount").value;

            if (score == "" || content == "" || count == "") {
                render.showToast("Please fill out all inputs", "red");
            } else {
                api.createItem(content, score, count).then((response) => {
                    if (response.msg == undefined) {
                        render.showToast("Failed adding new task", "red");
                    } else {
                        render.showToast("Task added succesfully", "green");
                        render.refreshTasks();
                    }
                });
            }
        });
    }
}
