import {injectable, singleton} from "tsyringe";
import {FormUtils} from "../../../js/FormUtils";
import {FetchService} from "../../../js/FetchService";
import {Tabulator as Tabulator} from 'tabulator-tables';

@injectable()
@singleton()
export class LoginForm extends FormUtils {

    username: HTMLInputElement;

    password: HTMLInputElement;

    newPassword: HTMLInputElement | null;

    confirmPassword: HTMLInputElement | null;

    protected passwordEye: HTMLSpanElement | null = null;

    constructor(protected fetchService: FetchService) {
        super("/", fetchService);
        this.form = document.getElementById("loginForm") as HTMLFormElement;
        this.username = document.getElementById("username") as HTMLInputElement;
        this.password = document.getElementById("password") as HTMLInputElement;
        this.newPassword = document.getElementById("new_password") as HTMLInputElement | null;
        this.confirmPassword = document.getElementById("confirm_password") as HTMLInputElement | null;
        this.passwordEye = document.getElementById("login-eye") as HTMLSpanElement | null;

        this.passwordEye?.addEventListener("click", () => {
            (this.password as HTMLInputElement).type = (this.password as HTMLInputElement).type === "password" ?
                "text" : "password";
        });
    }

    onFormChange(): void {
    }

    setLoadFocus(): void {
        if (this.newPassword !== null) {
            this.password.focus();
            return;
        }

        this.username.focus();

    }

    protected getTable(): Tabulator | null {
        return null;
    }

    protected setSaveEventListener(): void {
    }

}
