export declare class Gradient {
    colorGradients: any[];
    maxNum: number;
    colors: string[];
    intervals: any[];
    constructor(colorGradients?: any[], maxNum?: number, colors?: string[], intervals?: any[]);
    setColorGradient(gradientColors: any[]): void;
    getColors(): any[];
    getColor(numberValue: number): any;
    setMidpoint(maxNumber: number): this;
}
export declare class GradientColor {
    startColor: string;
    endColor: string;
    minNum: number;
    maxNum: number;
    constructor(startColor?: string, endColor?: string, minNum?: number, maxNum?: number);
    setColorGradient(colorStart: string, colorEnd: string): void;
    setMidpoint(minNumber: number, maxNumber: number): void;
    getColor(numberValue: number): string | undefined;
    generateHex(number: number | string, start: number | string, end: number | string): string;
    getHexColor(color: string): string;
}
