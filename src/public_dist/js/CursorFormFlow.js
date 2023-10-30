export function CursorFormFlow(evt) {
    return new Promise((resolve) => {
        var _a, _b;
        try {
            if (!["ArrowUp", "ArrowDown", "ArrowLeft", "ArrowRight"].includes(evt.key)) {
                return resolve();
            }
            let element = evt.target;
            let dataPositionParts = (_a = element.getAttribute("data-position")) === null || _a === void 0 ? void 0 : _a.split("_");
            if (dataPositionParts === undefined || dataPositionParts === null)
                return;
            if (dataPositionParts.length < 2)
                return resolve();
            let horizontal = Number(dataPositionParts[0]);
            let vertical = Number(dataPositionParts[1]);
            switch (evt.key) {
                case "ArrowRight":
                    vertical++;
                    break;
                case "ArrowLeft":
                    vertical--;
                    break;
                case "ArrowUp":
                    horizontal--;
                    break;
                case "ArrowDown":
                    horizontal++;
                    break;
                default:
                    return resolve();
            }
            let querySelector = `input[data-position="${horizontal}_${vertical}"]`;
            let queryDom = (_b = element.form) === null || _b === void 0 ? void 0 : _b.querySelectorAll(querySelector);
            if (queryDom !== undefined && queryDom.length > 0)
                queryDom[0].focus();
            resolve();
        }
        catch (e) {
            resolve();
        }
    });
}
//# sourceMappingURL=CursorFormFlow.js.map