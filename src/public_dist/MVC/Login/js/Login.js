var __decorate = (this && this.__decorate) || function (decorators, target, key, desc) {
    var c = arguments.length, r = c < 3 ? target : desc === null ? desc = Object.getOwnPropertyDescriptor(target, key) : desc, d;
    if (typeof Reflect === "object" && typeof Reflect.decorate === "function") r = Reflect.decorate(decorators, target, key, desc);
    else for (var i = decorators.length - 1; i >= 0; i--) if (d = decorators[i]) r = (c < 3 ? d(r) : c > 3 ? d(target, key, r) : d(target, key)) || r;
    return c > 3 && r && Object.defineProperty(target, key, r), r;
};
var __metadata = (this && this.__metadata) || function (k, v) {
    if (typeof Reflect === "object" && typeof Reflect.metadata === "function") return Reflect.metadata(k, v);
};
import { container } from "tsyringe";
import { injectable } from "tsyringe";
import { LoginForm } from "./LoginForm";
import { Modal } from "bootstrap";
import { FetchService } from "../../../js/FetchService";
let Login = class Login {
    constructor(fetchService) {
        this.fetchService = fetchService;
        this.controller = "Login";
    }
    async authenticate() {
        var _a, _b;
        try {
            const loginForm = container.resolve(LoginForm);
            if (loginForm.getForm() === null)
                return;
            let form = loginForm.getForm();
            let isValid = await loginForm.validate();
            if (!isValid)
                return;
            await loginForm.clearErrors();
            let fetchResponse = await this.fetchService.post("/", new FormData(form));
            if (fetchResponse.redirected) {
                window.document.location = fetchResponse.url;
                return;
            }
            let response = await fetchResponse.json();
            if (response.error) {
                let setFieldErrors = loginForm.setFieldErrors((_a = response.error_fields) !== null && _a !== void 0 ? _a : []);
                let body = document.getElementById("modalBody");
                let btnFormOk = document.getElementById("btn-form-ok");
                if (body !== null)
                    body.innerText = (_b = response.msg) !== null && _b !== void 0 ? _b : "";
                let modalDom = document.getElementById("confirmDialog");
                modalDom.addEventListener("shown.bs.modal", function (event) {
                    btnFormOk === null || btnFormOk === void 0 ? void 0 : btnFormOk.focus();
                });
                modalDom.addEventListener("hidden.bs.modal", function (event) {
                    var _a;
                    (_a = document.getElementsByClassName("is-invalid").item(0)) === null || _a === void 0 ? void 0 : _a.focus();
                });
                (new Modal(modalDom, {
                    backdrop: false
                })).show();
                Promise.all([setFieldErrors])
                    .catch((e) => {
                    console.log(`Exception while set filds with error in form ${form.id} with error ${e}`);
                })
                    .then(() => {
                    console.log(`Was set filds with error in form ${form.id}`);
                });
                return;
            }
            this.afterAuthenticate();
        }
        catch (e) {
            console.log(e);
        }
    }
    async logout() {
        let myWindow = window.location;
        window.document.location = `${myWindow.protocol}//${myWindow.hostname}${myWindow.pathname}?logout=1`;
    }
    afterAuthenticate() {
        let myWindow = window.location;
        window.document.location = `${myWindow.protocol}//${myWindow.hostname}${myWindow.pathname}`;
    }
};
Login.logoutConfirmDialog = null;
Login = __decorate([
    injectable(),
    __metadata("design:paramtypes", [FetchService])
], Login);
export { Login };
//# sourceMappingURL=Login.js.map