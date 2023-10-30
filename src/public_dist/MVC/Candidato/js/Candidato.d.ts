import { TabulatorFull as Tabulator } from 'tabulator-tables';
export declare class Candidato {
    static get baseUrl(): string;
    static readonly ControllerName = "Candidato";
    private static _baseUrl;
    static readonly tableId: string;
    tabContainer: HTMLElement | null;
    tableContainer: HTMLDivElement | null;
    licencaTableContainer: HTMLDivElement | null;
    exameTableContainer: HTMLDivElement | null;
    table: Tabulator | null;
    licencaTable: Tabulator | null;
    exameTable: Tabulator | null;
    licencaTableUrlIsSet: boolean;
    exameTableUrlIsSet: boolean;
    constructor();
    init(): Promise<void>;
    createTable(): Promise<void>;
    private columnDefenitions;
    private licencaColumnDefenitions;
    private exameColumnDefenitions;
}
