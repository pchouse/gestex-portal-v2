import { FormUtils } from "../../../js/FormUtils";
import { TabulatorFull as Tabulator } from 'tabulator-tables';
import { FetchService } from "../../../js/FetchService";
import { TEscola } from "./TEscola";
import { Dropdown } from "bootstrap";
export declare class EscolaForm extends FormUtils {
    protected fetchService: FetchService;
    newPasswordBtn: HTMLButtonElement | null;
    newPassword: HTMLInputElement | null;
    newPassword2: HTMLInputElement | null;
    dropdown: Dropdown | null;
    constructor(fetchService: FetchService);
    getTable(): Tabulator | null;
    domStack(): Promise<void>;
    /**
     * Set the event listen click in the save button and set has enabled
     */
    setSaveEventListener(): void;
    /**
     * Open form
     */
    openForm(): Promise<void>;
    loadSchool(): Promise<TEscola | null>;
    updateSchool(): Promise<void>;
    changeFormState(disable: boolean): Promise<void>;
    /**
     * Sen a new password to the user
     * @private
     */
    private sendNewPassword;
}
