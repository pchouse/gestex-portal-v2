var __decorate = (this && this.__decorate) || function (decorators, target, key, desc) {
    var c = arguments.length, r = c < 3 ? target : desc === null ? desc = Object.getOwnPropertyDescriptor(target, key) : desc, d;
    if (typeof Reflect === "object" && typeof Reflect.decorate === "function") r = Reflect.decorate(decorators, target, key, desc);
    else for (var i = decorators.length - 1; i >= 0; i--) if (d = decorators[i]) r = (c < 3 ? d(r) : c > 3 ? d(target, key, r) : d(target, key)) || r;
    return c > 3 && r && Object.defineProperty(target, key, r), r;
};
var __metadata = (this && this.__metadata) || function (k, v) {
    if (typeof Reflect === "object" && typeof Reflect.metadata === "function") return Reflect.metadata(k, v);
};
var Candidato_1;
import { singleton, injectable } from "tsyringe";
import { TabulatorFull as Tabulator } from 'tabulator-tables';
import { TableHeaderMenu } from "../../../js/TableHeaderMenu";
import { FormatterExameResultado } from "../../../js/FormatterExameResultado";
import { Util } from "../../../js/Util";
import { Exame } from "../../Exame/js/Exame";
import { Licenca } from "../../Licenca/js/Licenca";
let Candidato = Candidato_1 = class Candidato {
    static get baseUrl() {
        return Candidato_1._baseUrl;
    }
    constructor() {
        this.tabContainer = null;
        this.tableContainer = null;
        this.licencaTableContainer = null;
        this.exameTableContainer = null;
        this.table = null;
        this.licencaTable = null;
        this.exameTable = null;
        this.licencaTableUrlIsSet = false;
        this.exameTableUrlIsSet = false;
    }
    init() {
        return new Promise(async (resolve, reject) => {
            var _a, _b, _c;
            try {
                let tabContentId = `tab-${Candidato_1.ControllerName}`;
                this.tabContainer = document.getElementById(tabContentId);
                if (this.tabContainer === null) {
                    console.log(`Tab content id ${tabContentId} not found`);
                    resolve();
                    return;
                }
                this.tableContainer = document.createElement("div");
                this.tableContainer.id = Candidato_1.tableId;
                (_a = this.tabContainer) === null || _a === void 0 ? void 0 : _a.appendChild(this.tableContainer);
                let leftContainer = document.createElement("div");
                leftContainer.style.width = `${window.innerWidth / 3}px`;
                leftContainer.style.float = "left";
                this.licencaTableContainer = document.createElement("div");
                this.licencaTableContainer.id = `${Candidato_1.tableId}-licenca`;
                this.licencaTableContainer.style.marginTop = "19px";
                leftContainer.appendChild(this.licencaTableContainer);
                (_b = this.tabContainer) === null || _b === void 0 ? void 0 : _b.appendChild(leftContainer);
                let rightContainer = document.createElement("div");
                rightContainer.style.width = `${((window.innerWidth / 3) * 2) - 100}px`;
                rightContainer.style.float = `right`;
                this.exameTableContainer = document.createElement("div");
                this.exameTableContainer.id = `${Candidato_1.tableId}-exame`;
                this.exameTableContainer.style.marginTop = "19px";
                rightContainer.appendChild(this.exameTableContainer);
                (_c = this.tabContainer) === null || _c === void 0 ? void 0 : _c.appendChild(rightContainer);
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
            var _a, _b, _c, _d;
            try {
                this.table = new Tabulator((_a = this.tableContainer) !== null && _a !== void 0 ? _a : "", {
                    index: "idCandidato",
                    placeholder: "Sem registos",
                    height: (window.innerHeight - 149) / 2,
                    renderHorizontal: "virtual",
                    layout: "fitColumns",
                    responsiveLayout: "hide",
                    progressiveLoad: "scroll",
                    sortMode: "remote",
                    filterMode: "remote",
                    paginationSize: 49,
                    ajaxURL: `${Candidato_1._baseUrl}&action=dataGrid`,
                    ajaxConfig: "POST",
                    ajaxContentType: "json",
                    columns: this.columnDefenitions(),
                    validationMode: "highlight",
                    initialSort: [{ column: "name", dir: "asc" }],
                });
                this.table.on("dataProcessed", async () => {
                    var _a, _b;
                    if (this.table.getSelectedRows.length === 0) {
                        (_a = this.licencaTable) === null || _a === void 0 ? void 0 : _a.clearData();
                        (_b = this.exameTable) === null || _b === void 0 ? void 0 : _b.clearData();
                    }
                });
                this.table.on("rowClick", async (_, row) => {
                    var _a, _b, _c, _d, _e, _f;
                    let filter = {
                        field: "numDocId",
                        type: "=",
                        value: row.getData().numDocId
                    };
                    (_a = this.licencaTable) === null || _a === void 0 ? void 0 : _a.clearData();
                    (_b = this.licencaTable) === null || _b === void 0 ? void 0 : _b.setFilter([filter]);
                    if (!this.licencaTableUrlIsSet) {
                        (_c = this.licencaTable) === null || _c === void 0 ? void 0 : _c.setData(`${Licenca.baseUrl}&action=dataGrid`);
                        this.licencaTableUrlIsSet = true;
                    }
                    (_d = this.exameTable) === null || _d === void 0 ? void 0 : _d.clearData();
                    (_e = this.exameTable) === null || _e === void 0 ? void 0 : _e.setFilter([filter]);
                    if (!this.exameTableUrlIsSet) {
                        (_f = this.exameTable) === null || _f === void 0 ? void 0 : _f.setData(`${Exame.baseUrl}&action=dataGrid`);
                        this.exameTableUrlIsSet = true;
                    }
                    row.getTable().getSelectedRows().forEach((s) => s.deselect());
                    row.select();
                });
                this.licencaTable = new Tabulator((_b = this.licencaTableContainer) !== null && _b !== void 0 ? _b : "", {
                    index: "numLic",
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
                    initialSort: [{ column: "validadeLic", dir: "asc" }],
                    columns: this.licencaColumnDefenitions(),
                });
                this.exameTable = new Tabulator((_c = this.exameTableContainer) !== null && _c !== void 0 ? _c : "", {
                    index: "doc",
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
                    initialSort: [{ column: "dataExame", dir: "desc" }],
                    columns: this.exameColumnDefenitions(),
                });
                (_d = this.tabContainer) === null || _d === void 0 ? void 0 : _d.removeChild(this.tabContainer.firstElementChild);
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
                title: "ID",
                field: "idCandidato",
                headerFilter: "input",
                headerMenu: TableHeaderMenu,
                visible: false,
                hozAlign: "center"
            },
            {
                title: "Alvará",
                field: "alvara",
                headerFilter: "input",
                headerMenu: TableHeaderMenu,
                visible: false,
                hozAlign: "center"
            },
            {
                title: "B.I.",
                field: "numDocId",
                headerFilter: "input",
                headerMenu: TableHeaderMenu,
                visible: true,
                hozAlign: "center",
                width: 179,
            },
            {
                title: "B.I. validade",
                field: "docValidade",
                headerFilter: "input",
                headerMenu: TableHeaderMenu,
                visible: true,
                hozAlign: "center",
                width: 179,
                //@ts-ignore
                formatter: function (cell) {
                    let dateString = cell.getData().docValidade;
                    return `<span class="${Util.isEmpty(dateString) ? 'text-danger' : 'text-success'}">${dateString}</span>`;
                }
            },
            {
                title: "Nome",
                field: "name",
                headerFilter: "input",
                headerMenu: TableHeaderMenu,
                visible: true,
                hozAlign: "left"
            },
            {
                title: "NIF",
                field: "contribuinte",
                headerFilter: "input",
                headerMenu: TableHeaderMenu,
                visible: true,
                hozAlign: "center",
                width: 179,
            },
            {
                title: "Data Nascimento",
                field: "dataNascimento",
                headerFilter: "input",
                headerMenu: TableHeaderMenu,
                visible: true,
                hozAlign: "center",
                width: 179,
            },
            {
                title: "Nº aluno",
                field: "numAluno",
                width: 99,
                headerFilter: "input",
                headerMenu: TableHeaderMenu,
                visible: true,
                hozAlign: "center",
            },
            {
                title: "Validade teórico",
                field: "validadeTeorico",
                headerFilter: "input",
                headerMenu: TableHeaderMenu,
                visible: true,
                hozAlign: "center",
                width: 179,
                //@ts-ignore
                formatter: function (cell) {
                    let dateString = cell.getData().validadeTeorico;
                    return `<span class="${Util.isEmpty(dateString) ? 'text-danger' : 'text-success'}">${dateString !== null && dateString !== void 0 ? dateString : ""}</span>`;
                }
            },
        ];
    }
    licencaColumnDefenitions() {
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
                visible: false,
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
    exameColumnDefenitions() {
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
                title: "Nº aluno",
                field: "numAluno",
                visible: false,
                headerFilter: "input",
                headerMenu: TableHeaderMenu,
                hozAlign: "center"
            },
            {
                title: "B.I.",
                field: "numDocId",
                visible: false,
                headerFilter: "input",
                headerMenu: TableHeaderMenu,
                hozAlign: "center"
            },
            {
                title: "Nome",
                field: "nome",
                visible: false,
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
                visible: true,
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
Candidato.ControllerName = "Candidato";
Candidato._baseUrl = `/?controller=${Candidato_1.ControllerName}`;
Candidato.tableId = `${Candidato_1.ControllerName}-table`;
Candidato = Candidato_1 = __decorate([
    injectable(),
    singleton(),
    __metadata("design:paramtypes", [])
], Candidato);
export { Candidato };
//# sourceMappingURL=Candidato.js.map