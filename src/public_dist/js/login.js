import "reflect-metadata";
import 'bootstrap/dist/css/bootstrap.css';
import 'bootstrap-icons/font/bootstrap-icons.css';
import { Login } from "../MVC/Login/js/Login";
import { OnKeyPressEventManager } from "./OnKeyPressEventManager";
import { LoginForm } from "../MVC/Login/js/LoginForm";
import { container } from "tsyringe";
window.addEventListener("load", () => {
    var _a;
    (container.resolve(LoginForm)).setLoadFocus();
    (_a = document.getElementById("login_submit")) === null || _a === void 0 ? void 0 : _a.addEventListener("click", async () => {
        await (container.resolve(Login)).authenticate();
    });
    document.onkeydown = OnKeyPressEventManager;
});
//# sourceMappingURL=login.js.map