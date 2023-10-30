var __decorate = (this && this.__decorate) || function (decorators, target, key, desc) {
    var c = arguments.length, r = c < 3 ? target : desc === null ? desc = Object.getOwnPropertyDescriptor(target, key) : desc, d;
    if (typeof Reflect === "object" && typeof Reflect.decorate === "function") r = Reflect.decorate(decorators, target, key, desc);
    else for (var i = decorators.length - 1; i >= 0; i--) if (d = decorators[i]) r = (c < 3 ? d(r) : c > 3 ? d(target, key, r) : d(target, key)) || r;
    return c > 3 && r && Object.defineProperty(target, key, r), r;
};
var __metadata = (this && this.__metadata) || function (k, v) {
    if (typeof Reflect === "object" && typeof Reflect.metadata === "function") return Reflect.metadata(k, v);
};
var Dashboard_1;
import { singleton, injectable } from "tsyringe";
import { FetchService } from "../../../js/FetchService";
import Chart from 'chart.js/auto';
import { Gradient } from "../../../js/GradientColor";
let Dashboard = Dashboard_1 = class Dashboard {
    static get baseUrl() {
        return Dashboard_1._baseUrl;
    }
    constructor(fetchService) {
        this.fetchService = fetchService;
        this.tabContainer = null;
        this.dashboardInvoicesTitle = null;
        this.dashboardInvoicesCanvas = null;
        this.dashboardCategoriaTitle = null;
        this.dashboardCategoriaCanvas = null;
        this.dashboardPraticosTitle = null;
        this.dashboardPraticosCanvas = null;
        this.dashboardTeoricosTitle = null;
        this.dashboardTeoricosCanvas = null;
    }
    init() {
        return new Promise(async (resolve, reject) => {
            var _a, _b, _c;
            try {
                let tabContentId = `tab-${Dashboard_1.ControllerName}`;
                this.tabContainer = document.getElementById(tabContentId);
                if (this.tabContainer === null) {
                    console.log(`Tab content id ${tabContentId} not found`);
                    resolve();
                    return;
                }
                let fetchHtmlResponse = await this.fetchService.get(`${Dashboard_1.baseUrl}&action=layout`);
                if (fetchHtmlResponse.status !== 200) {
                    return reject(await fetchHtmlResponse.text());
                }
                (_b = (_a = this.tabContainer) === null || _a === void 0 ? void 0 : _a.firstElementChild) === null || _b === void 0 ? void 0 : _b.remove();
                (_c = this.tabContainer) === null || _c === void 0 ? void 0 : _c.insertAdjacentHTML("afterbegin", await fetchHtmlResponse.text());
                this.dashboardInvoicesTitle = document.getElementById("dashboard_invoices_chart_title");
                this.dashboardInvoicesCanvas = document.getElementById("dashboard_invoices_chart");
                this.dashboardCategoriaTitle = document.getElementById("dashboard_categoria_chart_title");
                this.dashboardCategoriaCanvas = document.getElementById("dashboard_categoria_chart");
                this.dashboardPraticosTitle = document.getElementById("dashboard_praticos_chart_title");
                this.dashboardPraticosCanvas = document.getElementById("dashboard_praticos_chart");
                this.dashboardTeoricosTitle = document.getElementById("dashboard_teoricos_chart_title");
                this.dashboardTeoricosCanvas = document.getElementById("dashboard_teoricos_chart");
                this.generateDashboardInvoices().then(null).catch(null);
                this.generateDashboardCategorias().then(null).catch(null);
                this.generateDashboardResultadosPraticos().then(null).catch(null);
                this.generateDashboardResultadosTeoricos().then(null).catch(null);
                return resolve();
            }
            catch (e) {
                return reject(e);
            }
        });
    }
    generateDashboardInvoices() {
        return new Promise(async (resolve, _) => {
            let response = await this.fetchService.get(`${Dashboard_1.baseUrl}&action=Invoices`);
            let dashboardResponse = await response.json();
            let legends = dashboardResponse.data.map(d => d.legend);
            let gradiant = new Gradient();
            gradiant.setColorGradient(["#3F2CAF", "#e9446a", "#edc988", "#607D8B"]);
            gradiant.setMidpoint(legends.length);
            this.dashboardInvoicesTitle.innerText = `Faturação de ${dashboardResponse.from} a ${dashboardResponse.to}`;
            new Chart(this.dashboardInvoicesCanvas, {
                type: "bar",
                data: {
                    labels: legends,
                    datasets: [
                        {
                            label: "",
                            data: dashboardResponse.data.map(d => d.value),
                            borderWidth: 1,
                            backgroundColor: gradiant.getColors(),
                            borderColor: []
                        },
                    ],
                }
            });
            return resolve();
        });
    }
    generateDashboardCategorias() {
        return new Promise(async (resolve, _) => {
            let response = await this.fetchService.get(`${Dashboard_1.baseUrl}&action=Categoria`);
            let dashboardResponse = await response.json();
            let legends = dashboardResponse.data.map(d => d.legend);
            this.dashboardCategoriaTitle.innerText = `Exames por categoria ${dashboardResponse.from} a ${dashboardResponse.to}`;
            let gradiant = new Gradient();
            gradiant.setColorGradient(["#3F2CAF", "#e9446a", "#edc988", "#607D8B"]);
            gradiant.setMidpoint(legends.length);
            new Chart(this.dashboardCategoriaCanvas, {
                type: "bar",
                data: {
                    labels: legends,
                    datasets: [
                        {
                            label: "",
                            data: dashboardResponse.data.map(d => d.value),
                            backgroundColor: gradiant.getColors()
                        }
                    ],
                }
            });
            return resolve();
        });
    }
    generateDashboardResultadosPraticos() {
        return new Promise(async (resolve, _) => {
            let response = await this.fetchService.get(`${Dashboard_1.baseUrl}&action=ResultadoPratico`);
            let dashboardResponse = await response.json();
            let legends = dashboardResponse.data.map(d => d.legend);
            this.dashboardPraticosTitle.innerText = `Resultados práticos de ${dashboardResponse.from} a ${dashboardResponse.to}`;
            let gradiant = new Gradient();
            gradiant.setColorGradient(["#3F2CAF", "#e9446a", "#edc988", "#607D8B"]);
            gradiant.setMidpoint(legends.length);
            new Chart(this.dashboardPraticosCanvas, {
                type: "bar",
                data: {
                    labels: legends,
                    datasets: [
                        {
                            label: "",
                            data: dashboardResponse.data.map(d => d.value),
                            backgroundColor: gradiant.getColors()
                        }
                    ],
                }
            });
            return resolve();
        });
    }
    generateDashboardResultadosTeoricos() {
        return new Promise(async (resolve, _) => {
            let response = await this.fetchService.get(`${Dashboard_1.baseUrl}&action=ResultadoTeorico`);
            let dashboardResponse = await response.json();
            let legends = dashboardResponse.data.map(d => d.legend);
            this.dashboardTeoricosTitle.innerText = `Resultados teóricos de ${dashboardResponse.from} a ${dashboardResponse.to}`;
            let gradiant = new Gradient();
            gradiant.setColorGradient(["#3F2CAF", "#e9446a", "#edc988", "#607D8B"]);
            gradiant.setMidpoint(legends.length);
            new Chart(this.dashboardTeoricosCanvas, {
                type: "bar",
                data: {
                    labels: legends,
                    datasets: [
                        {
                            label: "",
                            data: dashboardResponse.data.map(d => d.value),
                            backgroundColor: gradiant.getColors()
                        }
                    ],
                }
            });
            return resolve();
        });
    }
};
Dashboard.ControllerName = "Dashboard";
Dashboard._baseUrl = `/?controller=${Dashboard_1.ControllerName}`;
Dashboard.tableId = `${Dashboard_1.ControllerName}-table`;
Dashboard = Dashboard_1 = __decorate([
    injectable(),
    singleton(),
    __metadata("design:paramtypes", [FetchService])
], Dashboard);
export { Dashboard };
//# sourceMappingURL=Dashboard.js.map