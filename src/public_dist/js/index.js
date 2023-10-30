import "reflect-metadata";
import 'bootstrap/dist/css/bootstrap.css';
import 'bootstrap-icons/font/bootstrap-icons.css';
import "tabulator-tables/dist/css/tabulator_bootstrap5.css";
import "font-awesome/css/font-awesome.css";
import * as bootstrap from 'bootstrap';
import * as luxon from "luxon";
window.luxon = luxon;
import { Start } from "../MVC/Start/js/Start";
import { Login } from "../MVC/Login/js/Login";
import { Util } from "./Util";
import { OnKeyPressEventManager } from "./OnKeyPressEventManager";
import { container } from "tsyringe";
import { CursorFormFlow } from "./CursorFormFlow";
import { ProgressBar } from "./ProgressBar";
window.addEventListener("load", async () => {
    var _a;
    let $logout = document.getElementById("logout");
    let $menuStack = (_a = document.getElementById("offcanvasListContainer")) === null || _a === void 0 ? void 0 : _a.querySelectorAll("[data-gx-menu-controller]");
    Util.toastBody = document.getElementById("liveToastBody");
    // noinspection all
    Util.toast = bootstrap.Toast.getOrCreateInstance(document.getElementById("liveToast"));
    $menuStack === null || $menuStack === void 0 ? void 0 : $menuStack.forEach((element) => {
        element.addEventListener("click", () => {
            var _a;
            let controller = (_a = element.getAttribute("data-gx-menu-controller")) !== null && _a !== void 0 ? _a : "";
            if (Util.isEmpty(controller))
                return;
            container.resolve(Start).openTab(controller).catch((e) => {
                console.log(e);
            }).finally(() => {
                var _a;
                // noinspection all
                (_a = bootstrap.Offcanvas.getInstance("#offcanvasNavbar")) === null || _a === void 0 ? void 0 : _a.hide();
            });
        });
    });
    $logout === null || $logout === void 0 ? void 0 : $logout.addEventListener("click", async () => {
        await (container.resolve(Login)).logout();
    });
    //document.onkeydown = OnKeyPressEventManager;
    document.addEventListener("keydown", OnKeyPressEventManager);
    document.addEventListener("keydown", CursorFormFlow);
    let progressBar = new ProgressBar(5);
    let initialCache = [];
    await Promise.all(initialCache);
    progressBar.lastStage();
    let cacheForms = [];
    await Promise.all(cacheForms);
    let mainNav = document.getElementById("main-navigation");
    let containerMainTab = document.getElementById("containerMainTab");
    let loaderSpinnerContainer = document.getElementById("loaderSpinnerContainer");
    mainNav.style.visibility = "unset";
    containerMainTab.style.visibility = "unset";
    loaderSpinnerContainer.style.display = "unset";
    progressBar === null || progressBar === void 0 ? void 0 : progressBar.remove().then(null);
    document.addEventListener('focusin', (e) => {
        var _a;
        //@ts-ignore
        if (((_a = e.target) === null || _a === void 0 ? void 0 : _a.closest(".tox-tinymce-aux, .moxman-window, .tam-assetmanager-root")) !== null) {
            e.stopImmediatePropagation();
        }
    });
    container.resolve(Start).openTab("Dashboard").then(null).catch(null);
    //this.ws().then(null);
});
//# sourceMappingURL=index.js.map