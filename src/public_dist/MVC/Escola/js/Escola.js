var __decorate = (this && this.__decorate) || function (decorators, target, key, desc) {
    var c = arguments.length, r = c < 3 ? target : desc === null ? desc = Object.getOwnPropertyDescriptor(target, key) : desc, d;
    if (typeof Reflect === "object" && typeof Reflect.decorate === "function") r = Reflect.decorate(decorators, target, key, desc);
    else for (var i = decorators.length - 1; i >= 0; i--) if (d = decorators[i]) r = (c < 3 ? d(r) : c > 3 ? d(target, key, r) : d(target, key)) || r;
    return c > 3 && r && Object.defineProperty(target, key, r), r;
};
var __metadata = (this && this.__metadata) || function (k, v) {
    if (typeof Reflect === "object" && typeof Reflect.metadata === "function") return Reflect.metadata(k, v);
};
var Escola_1;
import { singleton, injectable } from "tsyringe";
import { FetchService } from "../../../js/FetchService";
let Escola = Escola_1 = class Escola {
    static get baseUrl() {
        return this._baseUrl;
    }
    constructor(fetchService) {
        this.tabContainer = null;
        this.fetchService = fetchService;
    }
    init() {
        return new Promise(async (resolve, reject) => {
            try {
                return resolve();
            }
            catch (e) {
                console.log(e);
                reject(e);
            }
        });
    }
};
Escola.cache = new Map();
Escola.html = null;
Escola.ControllerName = "Escola";
Escola._baseUrl = `/?controller=${Escola_1.ControllerName}`;
Escola.tableId = "escola-table";
Escola = Escola_1 = __decorate([
    injectable(),
    singleton(),
    __metadata("design:paramtypes", [FetchService])
], Escola);
export { Escola };
//# sourceMappingURL=Escola.js.map