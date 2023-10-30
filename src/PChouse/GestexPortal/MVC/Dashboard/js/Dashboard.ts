import {singleton, injectable} from "tsyringe";
import {FetchService} from "../../../js/FetchService";
import {TDashboardResponse} from "./TDashboardResponse";
import Chart from 'chart.js/auto';
import {Gradient} from "../../../js/GradientColor";

@injectable()
@singleton()
export class Dashboard {

    public static get baseUrl(): string {
        return Dashboard._baseUrl;
    }

    public static readonly ControllerName = "Dashboard";

    private static _baseUrl = `/?controller=${Dashboard.ControllerName}`;

    public static readonly tableId = `${Dashboard.ControllerName}-table`;

    public tabContainer: HTMLElement | null = null;

    public dashboardInvoicesTitle: HTMLDivElement | null = null;
    public dashboardInvoicesCanvas: HTMLCanvasElement | null = null;

    public dashboardCategoriaTitle: HTMLDivElement | null = null;
    public dashboardCategoriaCanvas: HTMLCanvasElement | null = null;

    public dashboardPraticosTitle: HTMLDivElement | null = null;
    public dashboardPraticosCanvas: HTMLCanvasElement | null = null;

    public dashboardTeoricosTitle: HTMLDivElement | null = null;
    public dashboardTeoricosCanvas: HTMLCanvasElement | null = null;

    constructor(protected fetchService: FetchService) {

    }

    init(): Promise<void> {
        return new Promise<void>(async (resolve, reject) => {
            try {

                let tabContentId = `tab-${Dashboard.ControllerName}`;

                this.tabContainer = document.getElementById(tabContentId);

                if (this.tabContainer === null) {
                    console.log(`Tab content id ${tabContentId} not found`);
                    resolve();
                    return;
                }

                let fetchHtmlResponse = await this.fetchService.get(`${Dashboard.baseUrl}&action=layout`);

                if (fetchHtmlResponse.status !== 200) {
                    return reject(await fetchHtmlResponse.text());
                }

                this.tabContainer?.firstElementChild?.remove();

                this.tabContainer?.insertAdjacentHTML("afterbegin", await fetchHtmlResponse.text())

                this.dashboardInvoicesTitle = document.getElementById("dashboard_invoices_chart_title") as HTMLDivElement | null;
                this.dashboardInvoicesCanvas = document.getElementById("dashboard_invoices_chart") as HTMLCanvasElement | null;
                this.dashboardCategoriaTitle = document.getElementById("dashboard_categoria_chart_title") as HTMLDivElement | null;
                this.dashboardCategoriaCanvas = document.getElementById("dashboard_categoria_chart") as HTMLCanvasElement | null;
                this.dashboardPraticosTitle = document.getElementById("dashboard_praticos_chart_title") as HTMLDivElement | null;
                this.dashboardPraticosCanvas = document.getElementById("dashboard_praticos_chart") as HTMLCanvasElement | null;
                this.dashboardTeoricosTitle = document.getElementById("dashboard_teoricos_chart_title") as HTMLDivElement | null;
                this.dashboardTeoricosCanvas = document.getElementById("dashboard_teoricos_chart") as HTMLCanvasElement | null;

                this.generateDashboardInvoices().then(null).catch(null);
                this.generateDashboardCategorias().then(null).catch(null);
                this.generateDashboardResultadosPraticos().then(null).catch(null);
                this.generateDashboardResultadosTeoricos().then(null).catch(null);

                return resolve();
            } catch (e) {
                return reject(e);
            }
        });
    }

    public generateDashboardInvoices(): Promise<void> {
        return new Promise(async (resolve, _) => {

            let response = await this.fetchService.get(
                `${Dashboard.baseUrl}&action=Invoices`
            );

            let dashboardResponse = await response.json() as TDashboardResponse;

            let legends = dashboardResponse.data.map(d => d.legend)

            let gradiant = new Gradient();
            gradiant.setColorGradient(["#3F2CAF", "#e9446a", "#edc988", "#607D8B"]);
            gradiant.setMidpoint(legends.length);

            this.dashboardInvoicesTitle!.innerText = `Faturação de ${dashboardResponse.from} a ${dashboardResponse.to}`;

            new Chart(this.dashboardInvoicesCanvas!, {
                type: "bar",
                data: {
                    labels  : legends,
                    datasets: [
                        {
                            label          : "",
                            data           : dashboardResponse.data.map(d => d.value),
                            borderWidth    : 1,
                            backgroundColor: gradiant.getColors(),
                            borderColor    : []
                        },
                    ],
                }
            });

            return resolve();
        });
    }

    public generateDashboardCategorias(): Promise<void> {
        return new Promise(async (resolve, _) => {

            let response = await this.fetchService.get(
                `${Dashboard.baseUrl}&action=Categoria`
            );

            let dashboardResponse = await response.json() as TDashboardResponse;

            let legends = dashboardResponse.data.map(d => d.legend);

            this.dashboardCategoriaTitle!.innerText = `Exames por categoria ${dashboardResponse.from} a ${dashboardResponse.to}`;

            let gradiant = new Gradient();
            gradiant.setColorGradient(["#3F2CAF", "#e9446a", "#edc988", "#607D8B"]);
            gradiant.setMidpoint(legends.length);

            new Chart(this.dashboardCategoriaCanvas!, {
                type: "bar",
                data: {
                    labels  : legends,
                    datasets: [
                        {
                            label          : "",
                            data           : dashboardResponse.data.map(d => d.value),
                            backgroundColor: gradiant.getColors()
                        }
                    ],

                }
            });

            return resolve();
        });
    }

    public generateDashboardResultadosPraticos(): Promise<void> {
        return new Promise(async (resolve, _) => {

            let response = await this.fetchService.get(
                `${Dashboard.baseUrl}&action=ResultadoPratico`
            );

            let dashboardResponse = await response.json() as TDashboardResponse;

            let legends = dashboardResponse.data.map(d => d.legend);

            this.dashboardPraticosTitle!.innerText = `Resultados práticos de ${dashboardResponse.from} a ${dashboardResponse.to}`;

            let gradiant = new Gradient();
            gradiant.setColorGradient(["#3F2CAF", "#e9446a", "#edc988", "#607D8B"]);
            gradiant.setMidpoint(legends.length);

            new Chart(this.dashboardPraticosCanvas!, {
                type: "bar",
                data: {
                    labels  : legends,
                    datasets: [
                        {
                            label          : "",
                            data           : dashboardResponse.data.map(d => d.value),
                            backgroundColor: gradiant.getColors()
                        }
                    ],

                }
            });

            return resolve();
        });
    }


    public generateDashboardResultadosTeoricos(): Promise<void> {
        return new Promise(async (resolve, _) => {

            let response = await this.fetchService.get(
                `${Dashboard.baseUrl}&action=ResultadoTeorico`
            );

            let dashboardResponse = await response.json() as TDashboardResponse;

            let legends = dashboardResponse.data.map(d => d.legend);

            this.dashboardTeoricosTitle!.innerText = `Resultados teóricos de ${dashboardResponse.from} a ${dashboardResponse.to}`;

            let gradiant = new Gradient();
            gradiant.setColorGradient(["#3F2CAF", "#e9446a", "#edc988", "#607D8B"]);
            gradiant.setMidpoint(legends.length);

            new Chart(this.dashboardTeoricosCanvas!, {
                type: "bar",
                data: {
                    labels  : legends,
                    datasets: [
                        {
                            label          : "",
                            data           : dashboardResponse.data.map(d => d.value),
                            backgroundColor: gradiant.getColors()
                        }
                    ],

                }
            });

            return resolve();
        });
    }

}
