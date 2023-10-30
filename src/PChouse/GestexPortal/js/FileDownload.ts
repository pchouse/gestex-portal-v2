import {Response} from "./Response";
import {singleton, injectable} from "tsyringe";

@injectable()
@singleton()
export class FileDownload {


    showPdfFileFromResponse(response: Response): Promise<void> {
        return new Promise<void>((resolve, reject) => {
            try {

                if(typeof response.data === "undefined" || response.data === null)
                {
                    reject("Nenhum ficheiro recebido do servidor");
                    return;
                }

                let id = `gestex_pdf_response_${Math.floor(Math.random() * 9999).toString()}`;
                let link = document.createElement('a') as HTMLAnchorElement;
                link.id = id;
                link.href = `data:application/octet-stream;base64, ${response.data}`;
                link.download = response.extraData ?? "gestex.pdf";
                link.target = "_blank";
                link.click();
                document.getElementById(id)?.remove();
                resolve();
            } catch (e) {
                console.log(e);
                reject(e);
            }
        });
    }

    showTxtFileFromResponse(response: Response): Promise<void> {
        return new Promise<void>((resolve, reject) => {
            try {

                if(typeof response.data === "undefined" || response.data === null)
                {
                    reject("Nenhum ficheiro recebido do servidor");
                    return;
                }

                let id = `txt_file_${Math.floor(Math.random() * 9999).toString()}`;
                let link = document.createElement('a') as HTMLAnchorElement;
                link.id = id;
                link.href = `data:text/plain;base64, ${response.data}`;
                link.download = response.extraData ?? `${id}.txt`;
                link.target = "_blank";
                link.click();
                document.getElementById(id)?.remove();
                resolve();
            } catch (e) {
                console.log(e);
                reject(e);
            }
        });
    }

}