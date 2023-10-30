var __decorate = (this && this.__decorate) || function (decorators, target, key, desc) {
    var c = arguments.length, r = c < 3 ? target : desc === null ? desc = Object.getOwnPropertyDescriptor(target, key) : desc, d;
    if (typeof Reflect === "object" && typeof Reflect.decorate === "function") r = Reflect.decorate(decorators, target, key, desc);
    else for (var i = decorators.length - 1; i >= 0; i--) if (d = decorators[i]) r = (c < 3 ? d(r) : c > 3 ? d(target, key, r) : d(target, key)) || r;
    return c > 3 && r && Object.defineProperty(target, key, r), r;
};
var __metadata = (this && this.__metadata) || function (k, v) {
    if (typeof Reflect === "object" && typeof Reflect.metadata === "function") return Reflect.metadata(k, v);
};
var Licenca_1;
import { singleton, injectable } from "tsyringe";
import { TabulatorFull as Tabulator } from 'tabulator-tables';
import { TableHeaderMenu } from "../../../js/TableHeaderMenu";
import { Util } from "../../../js/Util";
let Licenca = Licenca_1 = class Licenca {
    static get baseUrl() {
        return Licenca_1._baseUrl;
    }
    constructor() {
        this.tabContainer = null;
        this.tableContainer = null;
        this.table = null;
    }
    init() {
        return new Promise(async (resolve, reject) => {
            var _a;
            try {
                let tabContentId = `tab-${Licenca_1.ControllerName}`;
                this.tabContainer = document.getElementById(tabContentId);
                if (this.tabContainer === null) {
                    console.log(`Tab content id ${tabContentId} not found`);
                    resolve();
                    return;
                }
                this.tableContainer = document.createElement("div");
                this.tableContainer.id = Licenca_1.tableId;
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
                this.table = new Tabulator((_a = this.tabContainer) !== null && _a !== void 0 ? _a : "", {
                    index: "numLic",
                    placeholder: "Sem registos",
                    height: (window.innerHeight - 149),
                    layout: "fitColumns",
                    ajaxURL: `${Licenca_1.baseUrl}&action=dataGrid`,
                    ajaxConfig: "POST",
                    ajaxContentType: "json",
                    renderHorizontal: "virtual",
                    responsiveLayout: "hide",
                    progressiveLoad: "scroll",
                    sortMode: "remote",
                    filterMode: "remote",
                    paginationSize: 49,
                    validationMode: "highlight",
                    initialSort: [{ column: "validadeLic", dir: "desc" }],
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
                title: "Tipo exame",
                field: "descricao",
                visible: true,
                headerFilter: "input",
                headerMenu: TableHeaderMenu,
                hozAlign: "left"
            },
            {
                title: "Licença",
                field: "numLic",
                visible: true,
                headerFilter: "input",
                headerMenu: TableHeaderMenu,
                hozAlign: "center"
            },
            {
                title: "Validade licença",
                field: "validadeLic",
                headerFilter: "input",
                headerMenu: TableHeaderMenu,
                visible: true,
                hozAlign: "center",
                //@ts-ignore
                formatter: function (cell) {
                    let dateString = cell.getData().validadeLic;
                    return `<span class="${Util.isEmpty(dateString) ? 'text-danger' : 'text-success'}">${dateString}</span>`;
                }
            }
        ];
    }
};
Licenca.ControllerName = "Licenca";
Licenca._baseUrl = `/?controller=${Licenca_1.ControllerName}`;
Licenca.tableId = `${Licenca_1.ControllerName}-table`;
Licenca = Licenca_1 = __decorate([
    injectable(),
    singleton(),
    __metadata("design:paramtypes", [])
], Licenca);
export { Licenca };
//# sourceMappingURL=Licenca.js.map