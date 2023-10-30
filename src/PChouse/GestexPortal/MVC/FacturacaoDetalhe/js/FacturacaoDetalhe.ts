import {singleton, injectable} from "tsyringe";
import {TabulatorFull as Tabulator, ColumnDefinition, CellComponent} from 'tabulator-tables';
import {TableHeaderMenu} from "../../../js/TableHeaderMenu";
import {BigNumber} from "bignumber.js";
import {TFacturaDetalhe} from "../../Facturacao/js/TFacturaDetalhe";
import {FormatterExameResultado} from "../../../js/FormatterExameResultado";

@injectable()
@singleton()
export class FacturacaoDetalhe {

    public static get baseUrl(): string {
        return FacturacaoDetalhe._baseUrl;
    }

    public static readonly ControllerName = "FacturacaoDetalhe";

    private static _baseUrl = `/?controller=${FacturacaoDetalhe.ControllerName}`;

    public static readonly tableId = `${FacturacaoDetalhe.ControllerName}-table`;

    public tabContainer: HTMLElement | null = null;

    public tableContainer: HTMLDivElement | null = null;

    public table: Tabulator | null = null;

    public childTable: Tabulator | null = null;

    constructor() {

    }

    init(): Promise<void> {
        return new Promise<void>(async (resolve, reject) => {
            try {
                let tabContentId = `tab-${FacturacaoDetalhe.ControllerName}`;
                this.tabContainer = document.getElementById(tabContentId);
                if (this.tabContainer === null) {
                    console.log(`Tab content id ${tabContentId} not found`);
                    resolve();
                    return;
                }

                this.tableContainer = document.createElement("div");
                this.tableContainer.id = FacturacaoDetalhe.tableId;
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

                this.childTable = new Tabulator(this.tableContainer ?? "", {
                    index           : "numLinha",
                    placeholder     : "Sem registos",
                    height          : window.innerHeight - 149,
                    layout          : "fitColumns",
                    ajaxURL         : `${FacturacaoDetalhe._baseUrl}&action=dataGrid`,
                    ajaxConfig      : "POST",
                    ajaxContentType : "json",
                    renderHorizontal: "virtual",
                    responsiveLayout: "hide",
                    progressiveLoad : "scroll",
                    sortMode        : "remote",
                    filterMode      : "remote",
                    paginationSize  : 49,
                    validationMode  : "highlight",
                    initialSort     : [
                        {column: "doc", dir: "asc"},
                        {column: "numLinha", dir: "asc"}
                    ],
                    columns         : this.columnDefenitions(),
                })

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
                title   : "Alvará",
                field   : "alvara",
                visible : false,
                headerFilter: "input",
                headerMenu  : TableHeaderMenu,
                hozAlign: "center"
            }, {
                title   : "Factura",
                field   : "doc",
                visible : true,
                headerFilter: "input",
                headerMenu  : TableHeaderMenu,
                hozAlign: "center"
            },
            {
                title   : "Nº linha",
                field   : "numLinha",
                visible : true,
                headerFilter: "input",
                headerMenu  : TableHeaderMenu,
                hozAlign: "center"
            },
            {
                title   : "Artigo",
                field   : "artigo",
                visible : true,
                headerFilter: "input",
                headerMenu  : TableHeaderMenu,
                hozAlign: "left"
            },
            {
                title   : "Descrição",
                field   : "descricao",
                visible : true,
                headerFilter: "input",
                headerMenu  : TableHeaderMenu,
                hozAlign: "left"
            },
            {
                title   : "Preço",
                field   : "preco",
                visible : true,
                hozAlign: "right",
                headerFilter: "input",
                headerMenu  : TableHeaderMenu,
                //@ts-ignore
                formatter: function (cell:CellComponent) {
                    return (new BigNumber((cell.getData() as TFacturaDetalhe).preco))
                        .toFormat(2, BigNumber.ROUND_HALF_UP);
                }
            },
            {
                title   : "Qt.",
                field   : "quantidade",
                visible : true,
                headerFilter: "input",
                headerMenu  : TableHeaderMenu,
                hozAlign: "right"
            },
            {
                title   : "Total",
                field   : "precoTotal",
                visible : true,
                headerMenu  : TableHeaderMenu,
                headerFilter: "input",
                hozAlign: "right",
                //@ts-ignore
                formatter: function (cell:CellComponent) {
                    return (new BigNumber((cell.getData() as TFacturaDetalhe).precoTotal))
                        .toFormat(2, BigNumber.ROUND_HALF_UP);
                }
            },
            {
                title   : "B.I.",
                field   : "numDocId",
                visible : true,
                headerMenu  : TableHeaderMenu,
                headerFilter: "input",
                hozAlign: "center"
            },
            {
                title   : "Nome",
                field   : "nome",
                visible : true,
                headerFilter: "input",
                headerMenu  : TableHeaderMenu,
                hozAlign: "center"
            },
            {
                title   : "Categoria",
                field   : "categoria",
                visible : true,
                headerFilter: "input",
                headerMenu  : TableHeaderMenu,
                hozAlign: "center"
            },
            {
                title   : "Data exame",
                field   : "dataExame",
                visible : true,
                headerFilter: "input",
                headerMenu  : TableHeaderMenu,
                hozAlign: "center"
            },
            {
                title   : "Hora exame",
                field   : "horaExame",
                visible : true,
                headerFilter: "input",
                headerMenu  : TableHeaderMenu,
                hozAlign: "center"
            },
            {
                title    : "Marcado",
                field    : "exameMarcado",
                visible  : true,
                formatter: "tickCross",
                headerFilter: "input",
                headerMenu  : TableHeaderMenu,
                hozAlign : "center"
            },
            {
                title   : "Resultado",
                field   : "resultado",
                visible : true,
                headerFilter: "input",
                headerMenu  : TableHeaderMenu,
                hozAlign: "center",
                //@ts-ignore
                formatter: FormatterExameResultado
            },
        ]
    }
}
