import {singleton, injectable, container} from "tsyringe";
import {TabulatorFull as Tabulator, ColumnDefinition, CellComponent, Filter} from 'tabulator-tables';
import {TableHeaderMenu} from "../../../js/TableHeaderMenu";
import {FormatterExameResultado} from "../../../js/FormatterExameResultado";
import {TExame} from "./TExame";
import {FetchService} from "../../../js/FetchService";
import {easepick} from "@easepick/bundle";
import {Util} from "../../../js/Util";
import {FileDownload} from "../../../js/FileDownload";
import {Response} from "../../../js/Response";

@injectable()
@singleton()
export class Exame {

    public static get baseUrl(): string {
        return Exame._baseUrl;
    }

    public static readonly ControllerName = "Exame";

    private static _baseUrl = `/?controller=${Exame.ControllerName}`;

    public static readonly tableId = `${Exame.ControllerName}-table`;

    public tabContainer: HTMLElement | null = null;

    public tableTeoricosContainer: HTMLDivElement | null = null;
    public tablePraticosContainer: HTMLDivElement | null = null;

    public tableTeoricos: Tabulator | null = null;
    public tablePraticos: Tabulator | null = null;

    public fromDayElement: HTMLInputElement | null = null;
    public toDayElement: HTMLInputElement | null = null;

    constructor(protected fetchService: FetchService) {

    }

    initAll(): Promise<void> {
        return new Promise<void>(async (resolve, reject) => {
            try {

                let tabContentId = `tab-ExameAll`;

                let tabContainer = document.getElementById(tabContentId);

                if (tabContainer === null) {
                    console.log(`Tab content id ${tabContentId} not found`);
                    return resolve();
                }

                tabContainer?.firstElementChild?.remove()

                let tableContainer = document.createElement("div");
                tableContainer.id = "exame-all-table";
                tabContainer?.appendChild(tableContainer);

                new Tabulator(tableContainer, {
                    index           : "doc",
                    placeholder     : "Sem registos",
                    height          : (window.innerHeight - 149),
                    layout          : "fitColumns",
                    ajaxURL         : `${Exame.baseUrl}&action=dataGrid`,
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
                    columns         : this.columnDefenitions().map((c)=>{

                        if(c.field === "tipoExame"){
                            c.visible = true;
                        }else if(c.field === "pauta"){
                            c.visible = true;
                        }

                        return c;
                    }),
                });

                return resolve();
            } catch (e) {
                return reject(e);
            }
        });
    }

    init(): Promise<void> {
        return new Promise<void>(async (resolve, reject) => {
            try {

                let tabContentId = `tab-${Exame.ControllerName}`;

                this.tabContainer = document.getElementById(tabContentId);

                if (this.tabContainer === null) {
                    console.log(`Tab content id ${tabContentId} not found`);
                    resolve();
                    return;
                }

                let fetchLayoutResponse = await this.fetchService.get(
                    `${Exame.baseUrl}&action=layout`
                );

                let text = await fetchLayoutResponse.text();

                if (fetchLayoutResponse.status !== 200) {
                    return reject(text);
                }

                this.tabContainer?.firstElementChild?.remove()

                this.tabContainer?.insertAdjacentHTML("beforeend", text);

                this.tableTeoricosContainer = document.getElementById("exames-teoricos-table") as HTMLDivElement;
                this.tablePraticosContainer = document.getElementById("exames-praticos-table") as HTMLDivElement;
                this.fromDayElement = document.getElementById("exames_from-day") as HTMLInputElement;
                this.toDayElement = document.getElementById("exames_to-day") as HTMLInputElement;

                document.getElementById("exames-clear-date")?.addEventListener("click", () => {
                    this.fromDayElement!.value = "";
                    this.toDayElement!.value = "";
                    this.setTablesFilter();
                });

                document.getElementById("exames-print-teoricos")?.addEventListener(
                    "click", () => this.generatePdf("teorico").then(null).catch(null)
                );

                document.getElementById("exames-print-praticos")?.addEventListener(
                    "click", () => this.generatePdf("pratico").then(null).catch(null)
                );

                document.getElementById("exames-print-all")?.addEventListener(
                    "click", () => this.generatePdf("all").then(null).catch(null)
                );

                await Promise.all([
                    this.createTeoricoTable(),
                    this.createPraticoTable()
                ]);

                let self = this;

                // noinspection JSPotentiallyInvalidConstructorUsage
                new easepick.create({
                    element  : this.fromDayElement,
                    css      : [
                        '/vendor/easepick.css',
                    ],
                    format   : "YYYY-MM-DD",
                    readonly : false,
                    autoApply: true,
                    setup(picker) {
                        // noinspection all
                        picker.on("select", () => {
                            setTimeout(() => {
                                self.setTablesFilter();
                            }, 99)
                        });
                    }
                });

                this.fromDayElement.addEventListener(
                    "change",
                    async () => await this.setTablesFilter()
                );


                // noinspection JSPotentiallyInvalidConstructorUsage
                new easepick.create({
                    element  : this.toDayElement,
                    css      : [
                        '/vendor/easepick.css',
                    ],
                    format   : "YYYY-MM-DD",
                    readonly : false,
                    autoApply: true,
                    setup(picker) {
                        // noinspection all
                        picker.on("select", () => {
                            setTimeout(() => {
                                self.setTablesFilter();
                            }, 99)
                        });
                    }
                });

                this.toDayElement.addEventListener(
                    "change",
                    async () => await this.setTablesFilter()
                );

                return resolve();
            } catch (e) {
                return reject(e);
            }
        });
    }

    generatePdf(type: "all" | "teorico" | "pratico"): Promise<void> {

        return new Promise(async (resolve, reject) => {

            this.fromDayElement?.classList.remove("has-error");
            this.toDayElement?.classList.remove("has-error");

            let fromDay = this.fromDayElement?.value ?? "";
            let toDay = this.toDayElement?.value ?? "";

            if (fromDay.match(/[0-9]{4}-[0-9]{2}-[0-9]{2}/) === null) {
                this.fromDayElement?.classList.add("has-error");
                return resolve();
            }

            if (toDay.match(/[0-9]{4}-[0-9]{2}-[0-9]{2}/) === null) {
                this.toDayElement?.classList.add("has-error");
                return resolve();
            }

            let formData = new FormData();
            formData.set("fromDay", fromDay);
            formData.set("toDay", toDay);

            if (type === "all") {
                formData.set("action", "pdfAll");
            } else if (type === "teorico") {
                formData.set("action", "pdfTeoricos");
            } else {
                formData.set("action", "pdfPraticos");
            }

            let responseFetch = await this.fetchService.post(Exame.baseUrl, formData);

            if (responseFetch.status !== 200) {
                this.tableTeoricos?.alert(await responseFetch.text());
                setTimeout(() => this.tableTeoricos?.clearAlert(), 1999);
                return reject();
            }

            let response = await responseFetch.json() as Response;

            if (response.error) {
                this.tableTeoricos?.alert(response.msg ?? "Erro ao obter o PDF");
                setTimeout(() => this.tableTeoricos?.clearAlert(), 1999);
                return reject();
            }

            await container.resolve(FileDownload).showPdfFileFromResponse(response);

            return resolve();

        });
    }

    setTablesFilter(): Promise<void> {
        return new Promise<void>((resolve, _) => {

            let filters: Filter[] = [];

            this.fromDayElement?.classList.remove("has-error");
            this.toDayElement?.classList.remove("has-error");

            this.tablePraticos?.clearData();
            this.tableTeoricos?.clearData();

            let fromDay = this.fromDayElement?.value ?? "";
            let toDay = this.toDayElement?.value ?? "";

            if (Util.isNotEmpty(fromDay)) {
                if (fromDay.match(/[0-9]{4}-[0-9]{2}-[0-9]{2}/) !== null) {
                    filters.push({
                        field: "dataExame",
                        type : ">=",
                        value: fromDay
                    });
                } else {
                    this.fromDayElement?.classList.add("has-error");
                }
            }

            if (Util.isNotEmpty(toDay)) {
                if (toDay.match(/[0-9]{4}-[0-9]{2}-[0-9]{2}/) !== null) {
                    filters.push({
                        field: "dataExame",
                        type : "<=",
                        value: toDay
                    });
                } else {
                    this.toDayElement?.classList.add("has-error");
                }
            }

            this.tableTeoricos?.setFilter(filters);
            this.tablePraticos?.setFilter(filters);
            return resolve();
        });
    }


    createTeoricoTable(): Promise<void> {

        return new Promise<void>((resolve, reject) => {
            try {

                this.tableTeoricos = new Tabulator(this.tableTeoricosContainer ?? "", {
                    index           : "doc",
                    placeholder     : "Sem registos",
                    height          : (window.innerHeight - 149) / 2,
                    layout          : "fitColumns",
                    ajaxURL         : `${Exame.baseUrl}&action=dataGridTeoricos`,
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
                    columns         : this.columnDefenitions(),
                });

                return resolve();

            } catch (e) {
                reject(e);
            }
        });
    }

    createPraticoTable(): Promise<void> {

        return new Promise<void>((resolve, reject) => {
            try {

                this.tablePraticos = new Tabulator(this.tablePraticosContainer ?? "", {
                    index           : "doc",
                    placeholder     : "Sem registos",
                    height          : (window.innerHeight - 149) / 2,
                    layout          : "fitColumns",
                    ajaxURL         : `${Exame.baseUrl}&action=dataGridPratico`,
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
                    columns         : this.columnDefenitions().map((d: ColumnDefinition) => {
                        if (d.field === "sala") {
                            d.visible = false;
                        }
                        return d;
                    })
                });

                return resolve();

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
            }, {
                title       : "Factura",
                field       : "doc",
                visible     : true,
                headerFilter: "input",
                headerMenu  : TableHeaderMenu,
                hozAlign    : "left"
            },
            {
                title       : "Nº aluno",
                field       : "numAluno",
                visible     : true,
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
                title       : "Nome",
                field       : "nome",
                visible     : true,
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
                visible     : false,
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
            {
                title       : "Examinador",
                field       : "examinador",
                visible     : true,
                headerFilter: "input",
                headerMenu  : TableHeaderMenu,
                hozAlign    : "center",
            },
            {
                title       : "Pauta",
                field       : "pauta",
                visible     : false,
                headerFilter: "input",
                headerMenu  : TableHeaderMenu,
                hozAlign    : "center",
            },
        ]
    }

}
