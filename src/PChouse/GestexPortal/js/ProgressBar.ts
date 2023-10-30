export class ProgressBar {

    protected progress = 0;
    protected progressBar: HTMLDivElement | null = null;
    protected initLoader: HTMLDivElement | null = null;

    constructor(protected progressStep: number) {
        this.progressBar = document.getElementById("main-progressbar") as HTMLDivElement | null;
        this.initLoader = document.getElementById("init-loader") as HTMLDivElement | null;
    }

    lastStage(): void
    {
        this.progressBar?.classList.remove("bg-warning");
        this.progressBar?.classList.add("bg-primary");
    }

    remove(): Promise<void> {
        return new Promise((resolve, reject) => {
            setTimeout(() => {
                this.progressBar?.classList.remove("bg-warning", "bg-primary");
                this.progressBar?.classList.add("bg-success");
                setTimeout(() => this.progressBar?.parentElement?.remove(), 999)
            }, 299);
            resolve();
        });
    }

    forwardProgressBar(): void {
        if (this.progressBar === null) return;
        this.progress += this.progressStep;
        if (this.progress > 0) this.initLoader?.remove();
        this.progressBar.style.width = `${this.progress}%`;
    }
}