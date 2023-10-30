import {container, singleton, injectable} from "tsyringe";
import * as bootstrap from "bootstrap";
import {Util} from "../../../js/Util";
import {EscolaForm} from "../../Escola/js/EscolaForm";
import {Facturacao} from "../../Facturacao/js/Facturacao";
import {FacturacaoDetalhe} from "../../FacturacaoDetalhe/js/FacturacaoDetalhe";
import {Candidato} from "../../Candidato/js/Candidato";
import {Exame} from "../../Exame/js/Exame";
import {Licenca} from "../../Licenca/js/Licenca";
import {Dashboard} from "../../Dashboard/js/Dashboard";

@injectable()
@singleton()
export class Start {

    protected tabName: Map<string, string> = new Map();

    constructor() {
        this.tabName.set("Dashboard", "Dashboard");
        this.tabName.set("Escola", "Escola");
        this.tabName.set("Facturacao", "Facturação");
        this.tabName.set("FacturacaoDetalhe", "Facturação detalhe");
        this.tabName.set("Candidato", "Candidatos");
        this.tabName.set("Licenca", "Licenças");
        this.tabName.set("Exame", "Exames");
        this.tabName.set("ExameAll", "Todos exames");

        container.resolve(EscolaForm).loadSchool().then((escola) => {
            let escolaInfo = document.getElementById("main-escola-info") as HTMLDivElement;
            escolaInfo.innerText = `${escola?.alvara} - ${escola?.nome}, ${escola?.contribuinte}`;
        }).catch(null);

    }

    openTab(controller: string): Promise<void> {

        return new Promise<void>((resolve, reject) => {

            try {
                Util.afterCloseModel();

                let $tab = document.getElementById(`tab-link-${controller}`);

                if ($tab !== null) {
                    // noinspection all
                    bootstrap.Tab.getOrCreateInstance($tab)?.show();
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
                bootstrap.Tab.getOrCreateInstance(`#tab-link-${controller}`)?.show();

                document.getElementById(`tabCloseBtn-${controller}`)?.addEventListener("click", () => {
                    let $tabContent = document.getElementById(`tab-${controller}`)
                    let $tabNav = document.getElementById(`tab-link-${controller}`);
                    let firstTab = document.querySelector('#mainTab li:first-child a')

                    $tabContent?.remove();
                    $tabNav?.parentElement?.remove();
                    if (firstTab !== null) {
                        // noinspection all
                        bootstrap.Tab.getInstance(firstTab)?.show()
                    }
                });

                this.initJs(controller).then(() => {
                    resolve()
                }).catch((e) => {
                    reject(e)
                });

            } catch (e) {
                reject(e);
            }
        });
    }

    private async initJs(controllerName: string) {

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

}
