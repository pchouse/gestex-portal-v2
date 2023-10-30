export declare class ProgressBar {
    protected progressStep: number;
    protected progress: number;
    protected progressBar: HTMLDivElement | null;
    protected initLoader: HTMLDivElement | null;
    constructor(progressStep: number);
    lastStage(): void;
    remove(): Promise<void>;
    forwardProgressBar(): void;
}
