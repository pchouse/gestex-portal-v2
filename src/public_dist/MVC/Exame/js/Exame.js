var __decorate = (this && this.__decorate) || function (decorators, target, key, desc) {
    var c = arguments.length, r = c < 3 ? target : desc === null ? desc = Object.getOwnPropertyDescriptor(target, key) : desc, d;
    if (typeof Reflect === "object" && typeof Reflect.decorate === "function") r = Reflect.decorate(decorators, target, key, desc);
    else for (var i = decorators.length - 1; i >= 0; i--) if (d = decorators[i]) r = (c < 3 ? d(r) : c > 3 ? d(target, key, r) : d(target, key)) || r;
    return c > 3 && r && Object.defineProperty(target, key, r), r;
};
var __metadata = (this && this.__metadata) || function (k, v) {
    if (typeof Reflect === "object" && typeof Reflect.metadata === "function") return Reflect.metadata(k, v);
};
var Exame_1;
import { singleton, injectable, container } from "tsyringe";
import { TabulatorFull as Tabulator } from 'tabulator-tables';
import { TableHeaderMenu } from "../../../js/TableHeaderMenu";
import { FormatterExameResultado } from "../../../js/FormatterExameResultado";
import { FetchService } from "../../../js/FetchService";
import { easepick } from "@easepick/bundle";
import { Util } from "../../../js/Util";
import { FileDownload } from "../../../js/FileDownload";
let Exame = Exame_1 = class Exame {
    static get baseUrl() {
        return Exame_1._baseUrl;
    }
    constructor(fetchService) {
        this.fetchService = fetchService;
        this.tabContainer = null;
        this.tableTeoricosContainer = null;
        this.tablePraticosContainer = null;
        this.tableTeoricos = null;
        this.tablePraticos = null;
        this.fromDayElement = null;
        this.toDayElement = null;
    }
    initAll() {
        return new Promise(async (resolve, reject) => {
            var _a;
            try {
                let tabContentId = `tab-ExameAll`;
                let tabContainer = document.getElementById(tabContentId);
                if (tabContainer === null) {
                    console.log(`Tab content id ${tabContentId} not found`);
                    return resolve();
                }
                (_a = tabContainer === null || tabContainer === void 0 ? void 0 : tabContainer.firstElementChild) === null || _a === void 0 ? void 0 : _a.remove();
                let tableContainer = document.createElement("div");
                tableContainer.id = "exame-all-table";
                tabContainer === null || tabContainer === void 0 ? void 0 : tabContainer.appendChild(tableContainer);
                new Tabulator(tableContainer, {
                    index: "doc",
                    placeholder: "Sem registos",
                    height: (window.innerHeight - 149),
                    layout: "fitColumns",
                    ajaxURL: `${Exame_1.baseUrl}&action=dataGrid`,
                    ajaxConfig: "POST",
                    ajaxContentType: "json",
                    renderHorizontal: "virtual",
                    responsiveLayout: "hide",
                    progressiveLoad: "scroll",
                    sortMode: "remote",
                    filterMode: "remote",
                    paginationSize: 49,
                    validationMode: "highlight",
                    initialSort: [{ column: "dataExame", dir: "desc" }],
                    columns: this.columnDefenitions().map((c) => {
                        if (c.field === "tipoExame") {
                            c.visible = true;
                        }
                        else if (c.field === "pauta") {
                            c.visible = true;
                        }
                        return c;
                    }),
                });
                return resolve();
            }
            catch (e) {
                return reject(e);
            }
        });
    }
    init() {
        return new Promise(async (resolve, reject) => {
            var _a, _b, _c, _d, _e, _f, _g;
            try {
                let tabContentId = `tab-${Exame_1.ControllerName}`;
                this.tabContainer = document.getElementById(tabContentId);
                if (this.tabContainer === null) {
                    console.log(`Tab content id ${tabContentId} not found`);
                    resolve();
                    return;
                }
                let fetchLayoutResponse = await this.fetchService.get(`${Exame_1.baseUrl}&action=layout`);
                let text = await fetchLayoutResponse.text();
                if (fetchLayoutResponse.status !== 200) {
                    return reject(text);
                }
                (_b = (_a = this.tabContainer) === null || _a === void 0 ? void 0 : _a.firstElementChild) === null || _b === void 0 ? void 0 : _b.remove();
                (_c = this.tabContainer) === null || _c === void 0 ? void 0 : _c.insertAdjacentHTML("beforeend", text);
                this.tableTeoricosContainer = document.getElementById("exames-teoricos-table");
                this.tablePraticosContainer = document.getElementById("exames-praticos-table");
                this.fromDayElement = document.getElementById("exames_from-day");
                this.toDayElement = document.getElementById("exames_to-day");
                (_d = document.getElementById("exames-clear-date")) === null || _d === void 0 ? void 0 : _d.addEventListener("click", () => {
                    this.fromDayElement.value = "";
                    this.toDayElement.value = "";
                    this.setTablesFilter();
                });
                (_e = document.getElementById("exames-print-teoricos")) === null || _e === void 0 ? void 0 : _e.addEventListener("click", () => this.generatePdf("teorico").then(null).catch(null));
                (_f = document.getElementById("exames-print-praticos")) === null || _f === void 0 ? void 0 : _f.addEventListener("click", () => this.generatePdf("pratico").then(null).catch(null));
                (_g = document.getElementById("exames-print-all")) === null || _g === void 0 ? void 0 : _g.addEventListener("click", () => this.generatePdf("all").then(null).catch(null));
                await Promise.all([
                    this.createTeoricoTable(),
                    this.createPraticoTable()
                ]);
                let self = this;
                // noinspection JSPotentiallyInvalidConstructorUsage
                new easepick.create({
                    element: this.fromDayElement,
                    css: [
                        '/vendor/easepick.css',
                    ],
                    format: "YYYY-MM-DD",
                    readonly: false,
                    autoApply: true,
                    setup(picker) {
                        // noinspection all
                        picker.on("select", () => {
                            setTimeout(() => {
                                self.setTablesFilter();
                            }, 99);
                        });
                    }
                });
                this.fromDayElement.addEventListener("change", async () => await this.setTablesFilter());
                // noinspection JSPotentiallyInvalidConstructorUsage
                new easepick.create({
                    element: this.toDayElement,
                    css: [
                        '/vendor/easepick.css',
                    ],
                    format: "YYYY-MM-DD",
                    readonly: false,
                    autoApply: true,
                    setup(picker) {
                        // noinspection all
                        picker.on("select", () => {
                            setTimeout(() => {
                                self.setTablesFilter();
                            }, 99);
                        });
                    }
                });
                this.toDayElement.addEventListener("change", async () => await this.setTablesFilter());
                return resolve();
            }
            catch (e) {
                return reject(e);
            }
        });
    }
    generatePdf(type) {
        return new Promise(async (resolve, reject) => {
            var _a, _b, _c, _d, _e, _f, _g, _h, _j, _k, _l;
            (_a = this.fromDayElement) === null || _a === void 0 ? void 0 : _a.classList.remove("has-error");
            (_b = this.toDayElement) === null || _b === void 0 ? void 0 : _b.classList.remove("has-error");
            let fromDay = (_d = (_c = this.fromDayElement) === null || _c === void 0 ? void 0 : _c.value) !== null && _d !== void 0 ? _d : "";
            let toDay = (_f = (_e = this.toDayElement) === null || _e === void 0 ? void 0 : _e.value) !== null && _f !== void 0 ? _f : "";
            if (fromDay.match(/[0-9]{4}-[0-9]{2}-[0-9]{2}/) === null) {
                (_g = this.fromDayElement) === null || _g === void 0 ? void 0 : _g.classList.add("has-error");
                return resolve();
            }
            if (toDay.match(/[0-9]{4}-[0-9]{2}-[0-9]{2}/) === null) {
                (_h = this.toDayElement) === null || _h === void 0 ? void 0 : _h.classList.add("has-error");
                return resolve();
            }
            let formData = new FormData();
            formData.set("fromDay", fromDay);
            formData.set("toDay", toDay);
            if (type === "all") {
                formData.set("action", "pdfAll");
            }
            else if (type === "teorico") {
                formData.set("action", "pdfTeoricos");
            }
            else {
                formData.set("action", "pdfPraticos");
            }
            let responseFetch = await this.fetchService.post(Exame_1.baseUrl, formData);
            if (responseFetch.status !== 200) {
                (_j = this.tableTeoricos) === null || _j === void 0 ? void 0 : _j.alert(await responseFetch.text());
                setTimeout(() => { var _a; return (_a = this.tableTeoricos) === null || _a === void 0 ? void 0 : _a.clearAlert(); }, 1999);
                return reject();
            }
            let response = await responseFetch.json();
            if (response.error) {
                (_k = this.tableTeoricos) === null || _k === void 0 ? void 0 : _k.alert((_l = response.msg) !== null && _l !== void 0 ? _l : "Erro ao obter o PDF");
                setTimeout(() => { var _a; return (_a = this.tableTeoricos) === null || _a === void 0 ? void 0 : _a.clearAlert(); }, 1999);
                return reject();
            }
            await container.resolve(FileDownload).showPdfFileFromResponse(response);
            return resolve();
        });
    }
    setTablesFilter() {
        return new Promise((resolve, _) => {
            var _a, _b, _c, _d, _e, _f, _g, _h, _j, _k, _l, _m;
            let filters = [];
            (_a = this.fromDayElement) === null || _a === void 0 ? void 0 : _a.classList.remove("has-error");
            (_b = this.toDayElement) === null || _b === void 0 ? void 0 : _b.classList.remove("has-error");
            (_c = this.tablePraticos) === null || _c === void 0 ? void 0 : _c.clearData();
            (_d = this.tableTeoricos) === null || _d === void 0 ? void 0 : _d.clearData();
            let fromDay = (_f = (_e = this.fromDayElement) === null || _e === void 0 ? void 0 : _e.value) !== null && _f !== void 0 ? _f : "";
            let toDay = (_h = (_g = this.toDayElement) === null || _g === void 0 ? void 0 : _g.value) !== null && _h !== void 0 ? _h : "";
            if (Util.isNotEmpty(fromDay)) {
                if (fromDay.match(/[0-9]{4}-[0-9]{2}-[0-9]{2}/) !== null) {
                    filters.push({
                        field: "dataExame",
                        type: ">=",
                        value: fromDay
                    });
                }
                else {
                    (_j = this.fromDayElement) === null || _j === void 0 ? void 0 : _j.classList.add("has-error");
                }
            }
            if (Util.isNotEmpty(toDay)) {
                if (toDay.match(/[0-9]{4}-[0-9]{2}-[0-9]{2}/) !== null) {
                    filters.push({
                        field: "dataExame",
                        type: "<=",
                        value: toDay
                    });
                }
                else {
                    (_k = this.toDayElement) === null || _k === void 0 ? void 0 : _k.classList.add("has-error");
                }
            }
            (_l = this.tableTeoricos) === null || _l === void 0 ? void 0 : _l.setFilter(filters);
            (_m = this.tablePraticos) === null || _m === void 0 ? void 0 : _m.setFilter(filters);
            return resolve();
        });
    }
    createTeoricoTable() {
        return new Promise((resolve, reject) => {
            var _a;
            try {
                this.tableTeoricos = new Tabulator((_a = this.tableTeoricosContainer) !== null && _a !== void 0 ? _a : "", {
                    index: "doc",
                    placeholder: "Sem registos",
                    height: (window.innerHeight - 149) / 2,
                    layout: "fitColumns",
                    ajaxURL: `${Exame_1.baseUrl}&action=dataGridTeoricos`,
                    ajaxConfig: "POST",
                    ajaxContentType: "json",
                    renderHorizontal: "virtual",
                    responsiveLayout: "hide",
                    progressiveLoad: "scroll",
                    sortMode: "remote",
                    filterMode: "remote",
                    paginationSize: 49,
                    validationMode: "highlight",
                    initialSort: [{ column: "dataExame", dir: "desc" }],
                    columns: this.columnDefenitions(),
                });
                return resolve();
            }
            catch (e) {
                reject(e);
            }
        });
    }
    createPraticoTable() {
        return new Promise((resolve, reject) => {
            var _a;
            try {
                this.tablePraticos = new Tabulator((_a = this.tablePraticosContainer) !== null && _a !== void 0 ? _a : "", {
                    index: "doc",
                    placeholder: "Sem registos",
                    height: (window.innerHeight - 149) / 2,
                    layout: "fitColumns",
                    ajaxURL: `${Exame_1.baseUrl}&action=dataGridPratico`,
                    ajaxConfig: "POST",
                    ajaxContentType: "json",
                    renderHorizontal: "virtual",
                    responsiveLayout: "hide",
                    progressiveLoad: "scroll",
                    sortMode: "remote",
                    filterMode: "remote",
                    paginationSize: 49,
                    validationMode: "highlight",
                    initialSort: [{ column: "dataExame", dir: "desc" }],
                    columns: this.columnDefenitions().map((d) => {
                        if (d.field === "sala") {
                            d.visible = false;
                        }
                        return d;
                    })
                });
                return resolve();
            }
            catch (e) {
                reject(e);
            }
        });
    }
    columnDefenitions() {
        return [
            {
                title: "Alvará",
                field: "alvara",
                visible: false,
                headerFilter: "input",
                headerMenu: TableHeaderMenu,
                hozAlign: "center"
            }, {
                title: "Factura",
                field: "doc",
                visible: true,
                headerFilter: "input",
                headerMenu: TableHeaderMenu,
                hozAlign: "left"
            },
            {
                title: "Nº aluno",
                field: "numAluno",
                visible: true,
                headerFilter: "input",
                headerMenu: TableHeaderMenu,
                hozAlign: "center"
            },
            {
                title: "B.I.",
                field: "numDocId",
                visible: true,
                headerFilter: "input",
                headerMenu: TableHeaderMenu,
                hozAlign: "center"
            },
            {
                title: "Nome",
                field: "nome",
                visible: true,
                headerFilter: "input",
                headerMenu: TableHeaderMenu,
                hozAlign: "left"
            },
            {
                title: "Data exame",
                field: "dataExame",
                visible: true,
                headerFilter: "input",
                hozAlign: "center",
                headerMenu: TableHeaderMenu
            },
            {
                title: "Hora exame",
                field: "hora",
                visible: true,
                headerFilter: "input",
                headerMenu: TableHeaderMenu,
                hozAlign: "center"
            },
            {
                title: "Sala",
                field: "sala",
                visible: true,
                headerFilter: "input",
                headerMenu: TableHeaderMenu,
                hozAlign: "center",
                //@ts-ignore
                formatter: function (cell) {
                    let tipo = cell.getData().tipoExame;
                    if (tipo === "P")
                        return "";
                    return cell.getData().sala;
                }
            },
            {
                title: "Categoria",
                field: "categoria",
                visible: true,
                headerFilter: "input",
                headerMenu: TableHeaderMenu,
                hozAlign: "center"
            },
            {
                title: "Tipo",
                field: "tipoExame",
                visible: false,
                headerFilter: "input",
                headerMenu: TableHeaderMenu,
                hozAlign: "center"
            },
            {
                title: "Resultado",
                field: "resultado",
                visible: true,
                headerFilter: "input",
                headerMenu: TableHeaderMenu,
                hozAlign: "center",
                //@ts-ignore
                formatter: FormatterExameResultado
            },
            {
                title: "Examinador",
                field: "examinador",
                visible: true,
                headerFilter: "input",
                headerMenu: TableHeaderMenu,
                hozAlign: "center",
            },
            {
                title: "Pauta",
                field: "pauta",
                visible: false,
                headerFilter: "input",
                headerMenu: TableHeaderMenu,
                hozAlign: "center",
            },
        ];
    }
};
Exame.ControllerName = "Exame";
Exame._baseUrl = `/?controller=${Exame_1.ControllerName}`;
Exame.tableId = `${Exame_1.ControllerName}-table`;
Exame = Exame_1 = __decorate([
    injectable(),
    singleton(),
    __metadata("design:paramtypes", [FetchService])
], Exame);
export { Exame };
//# sourceMappingURL=Exame.js.map