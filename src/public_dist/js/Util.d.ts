import { Toast } from "bootstrap";
import { Modal } from "bootstrap";
export declare class Util {
    static toast: Toast | null;
    static toastBody: HTMLDivElement | null;
    static bodyElement: HTMLBodyElement | null;
    /**
     * Show tost
     * @param msg
     */
    static showToast(msg: string): Promise<void>;
    /**
     * Test if string is nul or empty
     * @param value
     */
    static isEmpty(value: string | null | undefined): boolean;
    /**
     * Test if string is not nul or not empty
     * @param value
     */
    static isNotEmpty(value: string | null | undefined): boolean;
    /**
     * Loader spinner html element
     * @private
     */
    private static loaderSpinnerContainer;
    /**
     * Show/Hide loader spinner
     * @param visible
     */
    static loaderSpinnerContainerVisible(visible: boolean): Promise<void>;
    static isUserDateValid(date: string): boolean;
    static weekDays: {
        0: string;
        1: string;
        2: string;
        3: string;
        4: string;
        5: string;
        6: string;
    };
    static months: {
        0: string;
        1: string;
        2: string;
        3: string;
        4: string;
        5: string;
        6: string;
        7: string;
        8: string;
        9: string;
        10: string;
        11: string;
    };
    static strDateToPTString(stringDate: String): string | null;
    static dateToPTString(date: Date): string;
    static userDateRegExp(): RegExp;
    protected static alvaraCache: Map<string, string>;
    protected static alvaraCacheIsloading: string[];
    static isOutOfDate(dateString: string): boolean | null;
    static closeModal(modal: Modal): void;
    static afterCloseModel(): void;
}
