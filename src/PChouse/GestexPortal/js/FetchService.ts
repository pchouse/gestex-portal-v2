import {injectable} from "tsyringe";

@injectable()
export class FetchService{

    constructor() {
    }

    get(url:string, options: RequestInit|undefined = undefined): Promise<globalThis.Response>
    {
        return fetch(url, options)
    }

    post(url: string, body: any, abortController: AbortController|undefined|null = undefined): Promise<globalThis.Response>
    {
        return fetch(url, {
            method: "POST",
            body  : body,
            signal: abortController?.signal
        });
    }

}