var __decorate = (this && this.__decorate) || function (decorators, target, key, desc) {
    var c = arguments.length, r = c < 3 ? target : desc === null ? desc = Object.getOwnPropertyDescriptor(target, key) : desc, d;
    if (typeof Reflect === "object" && typeof Reflect.decorate === "function") r = Reflect.decorate(decorators, target, key, desc);
    else for (var i = decorators.length - 1; i >= 0; i--) if (d = decorators[i]) r = (c < 3 ? d(r) : c > 3 ? d(target, key, r) : d(target, key)) || r;
    return c > 3 && r && Object.defineProperty(target, key, r), r;
};
var __metadata = (this && this.__metadata) || function (k, v) {
    if (typeof Reflect === "object" && typeof Reflect.metadata === "function") return Reflect.metadata(k, v);
};
import { container, singleton, injectable } from "tsyringe";
import * as bootstrap from "bootstrap";
import { Util } from "../../../js/Util";
import { EscolaForm } from "../../Escola/js/EscolaForm";
import { Facturacao } from "../../Facturacao/js/Facturacao";
import { FacturacaoDetalhe } from "../../FacturacaoDetalhe/js/FacturacaoDetalhe";
import { Candidato } from "../../Candidato/js/Candidato";
import { Exame } from "../../Exame/js/Exame";
import { Licenca } from "../../Licenca/js/Licenca";
import { Dashboard } from "../../Dashboard/js/Dashboard";
let Start = class Start {
    constructor() {
        this.tabName = new Map();
        this.tabName.set("Dashboard", "Dashboard");
        this.tabName.set("Escola", "Escola");
        this.tabName.set("Facturacao", "Facturação");
        this.tabName.set("FacturacaoDetalhe", "Facturação detalhe");
        this.tabName.set("Candidato", "Candidatos");
        this.tabName.set("Licenca", "Licenças");
        this.tabName.set("Exame", "Exames");
        this.tabName.set("ExameAll", "Todos exames");
        container.resolve(EscolaForm).loadSchool().then((escola) => {
            let escolaInfo = document.getElementById("main-escola-info");
            escolaInfo.innerText = `${escola === null || escola === void 0 ? void 0 : escola.alvara} - ${escola === null || escola === void 0 ? void 0 : escola.nome}, ${escola === null || escola === void 0 ? void 0 : escola.contribuinte}`;
        }).catch(null);
    }
    openTab(controller) {
        return new Promise((resolve, reject) => {
            var _a, _b, _c;
            try {
                Util.afterCloseModel();
                let $tab = document.getElementById(`tab-link-${controller}`);
                if ($tab !== null) {
                    // noinspection all
                    (_a = bootstrap.Tab.getOrCreateInstance($tab)) === null || _a === void 0 ? void 0 : _a.show();
                    return resolve();
                }
                let $mainTab = document.getElementById("mainTab");
                let $mainTabContent = document.getElementById("mainTabContent");
                let newTabHtml = `<li class="nav-item" role="presentation">
                                    <a class="nav-link" 
                                    id="tab-link-${controller}" 
                                    data-bs-toggle="tab" 
                                    data-bs-target="#tab-${controller}"
                                    type="button" 
                                    role="tab" 
                                    aria-controls="home-tab-pane" 
                                    aria-selected="true">${this.tabName.get(controller)}    
                                        <button  class="btn btn-light tab-close-btn" id="tabCloseBtn-${controller}">x</button>
                                    </a>
                                </li>`;
                let newTabContentHtml = `<div class="tab-pane fade" 
                                            style="padding: 9px;" 
                                            id="tab-${controller}" 
                                            role="tabpanel" aria-labelledby="tab-link-${controller}">
                                <div class="text-center" style="padding-top: 99px;">
                                    <div class="spinner-border text-success" style="width: 9rem; height: 9rem;" role="status">
                                        <span class="visually-hidden">A carregar...</span>
                                    </div>
                                </div>
                            </div>`;
                if ($mainTab !== null) {
                    $mainTab.insertAdjacentHTML("beforeend", newTabHtml);
                }
                if ($mainTabContent !== null) {
                    $mainTabContent.insertAdjacentHTML("beforeend", newTabContentHtml);
                }
                // noinspection all
                (_b = bootstrap.Tab.getOrCreateInstance(`#tab-link-${controller}`)) === null || _b === void 0 ? void 0 : _b.show();
                (_c = document.getElementById(`tabCloseBtn-${controller}`)) === null || _c === void 0 ? void 0 : _c.addEventListener("click", () => {
                    var _a, _b;
                    let $tabContent = document.getElementById(`tab-${controller}`);
                    let $tabNav = document.getElementById(`tab-link-${controller}`);
                    let firstTab = document.querySelector('#mainTab li:first-child a');
                    $tabContent === null || $tabContent === void 0 ? void 0 : $tabContent.remove();
                    (_a = $tabNav === null || $tabNav === void 0 ? void 0 : $tabNav.parentElement) === null || _a === void 0 ? void 0 : _a.remove();
                    if (firstTab !== null) {
                        // noinspection all
                        (_b = bootstrap.Tab.getInstance(firstTab)) === null || _b === void 0 ? void 0 : _b.show();
                    }
                });
                this.initJs(controller).then(() => {
                    resolve();
                }).catch((e) => {
                    reject(e);
                });
            }
            catch (e) {
                reject(e);
            }
        });
    }
    async initJs(controllerName) {
        switch (controllerName) {
            case "Dashboard":
                await container.resolve(Dashboard).init();
                break;
            case "Escola":
                await container.resolve(EscolaForm).openForm();
                break;
            case "Facturacao":
                await container.resolve(Facturacao).init();
                break;
            case "FacturacaoDetalhe":
                await container.resolve(FacturacaoDetalhe).init();
                break;
            case "Candidato":
                await container.resolve(Candidato).init();
                break;
            case "Exame":
                await container.resolve(Exame).init();
                break;
            case "ExameAll":
                await container.resolve(Exame).initAll();
                break;
            case "Licenca":
                await container.resolve(Licenca).init();
                break;
        }
    }
};
Start = __decorate([
    injectable(),
    singleton(),
    __metadata("design:paramtypes", [])
], Start);
export { Start };
//# sourceMappingURL=Start.js.map