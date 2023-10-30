import "reflect-metadata";
import 'bootstrap/dist/css/bootstrap.css';
import 'bootstrap-icons/font/bootstrap-icons.css';
import "tabulator-tables/dist/css/tabulator_bootstrap5.css";
import "font-awesome/css/font-awesome.css"
import * as bootstrap from 'bootstrap'
import * as luxon from "luxon";

window.luxon = luxon;
import {Start} from "../MVC/Start/js/Start";
import {Login} from "../MVC/Login/js/Login";
import {Util} from "./Util"
import {OnKeyPressEventManager} from "./OnKeyPressEventManager";
import {container} from "tsyringe";
import {CursorFormFlow} from "./CursorFormFlow";
import {ProgressBar} from "./ProgressBar";

window.addEventListener(
    "load", async () => {

        let $logout = document.getElementById("logout");
        let $menuStack = document.getElementById("offcanvasListContainer")?.querySelectorAll(
            "[data-gx-menu-controller]"
        );

        Util.toastBody = document.getElementById("liveToastBody") as HTMLDivElement | null;
        // noinspection all
        Util.toast = bootstrap.Toast.getOrCreateInstance(
            document.getElementById("liveToast") as HTMLElement
        );

        $menuStack?.forEach((element: Element) => {

            element.addEventListener("click", () => {

                let controller = element.getAttribute("data-gx-menu-controller") ?? "";

                if (Util.isEmpty(controller)) return;

                container.resolve(Start).openTab(controller).catch((e) => {
                    console.log(e)
                }).finally(() => {
                    // noinspection all
                    bootstrap.Offcanvas.getInstance("#offcanvasNavbar")?.hide();
                });

            });
        });

        $logout?.addEventListener(
            "click", async () => {
                await (container.resolve(Login)).logout();
            },
        );

        //document.onkeydown = OnKeyPressEventManager;
        document.addEventListener("keydown", OnKeyPressEventManager);
        document.addEventListener("keydown", CursorFormFlow);

        let progressBar = new ProgressBar(5);

        let initialCache: Promise<any>[] = [];

        await Promise.all(initialCache);

        progressBar.lastStage();

        let cacheForms: Promise<any>[] = [];

        await Promise.all(cacheForms);

        let mainNav = document.getElementById("main-navigation") as HTMLDivElement;
        let containerMainTab = document.getElementById("containerMainTab") as HTMLDivElement;
        let loaderSpinnerContainer = document.getElementById("loaderSpinnerContainer") as HTMLDivElement;
        mainNav.style.visibility = "unset";
        containerMainTab.style.visibility = "unset"
        loaderSpinnerContainer.style.display = "unset";

        progressBar?.remove().then(null);

        document.addEventListener('focusin', (e) => {
            //@ts-ignore
            if (e.target?.closest(".tox-tinymce-aux, .moxman-window, .tam-assetmanager-root") !== null) {
                e.stopImmediatePropagation();
            }
        });

        container.resolve(Start).openTab("Dashboard").then(null).catch(null);
        //this.ws().then(null);

    }
)
