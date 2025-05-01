export class Swapper {
    async fetch_body(uri) {
        const res = await fetch(uri);
        let html;
        if (res.ok) {
            html = await res.text();
        } else {
            html = `
            <div style="display: flex; justify-content: center; align-items: center; height: 100vh;">
                <div>
                    <h1 style="text-align: center;">Error ${res.status}</h1>
                    <p style="text-align: center;">${res.statusText}</p>
                </div>
            </div>
            `;
        }
        const parser = new DOMParser();
        const doc = parser.parseFromString(html, "text/html");
        document.getElementById("body-wrapper").innerHTML = doc.body.innerHTML;
    }
}
