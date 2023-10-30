import { container } from "tsyringe";
import { DateProvider } from "./DateProvider";
export class Util {
    /**
     * Show tost
     * @param msg
     */
    static showToast(msg) {
        return new Promise((resolve, reject) => {
            var _a;
            try {
                if (Util.toastBody === null) {
                    return resolve();
                }
                Util.toastBody.innerText = msg;
                (_a = Util.toast) === null || _a === void 0 ? void 0 : _a.show();
                return resolve();
            }
            catch (e) {
                return reject(e);
            }
        });
    }
    /**
     * Test if string is nul or empty
     * @param value
     */
    static isEmpty(value) {
        if (typeof value === "undefined" || value === null)
            return true;
        return value.trim() === "";
    }
    /**
     * Test if string is not nul or not empty
     * @param value
     */
    static isNotEmpty(value) {
        return !Util.isEmpty(value);
    }
    /**
     * Show/Hide loader spinner
     * @param visible
     */
    static loaderSpinnerContainerVisible(visible) {
        return new Promise((resolve, reject) => {
            try {
                if (Util.loaderSpinnerContainer === null) {
                    Util.loaderSpinnerContainer = document.getElementById("loaderSpinnerContainer");
                }
                if (Util.loaderSpinnerContainer === null)
                    resolve();
                Util.loaderSpinnerContainer.classList.remove("visually-hidden");
                if (!visible) {
                    Util.loaderSpinnerContainer.classList.add("visually-hidden");
                }
                resolve();
            }
            catch (e) {
                reject(e);
            }
        });
    }
    static isUserDateValid(date) {
        return date.match(/^[0-9]{2}-[0-9]{2}-[0-9]{4}$/) !== null;
    }
    static strDateToPTString(stringDate) {
        let dateParts = stringDate.split("-");
        if (dateParts.length !== 3)
            return null;
        let date = new Date(//@ts-ignore
        dateParts[2] * 1, dateParts[1] - 1, dateParts[0] * 1, 0, 0, 0, 0);
        return Util.dateToPTString(date);
    }
    static dateToPTString(date) {
        //@ts-ignore
        let dayOfweek = Util.weekDays[date.getDay()];
        let day = date.getDate();
        //@ts-ignore
        let month = Util.months[date.getMonth()];
        let year = date.getFullYear();
        return `${dayOfweek}, ${day} de ${month} de ${year}`;
    }
    static userDateRegExp() {
        return /[0-3][0-9]-[0-1][0-9]-20[1-3][0-9]/;
    }
    static isOutOfDate(dateString) {
        try {
            if (dateString.match(/^[0-9]{4}-[0-9]{2}-[0-9]{2}$/) === null) {
                return null;
            }
            let dateParts = dateString.split('-');
            let date = container.resolve(DateProvider).getDateHourZero();
            date.setFullYear(Number(dateParts[0]), Number(dateParts[1]) - 1, Number(dateParts[2]));
            let today = container.resolve(DateProvider).getDateHourZero();
            return today.getTime() > date.getTime();
        }
        catch (e) {
            console.log(e);
            return null;
        }
    }
    static closeModal(modal) {
        modal.hide();
        modal.dispose();
        Util.afterCloseModel();
    }
    static afterCloseModel() {
        if (Util.bodyElement === null) {
            Util.bodyElement = document.getElementById("body");
        }
        const style = Util.bodyElement.style;
        style.overflow = "auto";
        style.paddingRight = "";
        Util.bodyElement.classList.remove("model-open");
    }
}
Util.toast = null;
Util.toastBody = null;
Util.bodyElement = null;
/**
 * Loader spinner html element
 * @private
 */
Util.loaderSpinnerContainer = null;
Util.weekDays = {
    0: "Domingo",
    1: "2ª feira",
    2: "3ª feira",
    3: "4ª feira",
    4: "5ª feira",
    5: "6ª feira",
    6: "Sábado",
};
Util.months = {
    0: "Janeiro",
    1: "Fevereiro",
    2: "Março",
    3: "Abril",
    4: "Maio",
    5: "Junho",
    6: "Julho",
    7: "Agosto",
    8: "Setembro",
    9: "Outubro",
    10: "Novembro",
    11: 'Dezembro',
};
Util.alvaraCache = new Map();
Util.alvaraCacheIsloading = [];
//# sourceMappingURL=Util.js.map