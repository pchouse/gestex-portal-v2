var __decorate = (this && this.__decorate) || function (decorators, target, key, desc) {
    var c = arguments.length, r = c < 3 ? target : desc === null ? desc = Object.getOwnPropertyDescriptor(target, key) : desc, d;
    if (typeof Reflect === "object" && typeof Reflect.decorate === "function") r = Reflect.decorate(decorators, target, key, desc);
    else for (var i = decorators.length - 1; i >= 0; i--) if (d = decorators[i]) r = (c < 3 ? d(r) : c > 3 ? d(target, key, r) : d(target, key)) || r;
    return c > 3 && r && Object.defineProperty(target, key, r), r;
};
import { singleton, injectable } from "tsyringe";
let FileDownload = class FileDownload {
    showPdfFileFromResponse(response) {
        return new Promise((resolve, reject) => {
            var _a, _b;
            try {
                if (typeof response.data === "undefined" || response.data === null) {
                    reject("Nenhum ficheiro recebido do servidor");
                    return;
                }
                let id = `gestex_pdf_response_${Math.floor(Math.random() * 9999).toString()}`;
                let link = document.createElement('a');
                link.id = id;
                link.href = `data:application/octet-stream;base64, ${response.data}`;
                link.download = (_a = response.extraData) !== null && _a !== void 0 ? _a : "gestex.pdf";
                link.target = "_blank";
                link.click();
                (_b = document.getElementById(id)) === null || _b === void 0 ? void 0 : _b.remove();
                resolve();
            }
            catch (e) {
                console.log(e);
                reject(e);
            }
        });
    }
    showTxtFileFromResponse(response) {
        return new Promise((resolve, reject) => {
            var _a, _b;
            try {
                if (typeof response.data === "undefined" || response.data === null) {
                    reject("Nenhum ficheiro recebido do servidor");
                    return;
                }
                let id = `txt_file_${Math.floor(Math.random() * 9999).toString()}`;
                let link = document.createElement('a');
                link.id = id;
                link.href = `data:text/plain;base64, ${response.data}`;
                link.download = (_a = response.extraData) !== null && _a !== void 0 ? _a : `${id}.txt`;
                link.target = "_blank";
                link.click();
                (_b = document.getElementById(id)) === null || _b === void 0 ? void 0 : _b.remove();
                resolve();
            }
            catch (e) {
                console.log(e);
                reject(e);
            }
        });
    }
};
FileDownload = __decorate([
    injectable(),
    singleton()
], FileDownload);
export { FileDownload };
//# sourceMappingURL=FileDownload.js.map