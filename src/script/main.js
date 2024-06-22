import { Api } from "./api.js";
var api = new Api();
var array = {
    requestType: "getList",
};
api.apiQuery(array).then((data) => {
    console.log(data);
});
