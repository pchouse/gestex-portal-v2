import {singleton, injectable} from "tsyringe";
import {TabulatorFull as Tabulator, ColumnDefinition, RowComponent, CellComponent, Filter} from 'tabulator-tables';
import {TableHeaderMenu} from "../../../js/TableHeaderMenu";
import {BigNumber} from "bignumber.js";
import {TFacturaDetalhe} from "./TFacturaDetalhe";
import {TFactura} from "./TFactura";
import {FormatterExameResultado} from "../../../js/FormatterExameResultado";

@injectable()
@singleton()
export class Facturacao {

    public static get baseUrl(): string {
        return Facturacao._baseUrl;
    }

    public static readonly ControllerName = "Facturacao";

    private static _baseUrl = `/?controller=${Facturacao.ControllerName}`;

    public static readonly tableId = `${Facturacao.ControllerName}-table`;

    public tabContainer: HTMLElement | null = null;

    public tableContainer: HTMLDivElement | null = null;
    public childTableContainer: HTMLDivElement | null = null;

    public table: Tabulator | null = null;

    public childTable: Tabulator | null = null;

    public childTableUrlIsSelected: boolean = false;

    constructor() {

    }

    init(): Promise<void> {
        return new Promise<void>(async (resolve, reject) => {
            try {
                let tabContentId = `tab-${Facturacao.ControllerName}`;
                this.tabContainer = document.getElementById(tabContentId);
                if (this.tabContainer === null) {
                    console.log(`Tab content id ${tabContentId} not found`);
                    resolve();
                    return;
                }

                this.tableContainer = document.createElement("div");
                this.tableContainer.id = Facturacao.tableId;
                this.tabContainer?.appendChild(this.tableContainer);

                this.childTableContainer = document.createElement("div");
                this.childTableContainer.id = `${Facturacao.tableId}-child`;
                this.childTableContainer.style.marginTop = "19px";
                this.tabContainer?.appendChild(this.childTableContainer);

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
                this.table = new Tabulator(this.tableContainer ?? "", {
                    index           : "doc",
                    placeholder     : "Sem registos",
                    height          : (window.innerHeight - 149) / 2,
                    renderHorizontal: "virtual",
                    layout          : "fitColumns",
                    responsiveLayout: "hide",
                    progressiveLoad : "scroll",
                    sortMode        : "remote",
                    filterMode      : "remote",
                    paginationSize  : 49,
                    ajaxURL         : `${Facturacao._baseUrl}&action=dataGrid`,
                    ajaxConfig      : "POST",
                    ajaxContentType : "json",
                    columns         : this.columnDefenitions(),
                    validationMode  : "highlight",
                    initialSort     : [{column: "data", dir: "desc"}]
                });

                this.table.on("rowClick", async (_: UIEvent, row: RowComponent) => {
                    let filter: Filter = {
                        field: "doc",
                        type : "=",
                        value: row.getData().doc
                    };

                    this.childTable?.clearData();
                    this.childTable?.setFilter([filter]);

                    if (!this.childTableUrlIsSelected) {
                        this.childTable?.setData(
                            `${Facturacao._baseUrl}&action=dataGridChild`
                        );
                    }

                    (row.getTable() as Tabulator).getSelectedRows().forEach((s: RowComponent) => s.deselect());
                    row.select();
                });

                this.table.on("dataProcessed", async () => {
                    if ((this.table as Tabulator).getSelectedRows.length === 0) {
                        this.childTable?.clearData();
                    }
                });

                this.childTable = new Tabulator(this.childTableContainer ?? "", {
                    index           : "numLinha",
                    placeholder     : "Sem registos",
                    height          : (window.innerHeight - 149) / 2,
                    layout          : "fitColumns",
                    ajaxURL         : undefined,
                    ajaxConfig      : "POST",
                    ajaxContentType : "json",
                    renderHorizontal: "virtual",
                    responsiveLayout: "hide",
                    progressiveLoad : "scroll",
                    sortMode        : "remote",
                    filterMode      : "remote",
                    paginationSize  : 49,
                    validationMode  : "highlight",
                    initialSort     : [{column: "numLinha", dir: "asc"}],
                    columns         : this.childColumnDefenitions(),
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
                title       : "Alvará",
                field       : "alvara",
                headerFilter: "input",
                headerMenu  : TableHeaderMenu,
                visible     : false,
                hozAlign    : "center"
            },
            {
                title       : "Factura",
                field       : "doc",
                headerFilter: "input",
                headerMenu  : TableHeaderMenu,
                visible     : true,
                hozAlign    : "center"
            },
            {
                title       : "Data",
                field       : "data",
                headerFilter: "input",
                headerMenu  : TableHeaderMenu,
                visible     : true,
                hozAlign    : "center"
            },
            {
                title       : "NIF",
                field       : "nif",
                headerFilter: "input",
                headerMenu  : TableHeaderMenu,
                visible     : false,
                hozAlign    : "center"
            },
            {
                title       : "Íliquido",
                field       : "iliquido",
                headerFilter: "input",
                headerMenu  : TableHeaderMenu,
                visible     : true,
                hozAlign    : "right",
                //@ts-ignore
                formatter: function (cell: CellComponent) {
                    return (new BigNumber((cell.getData() as TFactura).iliquido))
                        .toFormat(2, BigNumber.ROUND_HALF_UP);
                }
            },
            {
                title       : "IVA",
                field       : "iva",
                headerFilter: "input",
                headerMenu  : TableHeaderMenu,
                visible     : true,
                hozAlign    : "right",
                //@ts-ignore
                formatter: function (cell: CellComponent) {
                    return (new BigNumber((cell.getData() as TFactura).iva))
                        .toFormat(2, BigNumber.ROUND_HALF_UP);
                }
            },
            {
                title       : "Total",
                field       : "total",
                headerFilter: "input",
                headerMenu  : TableHeaderMenu,
                visible     : true,
                hozAlign    : "right",
                //@ts-ignore
                formatter: function (cell: CellComponent) {
                    return (new BigNumber((cell.getData() as TFactura).total))
                        .toFormat(2, BigNumber.ROUND_HALF_UP);
                }
            },
        ]
    }

    private childColumnDefenitions(): ColumnDefinition[] {
        return [
            {
                title     : "Alvará",
                field     : "alvara",
                visible   : false,
                headerMenu: TableHeaderMenu,
                hozAlign  : "center"
            }, {
                title     : "Factura",
                field     : "doc",
                visible   : false,
                headerMenu: TableHeaderMenu,
                hozAlign  : "center"
            },
            {
                title     : "Nº linha",
                field     : "numLinha",
                visible   : true,
                headerMenu: TableHeaderMenu,
                hozAlign  : "center"
            },
            {
                title     : "Artigo",
                field     : "artigo",
                visible   : true,
                headerMenu: TableHeaderMenu,
                hozAlign  : "left"
            },
            {
                title     : "Descrição",
                field     : "descricao",
                visible   : true,
                headerMenu: TableHeaderMenu,
                hozAlign  : "left"
            },
            {
                title     : "Preço",
                field     : "preco",
                visible   : true,
                hozAlign  : "right",
                headerMenu: TableHeaderMenu,
                //@ts-ignore
                formatter: function (cell: CellComponent) {
                    return (new BigNumber((cell.getData() as TFacturaDetalhe).preco))
                        .toFormat(2, BigNumber.ROUND_HALF_UP);
                }
            },
            {
                title     : "Qt.",
                field     : "quantidade",
                visible   : true,
                headerMenu: TableHeaderMenu,
                hozAlign  : "right"
            },
            {
                title     : "Total",
                field     : "precoTotal",
                visible   : true,
                headerMenu: TableHeaderMenu,
                hozAlign  : "right",
                //@ts-ignore
                formatter: function (cell: CellComponent) {
                    return (new BigNumber((cell.getData() as TFacturaDetalhe).precoTotal))
                        .toFormat(2, BigNumber.ROUND_HALF_UP);
                }
            },
            {
                title     : "B.I.",
                field     : "numDocId",
                visible   : false,
                headerMenu: TableHeaderMenu,
                hozAlign  : "center"
            },
            {
                title     : "Nome",
                field     : "nome",
                visible   : false,
                headerMenu: TableHeaderMenu,
                hozAlign  : "center"
            },
            {
                title     : "Categoria",
                field     : "categoria",
                visible   : true,
                headerMenu: TableHeaderMenu,
                hozAlign  : "center"
            },
            {
                title     : "Data exame",
                field     : "dataExame",
                visible   : true,
                headerMenu: TableHeaderMenu,
                hozAlign  : "center"
            },
            {
                title     : "Hora exame",
                field     : "horaExame",
                visible   : true,
                headerMenu: TableHeaderMenu,
                hozAlign  : "center"
            },
            {
                title     : "Marcado",
                field     : "exameMarcado",
                visible   : true,
                formatter : "tickCross",
                headerMenu: TableHeaderMenu,
                hozAlign  : "center"
            },
            {
                title     : "Resultado",
                field     : "resultado",
                visible   : true,
                headerMenu: TableHeaderMenu,
                hozAlign  : "center",
                //@ts-ignore
                formatter: FormatterExameResultado
            },
        ]
    }
}
