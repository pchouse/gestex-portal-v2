var __decorate = (this && this.__decorate) || function (decorators, target, key, desc) {
    var c = arguments.length, r = c < 3 ? target : desc === null ? desc = Object.getOwnPropertyDescriptor(target, key) : desc, d;
    if (typeof Reflect === "object" && typeof Reflect.decorate === "function") r = Reflect.decorate(decorators, target, key, desc);
    else for (var i = decorators.length - 1; i >= 0; i--) if (d = decorators[i]) r = (c < 3 ? d(r) : c > 3 ? d(target, key, r) : d(target, key)) || r;
    return c > 3 && r && Object.defineProperty(target, key, r), r;
};
var __metadata = (this && this.__metadata) || function (k, v) {
    if (typeof Reflect === "object" && typeof Reflect.metadata === "function") return Reflect.metadata(k, v);
};
var FacturacaoDetalhe_1;
import { singleton, injectable } from "tsyringe";
import { TabulatorFull as Tabulator } from 'tabulator-tables';
import { TableHeaderMenu } from "../../../js/TableHeaderMenu";
import { BigNumber } from "bignumber.js";
import { FormatterExameResultado } from "../../../js/FormatterExameResultado";
let FacturacaoDetalhe = FacturacaoDetalhe_1 = class FacturacaoDetalhe {
    static get baseUrl() {
        return FacturacaoDetalhe_1._baseUrl;
    }
    constructor() {
        this.tabContainer = null;
        this.tableContainer = null;
        this.table = null;
        this.childTable = null;
    }
    init() {
        return new Promise(async (resolve, reject) => {
            var _a;
            try {
                let tabContentId = `tab-${FacturacaoDetalhe_1.ControllerName}`;
                this.tabContainer = document.getElementById(tabContentId);
                if (this.tabContainer === null) {
                    console.log(`Tab content id ${tabContentId} not found`);
                    resolve();
                    return;
                }
                this.tableContainer = document.createElement("div");
                this.tableContainer.id = FacturacaoDetalhe_1.tableId;
                (_a = this.tabContainer) === null || _a === void 0 ? void 0 : _a.appendChild(this.tableContainer);
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
            var _a, _b;
            try {
                this.childTable = new Tabulator((_a = this.tableContainer) !== null && _a !== void 0 ? _a : "", {
                    index: "numLinha",
                    placeholder: "Sem registos",
                    height: window.innerHeight - 149,
                    layout: "fitColumns",
                    ajaxURL: `${FacturacaoDetalhe_1._baseUrl}&action=dataGrid`,
                    ajaxConfig: "POST",
                    ajaxContentType: "json",
                    renderHorizontal: "virtual",
                    responsiveLayout: "hide",
                    progressiveLoad: "scroll",
                    sortMode: "remote",
                    filterMode: "remote",
                    paginationSize: 49,
                    validationMode: "highlight",
                    initialSort: [
                        { column: "doc", dir: "asc" },
                        { column: "numLinha", dir: "asc" }
                    ],
                    columns: this.columnDefenitions(),
                });
                (_b = this.tabContainer) === null || _b === void 0 ? void 0 : _b.removeChild(this.tabContainer.firstElementChild);
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
                hozAlign: "center"
            },
            {
                title: "Nº linha",
                field: "numLinha",
                visible: true,
                headerFilter: "input",
                headerMenu: TableHeaderMenu,
                hozAlign: "center"
            },
            {
                title: "Artigo",
                field: "artigo",
                visible: true,
                headerFilter: "input",
                headerMenu: TableHeaderMenu,
                hozAlign: "left"
            },
            {
                title: "Descrição",
                field: "descricao",
                visible: true,
                headerFilter: "input",
                headerMenu: TableHeaderMenu,
                hozAlign: "left"
            },
            {
                title: "Preço",
                field: "preco",
                visible: true,
                hozAlign: "right",
                headerFilter: "input",
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
                headerFilter: "input",
                headerMenu: TableHeaderMenu,
                hozAlign: "right"
            },
            {
                title: "Total",
                field: "precoTotal",
                visible: true,
                headerMenu: TableHeaderMenu,
                headerFilter: "input",
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
                visible: true,
                headerMenu: TableHeaderMenu,
                headerFilter: "input",
                hozAlign: "center"
            },
            {
                title: "Nome",
                field: "nome",
                visible: true,
                headerFilter: "input",
                headerMenu: TableHeaderMenu,
                hozAlign: "center"
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
                title: "Data exame",
                field: "dataExame",
                visible: true,
                headerFilter: "input",
                headerMenu: TableHeaderMenu,
                hozAlign: "center"
            },
            {
                title: "Hora exame",
                field: "horaExame",
                visible: true,
                headerFilter: "input",
                headerMenu: TableHeaderMenu,
                hozAlign: "center"
            },
            {
                title: "Marcado",
                field: "exameMarcado",
                visible: true,
                formatter: "tickCross",
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
        ];
    }
};
FacturacaoDetalhe.ControllerName = "FacturacaoDetalhe";
FacturacaoDetalhe._baseUrl = `/?controller=${FacturacaoDetalhe_1.ControllerName}`;
FacturacaoDetalhe.tableId = `${FacturacaoDetalhe_1.ControllerName}-table`;
FacturacaoDetalhe = FacturacaoDetalhe_1 = __decorate([
    injectable(),
    singleton(),
    __metadata("design:paramtypes", [])
], FacturacaoDetalhe);
export { FacturacaoDetalhe };
//# sourceMappingURL=FacturacaoDetalhe.js.map