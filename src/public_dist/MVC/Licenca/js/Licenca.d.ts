import { TabulatorFull as Tabulator } from 'tabulator-tables';
export declare class Licenca {
    static get baseUrl(): string;
    static readonly ControllerName = "Licenca";
    private static _baseUrl;
    static readonly tableId: string;
    tabContainer: HTMLElement | null;
    tableContainer: HTMLDivElement | null;
    table: Tabulator | null;
    constructor();
    init(): Promise<void>;
    createTable(): Promise<void>;
    private columnDefenitions;
}
