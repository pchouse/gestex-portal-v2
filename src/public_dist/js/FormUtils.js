import { Util } from "./Util";
import { Modal } from "bootstrap";
import { TabulatorFull as Tabulator } from 'tabulator-tables';
import { Session } from "./Session";
export class FormUtils {
    constructor(baseUrl, fetchService) {
        this.baseUrl = baseUrl;
        this.fetchService = fetchService;
        this.formElements = new Map();
        this.saveBtn = null;
        this.cancelBtn = null;
        this.updateBtn = null;
        this.form = null;
        this.dangerAlertMsg = null;
        this.dangerAlertContainer = null;
        this.warningAlertMsg = null;
        this.warningAlertContainer = null;
        this.table = null;
        this.modalForm = null;
        this.table = this.getTable();
    }
    onFormChange() {
        var _a;
        let abortController = new AbortController();
        (_a = this.form) === null || _a === void 0 ? void 0 : _a.addEventListener('change', async () => {
            this.setSaveEventListener();
            abortController.abort();
        }, { signal: abortController.signal });
    }
    domStack() {
        let self = this;
        return new Promise((resolve, reject) => {
            try {
                this.formElements.clear();
                // @ts-ignore
                for (let element of self.form) {
                    if (!element.hasAttribute("name"))
                        continue;
                    let name = element.getAttribute("name");
                    if (Util.isEmpty(name))
                        continue;
                    this.formElements.set(name, element);
                }
                resolve();
            }
            catch (e) {
                reject(e);
            }
        });
    }
    getForm() {
        return this.form;
    }
    /**
     * Set the Dom filed has error
     * @param fields
     */
    setFieldErrors(fields) {
        return new Promise((resolve, reject) => {
            var _a, _b;
            try {
                for (let field of fields) {
                    (_b = (_a = this.form) === null || _a === void 0 ? void 0 : _a.querySelector(`[name='${field}']`)) === null || _b === void 0 ? void 0 : _b.classList.add("is-invalid");
                }
                resolve();
            }
            catch (e) {
                reject(e);
            }
        });
    }
    validate() {
        return new Promise((resolve, reject) => {
            var _a, _b;
            try {
                if (!((_a = this.form) === null || _a === void 0 ? void 0 : _a.checkValidity())) {
                    (_b = this.form) === null || _b === void 0 ? void 0 : _b.classList.add('was-validated');
                    resolve(false);
                }
                resolve(true);
            }
            catch (e) {
                reject(e);
            }
        });
    }
    clearErrors() {
        return new Promise(async (resolve, reject) => {
            try {
                let clearIsInvalid = new Promise((resolve, _) => {
                    var _a, _b;
                    (_a = this.form) === null || _a === void 0 ? void 0 : _a.classList.remove('was-validated');
                    (_b = this.form) === null || _b === void 0 ? void 0 : _b.querySelectorAll(".is-invalid").forEach(element => element.classList.remove("is-invalid"));
                    resolve();
                });
                await Promise.all([this.hideAlert(), this.hideWarning(), clearIsInvalid]);
                resolve();
            }
            catch (e) {
                reject(e);
            }
        });
    }
    fillForm(values) {
        return new Promise((resolve, reject) => {
            var _a, _b, _c;
            try {
                let action = this.formElements.get("action");
                if ((typeof action) !== "undefined" && action !== null) {
                    action.value = "update";
                }
                for (let name in values) {
                    if (!this.formElements.has(name))
                        continue;
                    // @ts-ignore
                    let value = values[name];
                    if (typeof value === 'object')
                        continue;
                    if (value === null) {
                        value = "";
                    }
                    let element = this.formElements.get(name);
                    let tagName = element.tagName.toLowerCase();
                    if (tagName === "textarea") {
                        element.value = value;
                        continue;
                    }
                    if (tagName === "input") {
                        let type = (_a = element.getAttribute("type")) === null || _a === void 0 ? void 0 : _a.toLowerCase();
                        if (Util.isEmpty(type))
                            continue;
                        if (["text", "email", "hidden"].includes(type)) {
                            let dataType = (_b = element.getAttribute("data-type")) !== null && _b !== void 0 ? _b : "";
                            if (value === "" || dataType === "") {
                                element.value = value;
                                continue;
                            }
                            switch (dataType) {
                                case 'decimal':
                                    let precision = Number((_c = element.getAttribute("data-precision")) !== null && _c !== void 0 ? _c : 0);
                                    if (isNaN(precision))
                                        precision = 0;
                                    element.value = Session.formatNumber(value, precision);
                                    break;
                                case 'date':
                                case 'datetime':
                                case 'time':
                                    element.value = Session.formartDate(value);
                                    break;
                                default:
                                    element.value = value;
                            }
                            continue;
                        }
                        if (type === "checkbox") {
                            element.checked = value === true || value === 1 || value === "1";
                            continue;
                        }
                        element.value = value;
                        continue;
                    }
                    if (tagName === "select") {
                        let option = element.querySelector(`option[value='${value}']`);
                        if (option === null) {
                            element.querySelector(`option[value='']`).selected = true;
                            continue;
                        }
                        option.selected = true;
                    }
                }
                resolve();
            }
            catch (e) {
                reject(e);
            }
        });
    }
    async renderModalFormHtml() {
        return new Promise(async (resolve, reject) => {
            let url = `${this.baseUrl}&action=renderModalForm`;
            if (!FormUtils.formHtml.has(url)) {
                let responseFetch = await this.fetchService.get(url);
                if (!responseFetch.ok) {
                    reject("Erro ao obter o html de fomulário");
                    return;
                }
                FormUtils.formHtml.set(url, await responseFetch.text());
            }
            let html = FormUtils.formHtml.get(url);
            if (Util.isEmpty(html)) {
                reject("Erro ao obter o html de fomulário");
                return;
            }
            document.body.insertAdjacentHTML("beforeend", html);
            resolve();
        });
    }
    async renderFormHtml() {
        return new Promise(async (resolve, reject) => {
            let url = `${this.baseUrl}&action=renderForm`;
            let responseFetch = await this.fetchService.get(url);
            if (!responseFetch.ok) {
                return reject("Erro ao obter o html de fomulário");
            }
            let html = await responseFetch.text();
            if (Util.isEmpty(html)) {
                return reject("Erro ao obter o html de fomulário");
            }
            return resolve(html);
        });
    }
    async renderCancelFormDialog($formDialog) {
        return new Promise(async (resolve, reject) => {
            try {
                if (FormUtils.cancelFormModalHtml == null) {
                    let responseFetch = await fetch(`${this.baseUrl}&action=renderCancelFormModal`);
                    if (!responseFetch.ok) {
                        reject("Erro ao obter o html do modal de cancelamento de formulário");
                        return;
                    }
                    FormUtils.cancelFormModalHtml = await responseFetch.text();
                }
                if (FormUtils.cancelFormModalHtml === null)
                    reject("Erro ao obter o html de fomulário");
                document.body.insertAdjacentHTML("beforeend", FormUtils.cancelFormModalHtml);
                let $dialog = document.getElementById("confirmDialog");
                let $confirmBtn = document.getElementById("confirmDialogYes");
                let $negationBtn = document.getElementById("confirmDialogNo");
                if ($dialog === null) {
                    reject("Erro ao obter o dialog Dom dde cancelamento do formulário");
                    return;
                }
                let modal = new Modal($dialog, {
                    backdrop: false
                });
                modal.show();
                $confirmBtn === null || $confirmBtn === void 0 ? void 0 : $confirmBtn.addEventListener("click", () => {
                    $dialog === null || $dialog === void 0 ? void 0 : $dialog.remove();
                    $formDialog.remove();
                });
                $negationBtn === null || $negationBtn === void 0 ? void 0 : $negationBtn.addEventListener("click", () => {
                    $dialog === null || $dialog === void 0 ? void 0 : $dialog.remove();
                });
                resolve();
            }
            catch (e) {
                reject(e);
            }
        });
    }
    async renderConfirmDeleteDialog(message) {
        return new Promise(async (resolve, reject) => {
            try {
                if (FormUtils.confirmDeleteModalHtml == null) {
                    let responseFetch = await fetch(`${this.baseUrl}&action=renderConfirmDeleteModal`);
                    if (!responseFetch.ok) {
                        reject("Erro ao obter o html do modal de eliminação de registo");
                        return;
                    }
                    FormUtils.confirmDeleteModalHtml = await responseFetch.text();
                }
                if (FormUtils.confirmDeleteModalHtml === null)
                    reject("Erro ao obter o html de fomulário");
                document.body.insertAdjacentHTML("beforeend", FormUtils.confirmDeleteModalHtml);
                let $dialog = document.getElementById("confirmDialog");
                let $dialogBody = document.getElementById("confirmDialog-body");
                let $confirmBtn = document.getElementById("confirmDialogYes");
                let $negationBtn = document.getElementById("confirmDialogNo");
                if ($dialog === null) {
                    reject("Erro ao obter o dialog DOM de cancelamento do formulário");
                    return;
                }
                if ($dialogBody !== null)
                    $dialogBody.innerHTML = message;
                let modal = new Modal($dialog, {
                    backdrop: false
                });
                modal.show();
                $negationBtn === null || $negationBtn === void 0 ? void 0 : $negationBtn.addEventListener("click", () => {
                    $dialog === null || $dialog === void 0 ? void 0 : $dialog.remove();
                });
                resolve({
                    modalHtmlElement: $dialog,
                    confirmButton: $confirmBtn
                });
            }
            catch (e) {
                reject(e);
            }
        });
    }
    /**
     * Do a simple request
     * @param url
     * @protected
     */
    request(url) {
        return new Promise(async (resolve, reject) => {
            try {
                let requestFetch = await fetch(url);
                if (!requestFetch.ok) {
                    reject(await requestFetch.text());
                    return;
                }
                let response = await requestFetch.json();
                if (response.error) {
                    reject(Util.isEmpty(response.msg) ? "Erro de comunicação com o servido" : response.msg);
                    return;
                }
                resolve();
            }
            catch (e) {
                reject(e);
            }
        });
    }
    /**
     * Save
     * @private
     */
    save() {
        return new Promise(async (resolve, reject) => {
            var _a;
            try {
                await this.clearErrors();
                if (!await this.validate()) {
                    reject("Por favor corrija os erros");
                    return;
                }
                let fetchResponse = await this.fetchService.post("/", new FormData(this.form));
                if (!fetchResponse.ok) {
                    reject(`Erro de comunicação com o servidor: ${await fetchResponse.text()}`);
                    return;
                }
                let response = await fetchResponse.json();
                if (response.error) {
                    await this.setFieldErrors((_a = response.error_fields) !== null && _a !== void 0 ? _a : []);
                    reject(response.msg);
                    return;
                }
                resolve();
            }
            catch (e) {
                reject(e);
            }
        });
    }
    _getTable(id) {
        let find = Tabulator.findTable(`#${id}`);
        let table = find ? find.at(0) : undefined;
        return typeof table !== "undefined" ? table : null;
    }
    showAlert(msg) {
        return new Promise((resolve, _) => {
            var _a, _b;
            if (this.dangerAlertMsg !== null) {
                this.dangerAlertMsg.innerText = msg;
                (_b = (_a = this.dangerAlertContainer) === null || _a === void 0 ? void 0 : _a.classList) === null || _b === void 0 ? void 0 : _b.remove("visually-hidden");
            }
            setTimeout(() => this.hideAlert(), 2999);
            return resolve();
        });
    }
    hideAlert() {
        return new Promise((resolve, _) => {
            var _a, _b, _c, _d;
            if (this.dangerAlertMsg !== null) {
                this.dangerAlertMsg.innerText = "";
                if (((_b = (_a = this.dangerAlertContainer) === null || _a === void 0 ? void 0 : _a.classList) === null || _b === void 0 ? void 0 : _b.contains("visually-hidden")) === false) {
                    (_d = (_c = this.dangerAlertContainer) === null || _c === void 0 ? void 0 : _c.classList) === null || _d === void 0 ? void 0 : _d.add("visually-hidden");
                }
            }
            return resolve();
        });
    }
    showWarning(msg) {
        return new Promise((resolve, _) => {
            var _a, _b;
            if (this.warningAlertMsg !== null) {
                this.warningAlertMsg.innerText = msg;
                (_b = (_a = this.warningAlertContainer) === null || _a === void 0 ? void 0 : _a.classList) === null || _b === void 0 ? void 0 : _b.remove("visually-hidden");
            }
            setTimeout(() => this.hideWarning(), 2999);
            return resolve();
        });
    }
    hideWarning() {
        return new Promise((resolve, _) => {
            var _a, _b, _c, _d;
            if (this.warningAlertMsg !== null) {
                this.warningAlertMsg.innerText = "";
                if (((_b = (_a = this.warningAlertContainer) === null || _a === void 0 ? void 0 : _a.classList) === null || _b === void 0 ? void 0 : _b.contains("visually-hidden")) === false) {
                    (_d = (_c = this.warningAlertContainer) === null || _c === void 0 ? void 0 : _c.classList) === null || _d === void 0 ? void 0 : _d.add("visually-hidden");
                }
            }
            resolve();
        });
    }
}
FormUtils.formHtml = new Map();
FormUtils.cancelFormModalHtml = null;
FormUtils.confirmDeleteModalHtml = null;
//# sourceMappingURL=FormUtils.js.map