var __decorate = (this && this.__decorate) || function (decorators, target, key, desc) {
    var c = arguments.length, r = c < 3 ? target : desc === null ? desc = Object.getOwnPropertyDescriptor(target, key) : desc, d;
    if (typeof Reflect === "object" && typeof Reflect.decorate === "function") r = Reflect.decorate(decorators, target, key, desc);
    else for (var i = decorators.length - 1; i >= 0; i--) if (d = decorators[i]) r = (c < 3 ? d(r) : c > 3 ? d(target, key, r) : d(target, key)) || r;
    return c > 3 && r && Object.defineProperty(target, key, r), r;
};
var __metadata = (this && this.__metadata) || function (k, v) {
    if (typeof Reflect === "object" && typeof Reflect.metadata === "function") return Reflect.metadata(k, v);
};
import { injectable, singleton } from "tsyringe";
import { FormUtils } from "../../../js/FormUtils";
import { FetchService } from "../../../js/FetchService";
let LoginForm = class LoginForm extends FormUtils {
    constructor(fetchService) {
        var _a;
        super("/", fetchService);
        this.fetchService = fetchService;
        this.passwordEye = null;
        this.form = document.getElementById("loginForm");
        this.username = document.getElementById("username");
        this.password = document.getElementById("password");
        this.newPassword = document.getElementById("new_password");
        this.confirmPassword = document.getElementById("confirm_password");
        this.passwordEye = document.getElementById("login-eye");
        (_a = this.passwordEye) === null || _a === void 0 ? void 0 : _a.addEventListener("click", () => {
            this.password.type = this.password.type === "password" ?
                "text" : "password";
        });
    }
    onFormChange() {
    }
    setLoadFocus() {
        if (this.newPassword !== null) {
            this.password.focus();
            return;
        }
        this.username.focus();
    }
    getTable() {
        return null;
    }
    setSaveEventListener() {
    }
};
LoginForm = __decorate([
    injectable(),
    singleton(),
    __metadata("design:paramtypes", [FetchService])
], LoginForm);
export { LoginForm };
//# sourceMappingURL=LoginForm.js.map