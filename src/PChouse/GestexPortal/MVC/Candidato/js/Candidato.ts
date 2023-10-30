import {singleton, injectable} from "tsyringe";
import {TabulatorFull as Tabulator, ColumnDefinition, RowComponent, CellComponent, Filter} from 'tabulator-tables';
import {TableHeaderMenu} from "../../../js/TableHeaderMenu";
import {FormatterExameResultado} from "../../../js/FormatterExameResultado";
import {TCandidato} from "./TCandidato";
import {Util} from "../../../js/Util";
import {TLicenca} from "../../Licenca/js/TLicenca";
import {TExame} from "../../Exame/js/TExame";
import {Exame} from "../../Exame/js/Exame";
import {Licenca} from "../../Licenca/js/Licenca";

@injectable()
@singleton()
export class Candidato {

    public static get baseUrl(): string {
        return Candidato._baseUrl;
    }

    public static readonly ControllerName = "Candidato";

    private static _baseUrl = `/?controller=${Candidato.ControllerName}`;

    public static readonly tableId = `${Candidato.ControllerName}-table`;

    public tabContainer: HTMLElement | null = null;

    public tableContainer: HTMLDivElement | null = null;
    public licencaTableContainer: HTMLDivElement | null = null;
    public exameTableContainer: HTMLDivElement | null = null;

    public table: Tabulator | null = null;
    public licencaTable: Tabulator | null = null;
    public exameTable: Tabulator | null = null;

    public licencaTableUrlIsSet: boolean = false;
    public exameTableUrlIsSet: boolean = false;

    constructor() {

    }

    init(): Promise<void> {
        return new Promise<void>(async (resolve, reject) => {
            try {

                let tabContentId = `tab-${Candidato.ControllerName}`;

                this.tabContainer = document.getElementById(tabContentId);

                if (this.tabContainer === null) {
                    console.log(`Tab content id ${tabContentId} not found`);
                    resolve();
                    return;
                }

                this.tableContainer = document.createElement("div");
                this.tableContainer.id = Candidato.tableId;
                this.tabContainer?.appendChild(this.tableContainer);

                let leftContainer = document.createElement("div");
                leftContainer.style.width = `${window.innerWidth / 3}px`;
                leftContainer.style.float = "left";

                this.licencaTableContainer = document.createElement("div");
                this.licencaTableContainer.id = `${Candidato.tableId}-licenca`;
                this.licencaTableContainer.style.marginTop = "19px";
                leftContainer.appendChild(this.licencaTableContainer)
                this.tabContainer?.appendChild(leftContainer);

                let rightContainer = document.createElement("div");
                rightContainer.style.width = `${((window.innerWidth / 3) * 2) - 100}px`;
                rightContainer.style.float = `right`;

                this.exameTableContainer = document.createElement("div");
                this.exameTableContainer.id = `${Candidato.tableId}-exame`;
                this.exameTableContainer.style.marginTop = "19px";
                rightContainer.appendChild(this.exameTableContainer);
                this.tabContainer?.appendChild(rightContainer);

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
                    index           : "idCandidato",
                    placeholder     : "Sem registos",
                    height          : (window.innerHeight - 149) / 2,
                    renderHorizontal: "virtual",
                    layout          : "fitColumns",
                    responsiveLayout: "hide",
                    progressiveLoad : "scroll",
                    sortMode        : "remote",
                    filterMode      : "remote",
                    paginationSize  : 49,
                    ajaxURL         : `${Candidato._baseUrl}&action=dataGrid`,
                    ajaxConfig      : "POST",
                    ajaxContentType : "json",
                    columns         : this.columnDefenitions(),
                    validationMode  : "highlight",
                    initialSort     : [{column: "name", dir: "asc"}],
                });

                this.table.on("dataProcessed", async () => {
                    if ((this.table as Tabulator).getSelectedRows.length === 0) {
                        this.licencaTable?.clearData();
                        this.exameTable?.clearData();
                    }
                });

                this.table.on("rowClick", async (_: UIEvent, row: RowComponent) => {
                    let filter: Filter = {
                        field: "numDocId",
                        type : "=",
                        value: (row.getData() as TCandidato).numDocId
                    };

                    this.licencaTable?.clearData();
                    this.licencaTable?.setFilter([filter]);

                    if (!this.licencaTableUrlIsSet) {
                        this.licencaTable?.setData(
                            `${Licenca.baseUrl}&action=dataGrid`
                        );
                        this.licencaTableUrlIsSet = true;
                    }

                    this.exameTable?.clearData();
                    this.exameTable?.setFilter([filter]);

                    if (!this.exameTableUrlIsSet) {
                        this.exameTable?.setData(
                            `${Exame.baseUrl}&action=dataGrid`
                        );
                        this.exameTableUrlIsSet = true;
                    }

                    (row.getTable() as Tabulator).getSelectedRows().forEach((s: RowComponent) => s.deselect());
                    row.select();
                });

                this.licencaTable = new Tabulator(this.licencaTableContainer ?? "", {
                    index           : "numLic",
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
                    initialSort     : [{column: "validadeLic", dir: "asc"}],
                    columns         : this.licencaColumnDefenitions(),
                });

                this.exameTable = new Tabulator(this.exameTableContainer ?? "", {
                    index           : "doc",
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
                    initialSort     : [{column: "dataExame", dir: "desc"}],
                    columns         : this.exameColumnDefenitions(),
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
                title       : "ID",
                field       : "idCandidato",
                headerFilter: "input",
                headerMenu  : TableHeaderMenu,
                visible     : false,
                hozAlign    : "center"
            },
            {
                title       : "Alvará",
                field       : "alvara",
                headerFilter: "input",
                headerMenu  : TableHeaderMenu,
                visible     : false,
                hozAlign    : "center"
            },
            {
                title       : "B.I.",
                field       : "numDocId",
                headerFilter: "input",
                headerMenu  : TableHeaderMenu,
                visible     : true,
                hozAlign    : "center",
                width       : 179,
            },
            {
                title       : "B.I. validade",
                field       : "docValidade",
                headerFilter: "input",
                headerMenu  : TableHeaderMenu,
                visible     : true,
                hozAlign    : "center",
                width       : 179,
                //@ts-ignore
                formatter: function (cell: CellComponent) {
                    let dateString = (cell.getData() as TCandidato).docValidade;

                    return `<span class="${Util.isEmpty(dateString) ? 'text-danger' : 'text-success'}">${dateString}</span>`;

                }
            },
            {
                title       : "Nome",
                field       : "name",
                headerFilter: "input",
                headerMenu  : TableHeaderMenu,
                visible     : true,
                hozAlign    : "left"
            },
            {
                title       : "NIF",
                field       : "contribuinte",
                headerFilter: "input",
                headerMenu  : TableHeaderMenu,
                visible     : true,
                hozAlign    : "center",
                width       : 179,
            },
            {
                title       : "Data Nascimento",
                field       : "dataNascimento",
                headerFilter: "input",
                headerMenu  : TableHeaderMenu,
                visible     : true,
                hozAlign    : "center",
                width       : 179,
            },
            {
                title       : "Nº aluno",
                field       : "numAluno",
                width       : 99,
                headerFilter: "input",
                headerMenu  : TableHeaderMenu,
                visible     : true,
                hozAlign    : "center",
            },
            {
                title       : "Validade teórico",
                field       : "validadeTeorico",
                headerFilter: "input",
                headerMenu  : TableHeaderMenu,
                visible     : true,
                hozAlign    : "center",
                width       : 179,
                //@ts-ignore
                formatter: function (cell: CellComponent) {
                    let dateString = (cell.getData() as TCandidato).validadeTeorico;

                    return `<span class="${Util.isEmpty(dateString) ? 'text-danger' : 'text-success'}">${dateString ?? ""}</span>`;

                }
            },
        ]
    }

    private licencaColumnDefenitions(): ColumnDefinition[] {
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
                visible     : false,
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

    private exameColumnDefenitions(): ColumnDefinition[] {
        return [
            {
                title       : "Alvará",
                field       : "alvara",
                visible     : false,
                headerFilter: "input",
                headerMenu  : TableHeaderMenu,
                hozAlign    : "center"
            }, {
                title       : "Factura",
                field       : "doc",
                visible     : true,
                headerFilter: "input",
                headerMenu  : TableHeaderMenu,
                hozAlign    : "center"
            },
            {
                title       : "Nº aluno",
                field       : "numAluno",
                visible     : false,
                headerFilter: "input",
                headerMenu  : TableHeaderMenu,
                hozAlign    : "center"
            },
            {
                title       : "B.I.",
                field       : "numDocId",
                visible     : false,
                headerFilter: "input",
                headerMenu  : TableHeaderMenu,
                hozAlign    : "center"
            },
            {
                title       : "Nome",
                field       : "nome",
                visible     : false,
                headerFilter: "input",
                headerMenu  : TableHeaderMenu,
                hozAlign    : "left"
            },
            {
                title       : "Data exame",
                field       : "dataExame",
                visible     : true,
                headerFilter: "input",
                hozAlign    : "center",
                headerMenu  : TableHeaderMenu
            },
            {
                title       : "Hora exame",
                field       : "hora",
                visible     : true,
                headerFilter: "input",
                headerMenu  : TableHeaderMenu,
                hozAlign    : "center"
            },
            {
                title       : "Sala",
                field       : "sala",
                visible     : true,
                headerFilter: "input",
                headerMenu  : TableHeaderMenu,
                hozAlign    : "center",
                //@ts-ignore
                formatter: function (cell: CellComponent) {
                    let tipo = (cell.getData() as TExame).tipoExame;
                    if (tipo === "P") return "";
                    return (cell.getData() as TExame).sala;
                }
            },
            {
                title       : "Categoria",
                field       : "categoria",
                visible     : true,
                headerFilter: "input",
                headerMenu  : TableHeaderMenu,
                hozAlign    : "center"
            },
            {
                title       : "Tipo",
                field       : "tipoExame",
                visible     : true,
                headerFilter: "input",
                headerMenu  : TableHeaderMenu,
                hozAlign    : "center"
            },
            {
                title       : "Resultado",
                field       : "resultado",
                visible     : true,
                headerFilter: "input",
                headerMenu  : TableHeaderMenu,
                hozAlign    : "center",
                //@ts-ignore
                formatter: FormatterExameResultado
            },
        ]
    }

}
