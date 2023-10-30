import {container, singleton, injectable} from "tsyringe";
import {TabulatorFull as Tabulator, ColumnDefinition, RowComponent, CellComponent} from 'tabulator-tables';
import {EscolaForm} from "./EscolaForm";
import {Util} from "../../../js/Util";
import {TEscola} from "./TEscola";
import {FetchService} from "../../../js/FetchService";

@injectable()
@singleton()
export class Escola {

    public static readonly cache: Map<string, TEscola | null> = new Map();

    static get baseUrl(): string {
        return this._baseUrl;
    }

    protected static html: string | null = null;

    public static readonly ControllerName = "Escola";

    private static _baseUrl = `/?controller=${Escola.ControllerName}`;

    public static readonly tableId = "escola-table";

    protected fetchService: FetchService;

    protected tabContainer: HTMLElement | null = null;

    constructor(fetchService: FetchService) {
        this.fetchService = fetchService;
    }

    init(): Promise<void> {
        return new Promise<void>(async (resolve, reject) => {
            try {

                return  resolve();
            } catch (e) {
                console.log(e);
                reject(e);
            }
        });
    }

}
