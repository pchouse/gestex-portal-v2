import { FetchService } from "../../../js/FetchService";
export declare class Login {
    protected fetchService: FetchService;
    private controller;
    static logoutConfirmDialog: string | null;
    constructor(fetchService: FetchService);
    authenticate(): Promise<void>;
    logout(): Promise<void>;
    afterAuthenticate(): void;
}
