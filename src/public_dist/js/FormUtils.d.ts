import { Modal } from "bootstrap";
import { IConfirmDeleteDialog } from "./IConfirmDeleteDialog";
import { FetchService } from "./FetchService";
import { TabulatorFull as Tabulator } from 'tabulator-tables';
export declare abstract class FormUtils {
    protected baseUrl: string;
    protected fetchService: FetchService;
    static readonly formHtml: Map<string, string>;
    formElements: Map<string, HTMLElement>;
    saveBtn: HTMLButtonElement | null;
    cancelBtn: HTMLButtonElement | null;
    updateBtn: HTMLButtonElement | null;
    protected form: HTMLFormElement | null;
    protected static cancelFormModalHtml: string | null;
    protected static confirmDeleteModalHtml: string | null;
    protected dangerAlertMsg: HTMLElement | null;
    protected dangerAlertContainer: HTMLElement | null;
    protected warningAlertMsg: HTMLElement | null;
    protected warningAlertContainer: HTMLElement | null;
    protected table: Tabulator | null;
    protected modalForm: Modal | null;
    protected constructor(baseUrl: string, fetchService: FetchService);
    onFormChange(): void;
    protected abstract setSaveEventListener(): void;
    domStack(): Promise<void>;
    getForm(): HTMLFormElement | null;
    /**
     * Set the Dom filed has error
     * @param fields
     */
    setFieldErrors(fields: Array<string>): Promise<void>;
    validate(): Promise<boolean>;
    clearErrors(): Promise<void>;
    fillForm(values: object): Promise<void>;
    protected renderModalFormHtml(): Promise<void>;
    renderFormHtml(): Promise<string>;
    protected renderCancelFormDialog($formDialog: HTMLElement): Promise<void>;
    renderConfirmDeleteDialog(message: string): Promise<IConfirmDeleteDialog>;
    /**
     * Do a simple request
     * @param url
     * @protected
     */
    request(url: string): Promise<void>;
    /**
     * Save
     * @private
     */
    protected save(): Promise<void>;
    protected _getTable(id: string): Tabulator | null;
    protected abstract getTable(): Tabulator | null;
    showAlert(msg: string): Promise<void>;
    hideAlert(): Promise<void>;
    showWarning(msg: string): Promise<void>;
    hideWarning(): Promise<void>;
}
