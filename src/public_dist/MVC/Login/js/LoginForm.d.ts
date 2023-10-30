import { FormUtils } from "../../../js/FormUtils";
import { FetchService } from "../../../js/FetchService";
import { Tabulator as Tabulator } from 'tabulator-tables';
export declare class LoginForm extends FormUtils {
    protected fetchService: FetchService;
    username: HTMLInputElement;
    password: HTMLInputElement;
    newPassword: HTMLInputElement | null;
    confirmPassword: HTMLInputElement | null;
    protected passwordEye: HTMLSpanElement | null;
    constructor(fetchService: FetchService);
    onFormChange(): void;
    setLoadFocus(): void;
    protected getTable(): Tabulator | null;
    protected setSaveEventListener(): void;
}
