export declare class FetchService {
    constructor();
    get(url: string, options?: RequestInit | undefined): Promise<globalThis.Response>;
    post(url: string, body: any, abortController?: AbortController | undefined | null): Promise<globalThis.Response>;
}
