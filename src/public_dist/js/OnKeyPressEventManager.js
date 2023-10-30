export function OnKeyPressEventManager(event) {
    return new Promise((resolve) => {
        var _a;
        let rx = /INPUT|SELECT|TEXTAREA/i;
        if (event.code === "Backspace") {
            try {
                if (event.target instanceof HTMLInputElement) {
                    let element = event.target;
                    if (!rx.test(element.tagName) || element.disabled || element.readOnly) {
                        event.preventDefault();
                    }
                }
            }
            catch (err) {
                console.log(err);
            }
        }
        if (event.key !== "Enter")
            return;
        try {
            if (event.target instanceof HTMLInputElement ||
                event.target instanceof HTMLSelectElement ||
                event.target instanceof HTMLOptionElement) {
                let element = event.target;
                let formId = (_a = element.form) === null || _a === void 0 ? void 0 : _a.id;
                if (formId === null) {
                    return resolve();
                }
                let flow = element.getAttribute("data-flow");
                if (flow === null) {
                    return resolve();
                }
                let parts = flow.split(":");
                let index = Number(parts[1]);
                if (isNaN(index)) {
                    try {
                        setTimeout(() => {
                            (new Function(parts[1]))();
                        }, 1);
                    }
                    catch (e) {
                    }
                    return resolve();
                }
                let next = document.querySelector(`#${formId} [data-flow^='${index.valueOf()}:']`);
                if (next instanceof HTMLInputElement ||
                    next instanceof HTMLTextAreaElement ||
                    next instanceof HTMLSelectElement ||
                    next instanceof HTMLOptionElement) {
                    next.focus();
                }
            }
            resolve();
        }
        catch (err) {
            resolve();
            console.log(err);
        }
    });
}
//# sourceMappingURL=OnKeyPressEventManager.js.map