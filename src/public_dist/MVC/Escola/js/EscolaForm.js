var __decorate = (this && this.__decorate) || function (decorators, target, key, desc) {
    var c = arguments.length, r = c < 3 ? target : desc === null ? desc = Object.getOwnPropertyDescriptor(target, key) : desc, d;
    if (typeof Reflect === "object" && typeof Reflect.decorate === "function") r = Reflect.decorate(decorators, target, key, desc);
    else for (var i = decorators.length - 1; i >= 0; i--) if (d = decorators[i]) r = (c < 3 ? d(r) : c > 3 ? d(target, key, r) : d(target, key)) || r;
    return c > 3 && r && Object.defineProperty(target, key, r), r;
};
var __metadata = (this && this.__metadata) || function (k, v) {
    if (typeof Reflect === "object" && typeof Reflect.metadata === "function") return Reflect.metadata(k, v);
};
import { injectable } from "tsyringe";
import { FormUtils } from "../../../js/FormUtils";
import { Util } from "../../../js/Util";
import { Escola } from "./Escola";
import { FetchService } from "../../../js/FetchService";
import { Dropdown } from "bootstrap";
let EscolaForm = class EscolaForm extends FormUtils {
    constructor(fetchService) {
        super(Escola.baseUrl, fetchService);
        this.fetchService = fetchService;
        this.newPasswordBtn = null;
        this.newPassword = null;
        this.newPassword2 = null;
        this.dropdown = null;
    }
    getTable() {
        return this._getTable(Escola.tableId);
    }
    domStack() {
        return new Promise(async (resolve, reject) => {
            try {
                this.form = document.getElementById("escolaForm");
                let docStackPromise = super.domStack();
                this.saveBtn = document.getElementById("btn-escola-save");
                this.cancelBtn = document.getElementById("btn-escola-cancel");
                this.newPasswordBtn = document.getElementById("escola-new-password-btn");
                this.newPassword = document.getElementById("escola-new-password");
                this.newPassword2 = document.getElementById("escola-new-password-2");
                this.updateBtn = document.getElementById("btn-escola-update");
                this.dangerAlertMsg = document.getElementById("escolaDangerAlertMsg");
                this.dangerAlertContainer = document.getElementById("escolaDangerAlertContainer");
                this.dropdown = new Dropdown(document.getElementById("btn-escola-new-password"), {
                    autoClose: false,
                });
                await docStackPromise;
                resolve();
            }
            catch (e) {
                reject(e);
            }
        });
    }
    /**
     * Set the event listen click in the save button and set has enabled
     */
    setSaveEventListener() {
    }
    /**
     * Open form
     */
    async openForm() {
        return new Promise(async (resolve, reject) => {
            var _a, _b, _c, _d, _e;
            try {
                Util.loaderSpinnerContainerVisible(true).then(null);
                let html = await this.renderFormHtml();
                let tabContentId = `tab-${Escola.ControllerName}`;
                let tabContainer = document.getElementById(tabContentId);
                if (tabContainer === null) {
                    console.log(`Tab content id ${tabContentId} not found`);
                    return resolve();
                }
                (_a = tabContainer === null || tabContainer === void 0 ? void 0 : tabContainer.firstElementChild) === null || _a === void 0 ? void 0 : _a.remove();
                tabContainer === null || tabContainer === void 0 ? void 0 : tabContainer.insertAdjacentHTML("beforeend", html);
                await this.domStack();
                (_b = this.updateBtn) === null || _b === void 0 ? void 0 : _b.addEventListener("click", () => {
                    this.changeFormState(false).then(null).catch(null);
                });
                (_c = this.cancelBtn) === null || _c === void 0 ? void 0 : _c.addEventListener("click", async () => {
                    this.changeFormState(true).then(null).catch(null);
                });
                (_d = this.saveBtn) === null || _d === void 0 ? void 0 : _d.addEventListener("click", () => {
                    this.updateSchool().then(null).catch(null);
                });
                let escola = await this.loadSchool();
                await this.fillForm(escola !== null && escola !== void 0 ? escola : {});
                (_e = this.newPasswordBtn) === null || _e === void 0 ? void 0 : _e.addEventListener("click", () => {
                    this.sendNewPassword().then(null).catch(null);
                });
                return resolve();
            }
            catch (e) {
                return reject(e);
            }
            finally {
                Util.loaderSpinnerContainerVisible(false).then(null);
            }
        });
    }
    loadSchool() {
        return new Promise(async (resolve, _) => {
            try {
                let url = `${Escola.baseUrl}&action=getEscola`;
                let fetchResponse = await this.fetchService.get(url);
                if (fetchResponse.status !== 200) {
                    await this.showAlert(await fetchResponse.text());
                    return resolve(null);
                }
                let escola = await fetchResponse.json();
                return resolve(escola);
            }
            catch (e) {
                await this.showAlert(e.toString());
                return resolve(null);
            }
        });
    }
    updateSchool() {
        return new Promise(async (resolve, _) => {
            var _a, _b;
            try {
                await this.clearErrors();
                await Util.loaderSpinnerContainerVisible(true);
                let formData = new FormData(this.form);
                formData.set("alvara", this.formElements.get("alvara").value);
                let fetchResponse = await this.fetchService.post("/", formData);
                if (fetchResponse.status !== 200) {
                    await this.showAlert(await fetchResponse.text());
                    return resolve();
                }
                let response = await fetchResponse.json();
                if (response.error) {
                    await this.setFieldErrors((_a = response.error_fields) !== null && _a !== void 0 ? _a : []);
                    await this.showAlert((_b = response.msg) !== null && _b !== void 0 ? _b : "Erro ao actualizar os dados");
                    return resolve();
                }
                await this.changeFormState(true);
                let escola = await this.loadSchool();
                await this.fillForm(escola !== null && escola !== void 0 ? escola : {});
                return resolve();
            }
            catch (e) {
                await this.showAlert(e.toString());
            }
            finally {
                await Util.loaderSpinnerContainerVisible(false);
            }
        });
    }
    changeFormState(disable) {
        return new Promise((resolve, _) => {
            let elements = [
                "morada",
                "localidade",
                "cPostal1",
                "cPostal2",
                "telefone1",
                "telefone2",
                "telefone3",
                "fax",
                "email",
                "web"
            ];
            this.saveBtn.disabled = disable;
            this.formElements.forEach((e, k, _) => {
                if (elements.includes(k))
                    e.disabled = disable;
            });
            return resolve();
        });
    }
    /**
     * Sen a new password to the user
     * @private
     */
    sendNewPassword() {
        return new Promise(async (resolve, _) => {
            var _a, _b, _c;
            await Util.loaderSpinnerContainerVisible(false);
            try {
                this.newPassword.classList.remove("has-error");
                this.newPassword2.classList.remove("has-error");
                if (Util.isEmpty((_a = this.newPassword) === null || _a === void 0 ? void 0 : _a.value)) {
                    this.newPassword.classList.add("has-error");
                    this.dropdown.show();
                    return;
                }
                if (Util.isEmpty((_b = this.newPassword2) === null || _b === void 0 ? void 0 : _b.value)) {
                    this.newPassword2.classList.add("has-error");
                    this.dropdown.show();
                    return;
                }
                if (this.newPassword.value !== this.newPassword2.value) {
                    this.newPassword2.classList.add("has-error");
                    this.dropdown.show();
                    return;
                }
                let formData = new FormData();
                formData.set("newPassword", this.newPassword.value);
                formData.set("newPassword2", this.newPassword2.value);
                let fetchResponse = await this.fetchService.post(`${Escola.baseUrl}&action=renewPassword`, formData);
                if (fetchResponse.status !== 200) {
                    await this.showAlert(await fetchResponse.text());
                    this.dropdown.show();
                    return resolve();
                }
                let response = await fetchResponse.json();
                if (response.error) {
                    await this.showAlert((_c = response.msg) !== null && _c !== void 0 ? _c : "Erro ao alterar a palavra-chave");
                    this.dropdown.show();
                    return resolve();
                }
                await Util.showToast("Palava-chave alterada com sucesso");
                this.newPassword.value = "";
                this.newPassword2.value = "";
                this.dropdown.hide();
                return resolve();
            }
            catch (e) {
                await this.showAlert(e.toString());
            }
            finally {
                await Util.loaderSpinnerContainerVisible(false);
            }
        });
    }
};
EscolaForm = __decorate([
    injectable(),
    __metadata("design:paramtypes", [FetchService])
], EscolaForm);
export { EscolaForm };
//# sourceMappingURL=EscolaForm.js.map