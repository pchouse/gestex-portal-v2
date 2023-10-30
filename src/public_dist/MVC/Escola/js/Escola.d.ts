import { TEscola } from "./TEscola";
import { FetchService } from "../../../js/FetchService";
export declare class Escola {
    static readonly cache: Map<string, TEscola | null>;
    static get baseUrl(): string;
    protected static html: string | null;
    static readonly ControllerName = "Escola";
    private static _baseUrl;
    static readonly tableId = "escola-table";
    protected fetchService: FetchService;
    protected tabContainer: HTMLElement | null;
    constructor(fetchService: FetchService);
    init(): Promise<void>;
}
