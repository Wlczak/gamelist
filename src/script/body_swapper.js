export class Swapper {
    fetch_body(uri) {
        fetch(uri)
            .then((res) => res.text())
            .then((html) => {
                const parser = new DOMParser();
                const doc = parser.parseFromString(html, "text/html");
                //const part = doc.querySelector("#someElement");
                //document.body.appendChild(part);
                return doc;
            })
            .then((doc) => {
                document.getElementById("body-wrapper").innerHTML = doc.body.innerHTML;
            });
    }
}
