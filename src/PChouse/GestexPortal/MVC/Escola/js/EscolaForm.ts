import {injectable} from "tsyringe";
import {FormUtils} from "../../../js/FormUtils";
import {Util} from "../../../js/Util";
import {TabulatorFull as Tabulator} from 'tabulator-tables';
import {Escola} from "./Escola";
import {FetchService} from "../../../js/FetchService";
import {TEscola} from "./TEscola";
import {Response} from "../../../js/Response";
import {Dropdown} from "bootstrap";

@injectable()
export class EscolaForm extends FormUtils {

    public newPasswordBtn: HTMLButtonElement | null = null;

    public newPassword: HTMLInputElement | null = null;
    public newPassword2: HTMLInputElement |null =null;

    public dropdown: Dropdown| null = null;

    constructor(protected fetchService: FetchService) {
        super(Escola.baseUrl, fetchService);
    }

    getTable(): Tabulator | null {
        return this._getTable(Escola.tableId);
    }

    domStack(): Promise<void> {
        return new Promise<void>(async (resolve, reject) => {
            try {
                this.form = document.getElementById("escolaForm") as HTMLFormElement | null;
                let docStackPromise = super.domStack();
                this.saveBtn = document.getElementById("btn-escola-save") as HTMLButtonElement | null;
                this.cancelBtn = document.getElementById("btn-escola-cancel") as HTMLButtonElement | null;
                this.newPasswordBtn = document.getElementById("escola-new-password-btn") as HTMLButtonElement | null;
                this.newPassword = document.getElementById("escola-new-password") as HTMLInputElement | null;
                this.newPassword2 = document.getElementById("escola-new-password-2") as HTMLInputElement | null;
                this.updateBtn = document.getElementById("btn-escola-update") as HTMLButtonElement | null
                this.dangerAlertMsg = document.getElementById("escolaDangerAlertMsg");
                this.dangerAlertContainer = document.getElementById("escolaDangerAlertContainer");

                this.dropdown = new Dropdown(
                   document.getElementById("btn-escola-new-password")!,
                    {
                        autoClose: false,
                    }
                );

                await docStackPromise;
                resolve();
            } catch (e) {
                reject(e);
            }
        });
    }

    /**
     * Set the event listen click in the save button and set has enabled
     */
    public setSaveEventListener(): void {

    }

    /**
     * Open form
     */
    async openForm(): Promise<void> {

        return new Promise<void>(async (resolve, reject) => {

            try {

                Util.loaderSpinnerContainerVisible(true).then(null);

                let html = await this.renderFormHtml();

                let tabContentId = `tab-${Escola.ControllerName}`;
                let tabContainer = document.getElementById(tabContentId);
                if (tabContainer === null) {
                    console.log(`Tab content id ${tabContentId} not found`);
                    return resolve();
                }

                tabContainer?.firstElementChild?.remove();
                tabContainer?.insertAdjacentHTML("beforeend", html as string);

                await this.domStack();

                this.updateBtn?.addEventListener("click", () => {
                    this.changeFormState(false).then(null).catch(null);
                });

                this.cancelBtn?.addEventListener("click", async () => {
                    this.changeFormState(true).then(null).catch(null);
                });

                this.saveBtn?.addEventListener("click", ()=>{
                   this.updateSchool().then(null).catch(null);
                });

                let escola = await this.loadSchool();

                await this.fillForm(escola ?? {});

                this.newPasswordBtn?.addEventListener("click", ()=>{
                    this.sendNewPassword().then(null).catch(null);
                });

                return resolve();
            } catch (e) {
                return reject(e);
            } finally {
                Util.loaderSpinnerContainerVisible(false).then(null);
            }
        });
    }

    loadSchool(): Promise<TEscola | null> {
        return new Promise(async (resolve, _) => {

            try {
                let url = `${Escola.baseUrl}&action=getEscola`;

                let fetchResponse = await this.fetchService.get(url);

                if (fetchResponse.status !== 200) {
                    await this.showAlert(await fetchResponse.text());
                    return resolve(null)
                }

                let escola = await fetchResponse.json() as TEscola;

                return resolve(escola);

            } catch (e: any) {
                await this.showAlert(e.toString());
                return resolve(null)
            }
        });
    }

    updateSchool(): Promise<void>{
        return new Promise<void>(async (resolve, _)=>{
            try {
                await this.clearErrors();
                await Util.loaderSpinnerContainerVisible(true);
                let formData= new FormData(this.form!);

                formData.set(
                    "alvara",
                    (this.formElements.get("alvara") as HTMLInputElement).value
                );

                let fetchResponse = await this.fetchService.post(
                    "/", formData
                );

                if(fetchResponse.status !== 200){
                    await this.showAlert(await fetchResponse.text());
                    return resolve();
                }

                let response: Response = await fetchResponse.json();

                if(response.error){
                    await this.setFieldErrors(response.error_fields ?? []);
                    await this.showAlert(response.msg ?? "Erro ao actualizar os dados");
                    return resolve();
                }

                await this.changeFormState(true);
                let escola = await this.loadSchool();
                await this.fillForm(escola ?? {});
                return resolve();

            }catch (e: any) {
                await this.showAlert(e.toString())
            }finally {
                await Util.loaderSpinnerContainerVisible(false);
            }
        });
    }

    changeFormState(disable: boolean): Promise<void>{
        return new Promise<void>((resolve, _)=>{
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

            this.saveBtn!.disabled = disable;

            this.formElements.forEach(
                (e, k, _) => {
                if (elements.includes(k)) (e as HTMLInputElement).disabled = disable;
            });

            return resolve();
        });
    }

    /**
     * Sen a new password to the user
     * @private
     */
    private sendNewPassword(): Promise<void> {
        return new Promise<void>(async (resolve, _)=> {

            await Util.loaderSpinnerContainerVisible(false);

            try {
                this.newPassword!.classList.remove("has-error");
                this.newPassword2!.classList.remove("has-error");

                if (Util.isEmpty(this.newPassword?.value)) {
                    this.newPassword!.classList.add("has-error");
                    this.dropdown!.show();
                    return
                }

                if (Util.isEmpty(this.newPassword2?.value)) {
                    this.newPassword2!.classList.add("has-error");
                    this.dropdown!.show();
                    return
                }

                if (this.newPassword!.value !== this.newPassword2!.value) {
                    this.newPassword2!.classList.add("has-error");
                    this.dropdown!.show();
                    return
                }

                let formData = new FormData();
                formData.set("newPassword", this.newPassword!.value);
                formData.set("newPassword2", this.newPassword2!.value);

                let fetchResponse = await this.fetchService.post(
                    `${Escola.baseUrl}&action=renewPassword`, formData
                );

                if (fetchResponse.status !== 200) {
                    await this.showAlert(
                        await fetchResponse.text()
                    );
                    this.dropdown!.show();
                    return resolve();
                }

                let response: Response = await fetchResponse.json();

                if (response.error) {
                    await this.showAlert(response.msg ?? "Erro ao alterar a palavra-chave");
                    this.dropdown!.show();
                    return resolve();
                }

                await Util.showToast("Palava-chave alterada com sucesso");

                this.newPassword!.value = "";
                this.newPassword2!.value = "";
                this.dropdown!.hide();
                return resolve();
            }catch (e: any) {
                await this.showAlert(e.toString());
            }finally {
                await Util.loaderSpinnerContainerVisible(false);
            }
        });
    }
}
