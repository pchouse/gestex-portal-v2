import { TabulatorFull as Tabulator } from 'tabulator-tables';
import { FetchService } from "../../../js/FetchService";
export declare class Exame {
    protected fetchService: FetchService;
    static get baseUrl(): string;
    static readonly ControllerName = "Exame";
    private static _baseUrl;
    static readonly tableId: string;
    tabContainer: HTMLElement | null;
    tableTeoricosContainer: HTMLDivElement | null;
    tablePraticosContainer: HTMLDivElement | null;
    tableTeoricos: Tabulator | null;
    tablePraticos: Tabulator | null;
    fromDayElement: HTMLInputElement | null;
    toDayElement: HTMLInputElement | null;
    constructor(fetchService: FetchService);
    initAll(): Promise<void>;
    init(): Promise<void>;
    generatePdf(type: "all" | "teorico" | "pratico"): Promise<void>;
    setTablesFilter(): Promise<void>;
    createTeoricoTable(): Promise<void>;
    createPraticoTable(): Promise<void>;
    private columnDefenitions;
}
