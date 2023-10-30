export function CursorFormFlow(evt: KeyboardEvent): Promise<void> {

    return new Promise<void>((resolve) => {

        try {
            if (!["ArrowUp", "ArrowDown", "ArrowLeft", "ArrowRight"].includes(evt.key)) {
                return resolve();
            }

            let element = evt.target as HTMLElement;

            let dataPositionParts: string[] | null | undefined = element.getAttribute("data-position")?.split("_");

            if (dataPositionParts === undefined || dataPositionParts === null) return;

            if (dataPositionParts.length < 2) return resolve();

            let horizontal = Number(dataPositionParts[0]);
            let vertical = Number(dataPositionParts[1]);

            switch (evt.key) {
                case "ArrowRight":
                    vertical++;
                    break
                case "ArrowLeft":
                    vertical--;
                    break
                case "ArrowUp":
                    horizontal--;
                    break
                case "ArrowDown":
                    horizontal++;
                    break
                default:
                    return resolve();
            }

            let querySelector = `input[data-position="${horizontal}_${vertical}"]`;

            let queryDom = (element as HTMLInputElement).form?.querySelectorAll(querySelector);

            if (queryDom !== undefined && queryDom.length > 0) (queryDom[0] as HTMLInputElement).focus();

            resolve();
        }catch (e) {
            resolve()
        }
    });
}