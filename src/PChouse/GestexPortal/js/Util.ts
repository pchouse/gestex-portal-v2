import {Toast} from "bootstrap";
import {container} from "tsyringe";
import {DateProvider} from "./DateProvider";
import {Modal} from "bootstrap"

export class Util {

    static toast: Toast | null = null

    static toastBody: HTMLDivElement | null = null;

    public static bodyElement: HTMLBodyElement | null = null;

    /**
     * Show tost
     * @param msg
     */
    public static showToast(msg: string): Promise<void> {
        return new Promise<void>((resolve, reject) => {
            try {
                if (Util.toastBody === null) {
                    return resolve();
                }
                Util.toastBody.innerText = msg;
                Util.toast?.show();
                return resolve();
            } catch (e) {
                return reject(e);
            }
        });
    }


    /**
     * Test if string is nul or empty
     * @param value
     */
    static isEmpty(value: string | null | undefined): boolean {
        if (typeof value === "undefined" || value === null) return true;
        return value.trim() === ""
    }

    /**
     * Test if string is not nul or not empty
     * @param value
     */
    static isNotEmpty(value: string | null | undefined): boolean {
        return !Util.isEmpty(value);
    }


    /**
     * Loader spinner html element
     * @private
     */
    private static loaderSpinnerContainer: HTMLElement | null = null;

    /**
     * Show/Hide loader spinner
     * @param visible
     */
    public static loaderSpinnerContainerVisible(visible: boolean): Promise<void> {
        return new Promise<void>((resolve, reject) => {
            try {

                if (Util.loaderSpinnerContainer === null) {
                    Util.loaderSpinnerContainer = document.getElementById("loaderSpinnerContainer");
                }

                if (Util.loaderSpinnerContainer === null) resolve();

                (Util.loaderSpinnerContainer as HTMLElement).classList.remove("visually-hidden");

                if (!visible) {
                    (Util.loaderSpinnerContainer as HTMLElement).classList.add("visually-hidden");
                }

                resolve();
            } catch (e) {
                reject(e);
            }
        });
    }

    public static isUserDateValid(date: string): boolean {
        return date.match(/^[0-9]{2}-[0-9]{2}-[0-9]{4}$/) !== null;
    }

    public static weekDays = {
        0: "Domingo",
        1: "2ª feira",
        2: "3ª feira",
        3: "4ª feira",
        4: "5ª feira",
        5: "6ª feira",
        6: "Sábado",
    }

    public static months = {
        0 : "Janeiro",
        1 : "Fevereiro",
        2 : "Março",
        3 : "Abril",
        4 : "Maio",
        5 : "Junho",
        6 : "Julho",
        7 : "Agosto",
        8 : "Setembro",
        9 : "Outubro",
        10: "Novembro",
        11: 'Dezembro',
    }


    public static strDateToPTString(stringDate: String): string | null {
        let dateParts = stringDate.split("-");
        if (dateParts.length !== 3) return null;

        let date = new Date(//@ts-ignore
            dateParts[2] * 1, dateParts[1] - 1, dateParts[0] * 1, 0, 0, 0, 0
        );
        return Util.dateToPTString(date);
    }

    public static dateToPTString(date: Date): string {
        //@ts-ignore
        let dayOfweek = Util.weekDays[date.getDay()];
        let day = date.getDate();
        //@ts-ignore
        let month = Util.months[date.getMonth()];
        let year = date.getFullYear();

        return `${dayOfweek}, ${day} de ${month} de ${year}`;
    }


    public static userDateRegExp(): RegExp {
        return /[0-3][0-9]-[0-1][0-9]-20[1-3][0-9]/;
    }

    protected static alvaraCache = new Map<string, string>()

    protected static alvaraCacheIsloading: string[] = []

    public static isOutOfDate(dateString: string): boolean|null {

            try {

                if (dateString.match(/^[0-9]{4}-[0-9]{2}-[0-9]{2}$/) === null) {
                    return null;
                }

                let dateParts = dateString.split('-');

                let date = container.resolve(DateProvider).getDateHourZero();
                date.setFullYear(Number(dateParts[0]), Number(dateParts[1]) - 1, Number(dateParts[2]));

                let today = container.resolve(DateProvider).getDateHourZero();

                return  today.getTime() > date.getTime();

            } catch (e) {
                console.log(e);
                return null;
            }
    }

    public static closeModal(modal: Modal): void {
        modal.hide();
        modal.dispose();
        Util.afterCloseModel();
    }

    public static afterCloseModel(): void {
        if (Util.bodyElement === null) {
            Util.bodyElement = document.getElementById("body") as HTMLBodyElement;
        }
        const style = Util.bodyElement.style;
        style.overflow = "auto";
        style.paddingRight = "";
        Util.bodyElement.classList.remove("model-open");
    }

}
