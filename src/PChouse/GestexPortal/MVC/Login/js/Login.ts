import {container} from "tsyringe";
import {injectable} from "tsyringe";
import {LoginForm} from "./LoginForm";
import {Response} from "../../../js/Response";
import {Modal} from "bootstrap";
import {FetchService} from "../../../js/FetchService";

@injectable()
export class Login {

    private controller = "Login";

    static logoutConfirmDialog: string | null = null;

    constructor(protected fetchService: FetchService) {
    }

    async authenticate() {
        try {

            const loginForm = container.resolve(LoginForm);

            if(loginForm.getForm() === null) return;

            let form = loginForm.getForm() as HTMLFormElement

            let isValid = await loginForm.validate();

            if (!isValid) return;

            await loginForm.clearErrors();

            let fetchResponse = await this.fetchService.post("/", new FormData(form));

            if(fetchResponse.redirected){
                window.document.location = fetchResponse.url;
                return;
            }

            let response: Response = await fetchResponse.json();

            if (response.error) {

                let setFieldErrors = loginForm.setFieldErrors(response.error_fields ?? []);

                let body = document.getElementById("modalBody");
                let btnFormOk = document.getElementById("btn-form-ok");

                if (body !== null) body.innerText = response.msg ?? "";

                let modalDom = document.getElementById("confirmDialog") as Element;
                modalDom.addEventListener("shown.bs.modal", function (event){
                   btnFormOk?.focus()
                });

                modalDom.addEventListener("hidden.bs.modal", function (event){
                    (document.getElementsByClassName("is-invalid").item(0) as HTMLElement |null)?.focus();
                });

                (new Modal(modalDom, {
                    backdrop: false
                })).show();

                Promise.all([setFieldErrors])
                    .catch((e) => {
                        console.log(`Exception while set filds with error in form ${form.id} with error ${e}`)
                    })
                    .then(() => {
                        console.log(`Was set filds with error in form ${form.id}`)
                    });

                return;
            }

            this.afterAuthenticate();

        } catch (e) {
            console.log(e);
        }
    }

    async logout() {
        let myWindow = window.location;
        window.document.location = `${myWindow.protocol}//${myWindow.hostname}${myWindow.pathname}?logout=1`;
    }

    afterAuthenticate(): void {
        let myWindow = window.location;
        window.document.location = `${myWindow.protocol}//${myWindow.hostname}${myWindow.pathname}`;
    }

}
