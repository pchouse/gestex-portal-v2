import { FetchService } from "../../../js/FetchService";
export declare class Dashboard {
    protected fetchService: FetchService;
    static get baseUrl(): string;
    static readonly ControllerName = "Dashboard";
    private static _baseUrl;
    static readonly tableId: string;
    tabContainer: HTMLElement | null;
    dashboardInvoicesTitle: HTMLDivElement | null;
    dashboardInvoicesCanvas: HTMLCanvasElement | null;
    dashboardCategoriaTitle: HTMLDivElement | null;
    dashboardCategoriaCanvas: HTMLCanvasElement | null;
    dashboardPraticosTitle: HTMLDivElement | null;
    dashboardPraticosCanvas: HTMLCanvasElement | null;
    dashboardTeoricosTitle: HTMLDivElement | null;
    dashboardTeoricosCanvas: HTMLCanvasElement | null;
    constructor(fetchService: FetchService);
    init(): Promise<void>;
    generateDashboardInvoices(): Promise<void>;
    generateDashboardCategorias(): Promise<void>;
    generateDashboardResultadosPraticos(): Promise<void>;
    generateDashboardResultadosTeoricos(): Promise<void>;
}
