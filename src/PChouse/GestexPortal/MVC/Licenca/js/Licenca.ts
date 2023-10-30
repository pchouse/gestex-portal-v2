import {singleton, injectable} from "tsyringe";
import {TabulatorFull as Tabulator, ColumnDefinition, CellComponent} from 'tabulator-tables';
import {TableHeaderMenu} from "../../../js/TableHeaderMenu";
import {Util} from "../../../js/Util";
import {TLicenca} from "./TLicenca";

@injectable()
@singleton()
export class Licenca {

    public static get baseUrl(): string {
        return Licenca._baseUrl;
    }

    public static readonly ControllerName = "Licenca";

    private static _baseUrl = `/?controller=${Licenca.ControllerName}`;

    public static readonly tableId = `${Licenca.ControllerName}-table`;

    public tabContainer: HTMLElement | null = null;

    public tableContainer: HTMLDivElement | null = null;

    public table: Tabulator | null = null;


    constructor() {

    }

    init(): Promise<void> {
        return new Promise<void>(async (resolve, reject) => {
            try {

                let tabContentId = `tab-${Licenca.ControllerName}`;

                this.tabContainer = document.getElementById(tabContentId);

                if (this.tabContainer === null) {
                    console.log(`Tab content id ${tabContentId} not found`);
                    resolve();
                    return;
                }

                this.tableContainer = document.createElement("div");
                this.tableContainer.id = Licenca.tableId;
                this.tabContainer?.appendChild(this.tableContainer);

                await this.createTable();
                resolve();
            } catch (e) {
                reject(e);
            }
        });
    }

    createTable(): Promise<void> {

        return new Promise<void>((resolve, reject) => {
            try {


                this.table = new Tabulator(this.tabContainer ?? "", {
                    index           : "numLic",
                    placeholder     : "Sem registos",
                    height          : (window.innerHeight - 149),
                    layout          : "fitColumns",
                    ajaxURL         : `${Licenca.baseUrl}&action=dataGrid`,
                    ajaxConfig      : "POST",
                    ajaxContentType : "json",
                    renderHorizontal: "virtual",
                    responsiveLayout: "hide",
                    progressiveLoad : "scroll",
                    sortMode        : "remote",
                    filterMode      : "remote",
                    paginationSize  : 49,
                    validationMode  : "highlight",
                    initialSort     : [{column: "validadeLic", dir: "desc"}],
                    columns         : this.columnDefenitions(),
                });

                this.tabContainer?.removeChild(this.tabContainer.firstElementChild as Node);


                resolve();

            } catch (e) {
                reject(e);
            }
        });
    }


    private columnDefenitions(): ColumnDefinition[] {
        return [
            {
                title       : "Alvará",
                field       : "alvara",
                visible     : false,
                headerFilter: "input",
                headerMenu  : TableHeaderMenu,
                hozAlign    : "center"
            },
            {
                title       : "B.I.",
                field       : "numDocId",
                visible     : true,
                headerFilter: "input",
                headerMenu  : TableHeaderMenu,
                hozAlign    : "center"
            },
            {
                title       : "Tipo exame",
                field       : "descricao",
                visible     : true,
                headerFilter: "input",
                headerMenu  : TableHeaderMenu,
                hozAlign    : "left"
            },
            {
                title       : "Licença",
                field       : "numLic",
                visible     : true,
                headerFilter: "input",
                headerMenu  : TableHeaderMenu,
                hozAlign    : "center"
            },
            {
                title       : "Validade licença",
                field       : "validadeLic",
                headerFilter: "input",
                headerMenu  : TableHeaderMenu,
                visible     : true,
                hozAlign    : "center",
                //@ts-ignore
                formatter: function (cell: CellComponent) {
                    let dateString = (cell.getData() as TLicenca).validadeLic;

                    return `<span class="${Util.isEmpty(dateString) ? 'text-danger' : 'text-success'}">${dateString}</span>`;

                }
            }
        ]
    }

}
