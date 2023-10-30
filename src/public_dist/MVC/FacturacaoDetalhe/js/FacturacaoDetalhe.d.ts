import { TabulatorFull as Tabulator } from 'tabulator-tables';
export declare class FacturacaoDetalhe {
    static get baseUrl(): string;
    static readonly ControllerName = "FacturacaoDetalhe";
    private static _baseUrl;
    static readonly tableId: string;
    tabContainer: HTMLElement | null;
    tableContainer: HTMLDivElement | null;
    table: Tabulator | null;
    childTable: Tabulator | null;
    constructor();
    init(): Promise<void>;
    createTable(): Promise<void>;
    private columnDefenitions;
}
