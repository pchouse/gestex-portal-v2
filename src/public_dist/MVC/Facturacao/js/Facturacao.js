var __decorate = (this && this.__decorate) || function (decorators, target, key, desc) {
    var c = arguments.length, r = c < 3 ? target : desc === null ? desc = Object.getOwnPropertyDescriptor(target, key) : desc, d;
    if (typeof Reflect === "object" && typeof Reflect.decorate === "function") r = Reflect.decorate(decorators, target, key, desc);
    else for (var i = decorators.length - 1; i >= 0; i--) if (d = decorators[i]) r = (c < 3 ? d(r) : c > 3 ? d(target, key, r) : d(target, key)) || r;
    return c > 3 && r && Object.defineProperty(target, key, r), r;
};
var __metadata = (this && this.__metadata) || function (k, v) {
    if (typeof Reflect === "object" && typeof Reflect.metadata === "function") return Reflect.metadata(k, v);
};
var Facturacao_1;
import { singleton, injectable } from "tsyringe";
import { TabulatorFull as Tabulator } from 'tabulator-tables';
import { TableHeaderMenu } from "../../../js/TableHeaderMenu";
import { BigNumber } from "bignumber.js";
import { FormatterExameResultado } from "../../../js/FormatterExameResultado";
let Facturacao = Facturacao_1 = class Facturacao {
    static get baseUrl() {
        return Facturacao_1._baseUrl;
    }
    constructor() {
        this.tabContainer = null;
        this.tableContainer = null;
        this.childTableContainer = null;
        this.table = null;
        this.childTable = null;
        this.childTableUrlIsSelected = false;
    }
    init() {
        return new Promise(async (resolve, reject) => {
            var _a, _b;
            try {
                let tabContentId = `tab-${Facturacao_1.ControllerName}`;
                this.tabContainer = document.getElementById(tabContentId);
                if (this.tabContainer === null) {
                    console.log(`Tab content id ${tabContentId} not found`);
                    resolve();
                    return;
                }
                this.tableContainer = document.createElement("div");
                this.tableContainer.id = Facturacao_1.tableId;
                (_a = this.tabContainer) === null || _a === void 0 ? void 0 : _a.appendChild(this.tableContainer);
                this.childTableContainer = document.createElement("div");
                this.childTableContainer.id = `${Facturacao_1.tableId}-child`;
                this.childTableContainer.style.marginTop = "19px";
                (_b = this.tabContainer) === null || _b === void 0 ? void 0 : _b.appendChild(this.childTableContainer);
                await this.createTable();
                resolve();
            }
            catch (e) {
                reject(e);
            }
        });
    }
    createTable() {
        return new Promise((resolve, reject) => {
            var _a, _b, _c;
            try {
                this.table = new Tabulator((_a = this.tableContainer) !== null && _a !== void 0 ? _a : "", {
                    index: "doc",
                    placeholder: "Sem registos",
                    height: (window.innerHeight - 149) / 2,
                    renderHorizontal: "virtual",
                    layout: "fitColumns",
                    responsiveLayout: "hide",
                    progressiveLoad: "scroll",
                    sortMode: "remote",
                    filterMode: "remote",
                    paginationSize: 49,
                    ajaxURL: `${Facturacao_1._baseUrl}&action=dataGrid`,
                    ajaxConfig: "POST",
                    ajaxContentType: "json",
                    columns: this.columnDefenitions(),
                    validationMode: "highlight",
                    initialSort: [{ column: "data", dir: "desc" }]
                });
                this.table.on("rowClick", async (_, row) => {
                    var _a, _b, _c;
                    let filter = {
                        field: "doc",
                        type: "=",
                        value: row.getData().doc
                    };
                    (_a = this.childTable) === null || _a === void 0 ? void 0 : _a.clearData();
                    (_b = this.childTable) === null || _b === void 0 ? void 0 : _b.setFilter([filter]);
                    if (!this.childTableUrlIsSelected) {
                        (_c = this.childTable) === null || _c === void 0 ? void 0 : _c.setData(`${Facturacao_1._baseUrl}&action=dataGridChild`);
                    }
                    row.getTable().getSelectedRows().forEach((s) => s.deselect());
                    row.select();
                });
                this.table.on("dataProcessed", async () => {
                    var _a;
                    if (this.table.getSelectedRows.length === 0) {
                        (_a = this.childTable) === null || _a === void 0 ? void 0 : _a.clearData();
                    }
                });
                this.childTable = new Tabulator((_b = this.childTableContainer) !== null && _b !== void 0 ? _b : "", {
                    index: "numLinha",
                    placeholder: "Sem registos",
                    height: (window.innerHeight - 149) / 2,
                    layout: "fitColumns",
                    ajaxURL: undefined,
                    ajaxConfig: "POST",
                    ajaxContentType: "json",
                    renderHorizontal: "virtual",
                    responsiveLayout: "hide",
                    progressiveLoad: "scroll",
                    sortMode: "remote",
                    filterMode: "remote",
                    paginationSize: 49,
                    validationMode: "highlight",
                    initialSort: [{ column: "numLinha", dir: "asc" }],
                    columns: this.childColumnDefenitions(),
                });
                (_c = this.tabContainer) === null || _c === void 0 ? void 0 : _c.removeChild(this.tabContainer.firstElementChild);
                resolve();
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
                headerFilter: "input",
                headerMenu: TableHeaderMenu,
                visible: false,
                hozAlign: "center"
            },
            {
                title: "Factura",
                field: "doc",
                headerFilter: "input",
                headerMenu: TableHeaderMenu,
                visible: true,
                hozAlign: "center"
            },
            {
                title: "Data",
                field: "data",
                headerFilter: "input",
                headerMenu: TableHeaderMenu,
                visible: true,
                hozAlign: "center"
            },
            {
                title: "NIF",
                field: "nif",
                headerFilter: "input",
                headerMenu: TableHeaderMenu,
                visible: false,
                hozAlign: "center"
            },
            {
                title: "Íliquido",
                field: "iliquido",
                headerFilter: "input",
                headerMenu: TableHeaderMenu,
                visible: true,
                hozAlign: "right",
                //@ts-ignore
                formatter: function (cell) {
                    return (new BigNumber(cell.getData().iliquido))
                        .toFormat(2, BigNumber.ROUND_HALF_UP);
                }
            },
            {
                title: "IVA",
                field: "iva",
                headerFilter: "input",
                headerMenu: TableHeaderMenu,
                visible: true,
                hozAlign: "right",
                //@ts-ignore
                formatter: function (cell) {
                    return (new BigNumber(cell.getData().iva))
                        .toFormat(2, BigNumber.ROUND_HALF_UP);
                }
            },
            {
                title: "Total",
                field: "total",
                headerFilter: "input",
                headerMenu: TableHeaderMenu,
                visible: true,
                hozAlign: "right",
                //@ts-ignore
                formatter: function (cell) {
                    return (new BigNumber(cell.getData().total))
                        .toFormat(2, BigNumber.ROUND_HALF_UP);
                }
            },
        ];
    }
    childColumnDefenitions() {
        return [
            {
                title: "Alvará",
                field: "alvara",
                visible: false,
                headerMenu: TableHeaderMenu,
                hozAlign: "center"
            }, {
                title: "Factura",
                field: "doc",
                visible: false,
                headerMenu: TableHeaderMenu,
                hozAlign: "center"
            },
            {
                title: "Nº linha",
                field: "numLinha",
                visible: true,
                headerMenu: TableHeaderMenu,
                hozAlign: "center"
            },
            {
                title: "Artigo",
                field: "artigo",
                visible: true,
                headerMenu: TableHeaderMenu,
                hozAlign: "left"
            },
            {
                title: "Descrição",
                field: "descricao",
                visible: true,
                headerMenu: TableHeaderMenu,
                hozAlign: "left"
            },
            {
                title: "Preço",
                field: "preco",
                visible: true,
                hozAlign: "right",
                headerMenu: TableHeaderMenu,
                //@ts-ignore
                formatter: function (cell) {
                    return (new BigNumber(cell.getData().preco))
                        .toFormat(2, BigNumber.ROUND_HALF_UP);
                }
            },
            {
                title: "Qt.",
                field: "quantidade",
                visible: true,
                headerMenu: TableHeaderMenu,
                hozAlign: "right"
            },
            {
                title: "Total",
                field: "precoTotal",
                visible: true,
                headerMenu: TableHeaderMenu,
                hozAlign: "right",
                //@ts-ignore
                formatter: function (cell) {
                    return (new BigNumber(cell.getData().precoTotal))
                        .toFormat(2, BigNumber.ROUND_HALF_UP);
                }
            },
            {
                title: "B.I.",
                field: "numDocId",
                visible: false,
                headerMenu: TableHeaderMenu,
                hozAlign: "center"
            },
            {
                title: "Nome",
                field: "nome",
                visible: false,
                headerMenu: TableHeaderMenu,
                hozAlign: "center"
            },
            {
                title: "Categoria",
                field: "categoria",
                visible: true,
                headerMenu: TableHeaderMenu,
                hozAlign: "center"
            },
            {
                title: "Data exame",
                field: "dataExame",
                visible: true,
                headerMenu: TableHeaderMenu,
                hozAlign: "center"
            },
            {
                title: "Hora exame",
                field: "horaExame",
                visible: true,
                headerMenu: TableHeaderMenu,
                hozAlign: "center"
            },
            {
                title: "Marcado",
                field: "exameMarcado",
                visible: true,
                formatter: "tickCross",
                headerMenu: TableHeaderMenu,
                hozAlign: "center"
            },
            {
                title: "Resultado",
                field: "resultado",
                visible: true,
                headerMenu: TableHeaderMenu,
                hozAlign: "center",
                //@ts-ignore
                formatter: FormatterExameResultado
            },
        ];
    }
};
Facturacao.ControllerName = "Facturacao";
Facturacao._baseUrl = `/?controller=${Facturacao_1.ControllerName}`;
Facturacao.tableId = `${Facturacao_1.ControllerName}-table`;
Facturacao = Facturacao_1 = __decorate([
    injectable(),
    singleton(),
    __metadata("design:paramtypes", [])
], Facturacao);
export { Facturacao };
//# sourceMappingURL=Facturacao.js.map