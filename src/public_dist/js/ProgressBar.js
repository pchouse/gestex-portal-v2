export class ProgressBar {
    constructor(progressStep) {
        this.progressStep = progressStep;
        this.progress = 0;
        this.progressBar = null;
        this.initLoader = null;
        this.progressBar = document.getElementById("main-progressbar");
        this.initLoader = document.getElementById("init-loader");
    }
    lastStage() {
        var _a, _b;
        (_a = this.progressBar) === null || _a === void 0 ? void 0 : _a.classList.remove("bg-warning");
        (_b = this.progressBar) === null || _b === void 0 ? void 0 : _b.classList.add("bg-primary");
    }
    remove() {
        return new Promise((resolve, reject) => {
            setTimeout(() => {
                var _a, _b;
                (_a = this.progressBar) === null || _a === void 0 ? void 0 : _a.classList.remove("bg-warning", "bg-primary");
                (_b = this.progressBar) === null || _b === void 0 ? void 0 : _b.classList.add("bg-success");
                setTimeout(() => { var _a, _b; return (_b = (_a = this.progressBar) === null || _a === void 0 ? void 0 : _a.parentElement) === null || _b === void 0 ? void 0 : _b.remove(); }, 999);
            }, 299);
            resolve();
        });
    }
    forwardProgressBar() {
        var _a;
        if (this.progressBar === null)
            return;
        this.progress += this.progressStep;
        if (this.progress > 0)
            (_a = this.initLoader) === null || _a === void 0 ? void 0 : _a.remove();
        this.progressBar.style.width = `${this.progress}%`;
    }
}
//# sourceMappingURL=ProgressBar.js.map