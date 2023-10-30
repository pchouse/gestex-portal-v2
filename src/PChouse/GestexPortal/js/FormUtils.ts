import {Util} from "./Util";
import {Modal} from "bootstrap";
import {Response} from "./Response";
import {IConfirmDeleteDialog} from "./IConfirmDeleteDialog";
import {FetchService} from "./FetchService";
import {TabulatorFull as Tabulator} from 'tabulator-tables';

import {Session} from "./Session";

export abstract class FormUtils {

    static readonly formHtml: Map<string, string> = new Map();

    public formElements: Map<string, HTMLElement> = new Map();

    public saveBtn: HTMLButtonElement | null = null;

    public cancelBtn: HTMLButtonElement | null = null;

    public updateBtn: HTMLButtonElement | null = null;

    protected form: HTMLFormElement | null = null;

    protected static cancelFormModalHtml: string | null = null;

    protected static confirmDeleteModalHtml: string | null = null;

    protected dangerAlertMsg: HTMLElement | null = null;

    protected dangerAlertContainer: HTMLElement | null = null;

    protected warningAlertMsg: HTMLElement | null = null;

    protected warningAlertContainer: HTMLElement | null = null;

    protected table: Tabulator | null = null

    protected modalForm: Modal | null = null

    protected constructor(protected baseUrl: string, protected fetchService: FetchService) {
        this.table = this.getTable();
    }

    public onFormChange(): void {

        let abortController = new AbortController();

        this.form?.addEventListener('change', async () => {
                this.setSaveEventListener();
                abortController.abort();
            },
            {signal: abortController.signal}
        );
    }

    protected abstract setSaveEventListener(): void;

    domStack(): Promise<void> {
        let self = this;
        return new Promise<void>((resolve, reject) => {
            try {
                this.formElements.clear();
                // @ts-ignore
                for (let element: HTMLElement of self.form) {
                    if (!element.hasAttribute("name")) continue
                    let name = element.getAttribute("name");
                    if (Util.isEmpty(name)) continue;
                    this.formElements.set(name, element);
                }

                resolve();

            } catch (e) {
                reject(e);
            }
        });
    }

    public getForm(): HTMLFormElement | null {
        return this.form;
    }

    /**
     * Set the Dom filed has error
     * @param fields
     */
    setFieldErrors(fields: Array<string>): Promise<void> {
        return new Promise<void>((resolve, reject) => {
            try {
                for (let field of fields) {
                    this.form?.querySelector(`[name='${field}']`)?.classList.add("is-invalid");
                }
                resolve();
            } catch (e) {
                reject(e);
            }
        });
    }

    validate(): Promise<boolean> {
        return new Promise<boolean>((resolve, reject) => {
            try {
                if (!this.form?.checkValidity()) {
                    this.form?.classList.add('was-validated');
                    resolve(false);
                }
                resolve(true);
            } catch (e) {
                reject(e);
            }
        });
    }

    clearErrors(): Promise<void> {
        return new Promise<void>(async (resolve, reject) => {
            try {

                let clearIsInvalid = new Promise<void>((resolve, _) => {
                    this.form?.classList.remove('was-validated');
                    this.form?.querySelectorAll(".is-invalid").forEach(
                        element => element.classList.remove("is-invalid")
                    );
                    resolve();
                });

                await Promise.all([this.hideAlert(), this.hideWarning(), clearIsInvalid]);

                resolve();
            } catch (e) {
                reject(e);
            }
        });
    }

    public fillForm(values: object): Promise<void> {

        return new Promise<void>((resolve, reject) => {
            try {

                let action = this.formElements.get("action");

                if ((typeof action) !== "undefined" && action !== null) {
                    (action as HTMLInputElement).value = "update";
                }

                for (let name in values) {
                    if (!this.formElements.has(name)) continue;

                    // @ts-ignore
                    let value: any = values[name];

                    if (typeof value === 'object') continue;

                    if (value === null) {
                        value = "";
                    }

                    let element = <HTMLElement>this.formElements.get(name);
                    let tagName = element.tagName.toLowerCase();

                    if (tagName === "textarea") {
                        (element as HTMLTextAreaElement).value = value;
                        continue;
                    }

                    if (tagName === "input") {
                        let type = element.getAttribute("type")?.toLowerCase();

                        if (Util.isEmpty(type)) continue;

                        if (["text", "email", "hidden"].includes(type as string)) {

                            let dataType = element.getAttribute("data-type") ?? "";

                            if (value === "" || dataType === "") {
                                (element as HTMLInputElement).value = value;
                                continue;
                            }

                            switch (dataType) {
                                case 'decimal':
                                    let precision = Number(element.getAttribute("data-precision") ?? 0);
                                    if (isNaN(precision)) precision = 0;
                                    (element as HTMLInputElement).value = Session.formatNumber(value, precision);
                                    break;
                                case 'date':
                                case 'datetime':
                                case 'time':
                                    (element as HTMLInputElement).value = Session.formartDate(value);
                                    break
                                default:
                                    (element as HTMLInputElement).value = value;
                            }
                            continue;
                        }

                        if (type === "checkbox") {
                            (element as HTMLInputElement).checked = value === true || value === 1 || value === "1";
                            continue;
                        }

                        (element as HTMLInputElement).value = value;
                        continue;
                    }

                    if (tagName === "select") {
                        let option = element.querySelector(`option[value='${value}']`);
                        if (option === null) {
                            (element.querySelector(`option[value='']`) as HTMLOptionElement).selected = true;
                            continue;
                        }
                        (option as HTMLOptionElement).selected = true;
                    }
                }

                resolve();
            } catch (e) {
                reject(e);
            }
        });
    }

    protected async renderModalFormHtml(): Promise<void> {

        return new Promise<void>(async (resolve, reject) => {

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

            document.body.insertAdjacentHTML("beforeend", html as string);

            resolve();
        });
    }

    public async renderFormHtml(): Promise<string> {

        return new Promise<string>(async (resolve, reject) => {

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

    protected async renderCancelFormDialog($formDialog: HTMLElement): Promise<void> {

        return new Promise(async (resolve, reject) => {

            try {
                if (FormUtils.cancelFormModalHtml == null) {

                    let responseFetch = await fetch(`${this.baseUrl}&action=renderCancelFormModal`);

                    if (!responseFetch.ok) {
                        reject("Erro ao obter o html do modal de cancelamento de formulário");
                        return;
                    }

                    FormUtils.cancelFormModalHtml = await responseFetch.text()
                }

                if (FormUtils.cancelFormModalHtml === null) reject("Erro ao obter o html de fomulário");

                document.body.insertAdjacentHTML("beforeend", FormUtils.cancelFormModalHtml as string);

                let $dialog = document.getElementById("confirmDialog");
                let $confirmBtn = document.getElementById("confirmDialogYes");
                let $negationBtn = document.getElementById("confirmDialogNo");

                if ($dialog === null) {
                    reject("Erro ao obter o dialog Dom dde cancelamento do formulário");
                    return;
                }

                let modal = new Modal($dialog as HTMLElement, {
                    backdrop: false
                });

                modal.show();

                $confirmBtn?.addEventListener("click", () => {
                    $dialog?.remove();
                    $formDialog.remove();
                });

                $negationBtn?.addEventListener("click", () => {
                    $dialog?.remove();
                });

                resolve();
            } catch (e) {
                reject(e);
            }
        });
    }

    public async renderConfirmDeleteDialog(message: string): Promise<IConfirmDeleteDialog> {

        return new Promise(async (resolve, reject) => {

            try {
                if (FormUtils.confirmDeleteModalHtml == null) {
                    let responseFetch = await fetch(`${this.baseUrl}&action=renderConfirmDeleteModal`);
                    if (!responseFetch.ok) {
                        reject("Erro ao obter o html do modal de eliminação de registo");
                        return;
                    }
                    FormUtils.confirmDeleteModalHtml = await responseFetch.text()
                }

                if (FormUtils.confirmDeleteModalHtml === null) reject("Erro ao obter o html de fomulário");

                document.body.insertAdjacentHTML("beforeend", FormUtils.confirmDeleteModalHtml as string);

                let $dialog = document.getElementById("confirmDialog");
                let $dialogBody = document.getElementById("confirmDialog-body");
                let $confirmBtn = document.getElementById("confirmDialogYes");
                let $negationBtn = document.getElementById("confirmDialogNo");

                if ($dialog === null) {
                    reject("Erro ao obter o dialog DOM de cancelamento do formulário");
                    return;
                }

                if ($dialogBody !== null) $dialogBody.innerHTML = message;

                let modal = new Modal($dialog as HTMLElement, {
                    backdrop: false
                });

                modal.show();

                $negationBtn?.addEventListener("click", () => {
                    $dialog?.remove();
                });

                resolve(
                    {
                        modalHtmlElement: $dialog,
                        confirmButton   : $confirmBtn as HTMLButtonElement | null
                    }
                );

            } catch (e) {
                reject(e);
            }
        });
    }

    /**
     * Do a simple request
     * @param url
     * @protected
     */
    public request(url: string): Promise<void> {

        return new Promise<void>(async (resolve, reject) => {

            try {

                let requestFetch = await fetch(url);

                if (!requestFetch.ok) {
                    reject(await requestFetch.text());
                    return;
                }

                let response: Response = await requestFetch.json();

                if (response.error) {
                    reject(
                        Util.isEmpty(response.msg) ? "Erro de comunicação com o servido" : response.msg
                    )
                    return;
                }

                resolve();
            } catch (e) {
                reject(e);
            }
        });
    }

    /**
     * Save
     * @private
     */
    protected save(): Promise<void> {
        return new Promise<void>(async (resolve, reject) => {
            try {

                await this.clearErrors();

                if (!await this.validate()) {
                    reject("Por favor corrija os erros");
                    return;
                }

                let fetchResponse = await this.fetchService.post(
                    "/", new FormData(this.form as HTMLFormElement)
                );

                if (!fetchResponse.ok) {
                    reject(`Erro de comunicação com o servidor: ${await fetchResponse.text()}`);
                    return;
                }

                let response: Response = await fetchResponse.json();

                if (response.error) {
                    await this.setFieldErrors(response.error_fields ?? []);
                    reject(response.msg);
                    return;
                }

                resolve();
            } catch (e) {
                reject(e);
            }
        });
    }

    protected _getTable(id: string): Tabulator | null {
        let find = Tabulator.findTable(`#${id}`);
        let table = find ? find.at(0) : undefined;
        return typeof table !== "undefined" ? table : null;
    }

    protected abstract getTable(): Tabulator | null;

    showAlert(msg: string): Promise<void> {
        return new Promise<void>((resolve, _) => {
            if (this.dangerAlertMsg !== null) {
                this.dangerAlertMsg.innerText = msg
                this.dangerAlertContainer?.classList?.remove("visually-hidden");
            }
            setTimeout(() => this.hideAlert(),2999);
            return resolve();
        });
    }

    hideAlert(): Promise<void> {
        return new Promise<void>((resolve, _) => {
            if (this.dangerAlertMsg !== null) {
                this.dangerAlertMsg.innerText = ""
                if (this.dangerAlertContainer?.classList?.contains("visually-hidden") === false) {
                    this.dangerAlertContainer?.classList?.add("visually-hidden");
                }
            }
            return resolve();
        });
    }

    showWarning(msg: string): Promise<void> {
        return new Promise<void>((resolve, _) => {
            if (this.warningAlertMsg !== null) {
                this.warningAlertMsg.innerText = msg
                this.warningAlertContainer?.classList?.remove("visually-hidden");
            }
            setTimeout(() => this.hideWarning(),2999);
            return resolve();
        });
    }

    hideWarning(): Promise<void> {
        return new Promise<void>((resolve, _) => {
            if (this.warningAlertMsg !== null) {
                this.warningAlertMsg.innerText = ""
                if (this.warningAlertContainer?.classList?.contains("visually-hidden") === false) {
                    this.warningAlertContainer?.classList?.add("visually-hidden");
                }
            }
            resolve();
        });
    }

}
