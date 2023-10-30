import { TabulatorFull as Tabulator } from 'tabulator-tables';
export declare class Facturacao {
    static get baseUrl(): string;
    static readonly ControllerName = "Facturacao";
    private static _baseUrl;
    static readonly tableId: string;
    tabContainer: HTMLElement | null;
    tableContainer: HTMLDivElement | null;
    childTableContainer: HTMLDivElement | null;
    table: Tabulator | null;
    childTable: Tabulator | null;
    childTableUrlIsSelected: boolean;
    constructor();
    init(): Promise<void>;
    createTable(): Promise<void>;
    private columnDefenitions;
    private childColumnDefenitions;
}
