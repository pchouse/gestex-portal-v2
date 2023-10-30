import { Response } from "./Response";
export declare class FileDownload {
    showPdfFileFromResponse(response: Response): Promise<void>;
    showTxtFileFromResponse(response: Response): Promise<void>;
}
