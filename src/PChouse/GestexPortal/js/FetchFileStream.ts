import {container} from "tsyringe";
import {FetchService} from "./FetchService";
/**
 *
 * @param formData
 * @param fileName
 * @param type Ex: text/xml
 * @constructor
 */
export function FetchFileStream(formData: FormData, fileName: string, type: string): Promise<void> {


    return new Promise<void>(async (resolve, reject) => {

        let fetch = await container.resolve(FetchService).post("/", formData);

        let text = await fetch.text();

        if(fetch.status !== 200) return reject(text);

        let link = document.createElement('a');
        link.href = `data:${type};base64,${text}`;
        link.id = "blob_response";
        link.download = fileName;
        link.target = "_blank";
        link.click();
        link.remove();
        return resolve();

    });
}
