import "reflect-metadata";
import 'bootstrap/dist/css/bootstrap.css';
import 'bootstrap-icons/font/bootstrap-icons.css';
import * as bootstrap from 'bootstrap'
import { Login } from "../MVC/Login/js/Login";
import { OnKeyPressEventManager } from "./OnKeyPressEventManager";
import {LoginForm} from "../MVC/Login/js/LoginForm";
import {container} from "tsyringe";

window.addEventListener(
    "load", () => {

        (container.resolve(LoginForm)).setLoadFocus();

        document.getElementById("login_submit")?.addEventListener(
            "click", async () => {
                await (container.resolve(Login)).authenticate();
            }
        )
        document.onkeydown = OnKeyPressEventManager;
    }
)
