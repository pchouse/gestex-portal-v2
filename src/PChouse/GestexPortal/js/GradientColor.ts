export class Gradient {

    constructor(
        public colorGradients: any[] = [],
        public maxNum = 10,
        public colors = ["", ""],
        public intervals: any[] = []
    ) {

    }

    setColorGradient(gradientColors: any[]) {
        if (gradientColors.length < 2) {
            throw new Error(
                `setColorGradient should have more than ${gradientColors.length} color`
            );
        } else {
            const increment = this.maxNum / (gradientColors.length - 1);
            const firstColorGradient = new GradientColor();
            const lower = 0;
            const upper = increment;
            firstColorGradient.setColorGradient(
                gradientColors[0],
                gradientColors[1]
            );
            firstColorGradient.setMidpoint(lower, upper);
            this.colorGradients = [firstColorGradient];
            this.intervals = [
                {
                    lower,
                    upper,
                },
            ];

            for (let i = 1; i < gradientColors.length - 1; i++) {
                const gradientColor = new GradientColor();
                const lower = increment * i;
                const upper = increment * (i + 1);
                gradientColor.setColorGradient(
                    gradientColors[i],
                    gradientColors[i + 1]
                );
                gradientColor.setMidpoint(lower, upper);
                this.colorGradients[i] = gradientColor;
                this.intervals[i] = {
                    lower,
                    upper,
                };
            }
            this.colors = gradientColors;
        }
    };

    getColors() {
        const gradientColorsArray = [];
        for (let j = 0; j < this.intervals.length; j++) {
            const interval = this.intervals[j];
            const start = interval.lower === 0 ? 1 : Math.ceil(interval.lower);
            const end =
                interval.upper === this.maxNum
                    ? interval.upper + 1
                    : Math.ceil(interval.upper);
            for (let i = start; i < end; i++) {
                gradientColorsArray.push(this.colorGradients[j].getColor(i));
            }
        }
        return gradientColorsArray;
    };

    getColor(numberValue: number) {
        if (isNaN(numberValue)) {
            throw new TypeError(`getColor should be a number`);
        } else if (numberValue <= 0) {
            throw new TypeError(`getColor should be greater than ${numberValue}`);
        } else {
            const toInsert = numberValue + 1;
            const segment = (this.maxNum) / this.colorGradients.length;
            const index = Math.min(
                Math.floor((Math.max(numberValue, 0)) / segment),
                this.colorGradients.length - 1
            );
            return this.colorGradients[index].getColor(toInsert);
        }
    };

    setMidpoint(maxNumber: number) {
        if (!isNaN(maxNumber) && maxNumber >= 0) {
            this.maxNum = maxNumber;
            this.setColorGradient(this.colors);
        } else if (maxNumber <= 0) {
            throw new RangeError(`midPoint should be greater than ${maxNumber}`);
        } else {
            throw new RangeError("midPoint should be a number");
        }
        return this;
    };

}


export class GradientColor {


    constructor(
        public startColor = "",
        public endColor = "",
        public minNum = 0,
        public maxNum = 10) {

    }

    setColorGradient(colorStart:string, colorEnd: string) {
        this.startColor = this.getHexColor(colorStart);
        this.endColor = this.getHexColor(colorEnd);

    };

    setMidpoint(minNumber: number, maxNumber: number) {
        this.minNum = minNumber;
        this.maxNum = maxNumber;
    };

    getColor(numberValue: number) {
        if (numberValue) {
            return (
                "#" +
                this.generateHex(
                    numberValue,
                    this.startColor.substring(0, 2),
                    this.endColor.substring(0, 2)
                ) +
                this.generateHex(
                    numberValue,
                    this.startColor.substring(2, 4),
                    this.endColor.substring(2, 4)
                ) +
                this.generateHex(
                    numberValue,
                    this.startColor.substring(4, 6),
                    this.endColor.substring(4, 6)
                )
            );
        }
    };

    generateHex(number: number|string, start: number|string, end: number|string){
        if (number < this.minNum) {
            number = this.minNum;
        } else if (number > this.maxNum) {
            number = this.maxNum;
        }

        const midPoint = this.maxNum - this.minNum;
        const startBase = parseInt(start.toString(), 16);
        const endBase = parseInt(end.toString(), 16);
        const average = (endBase - startBase) / midPoint;
        const finalBase = Math.round(average * (Number(number) - this.minNum) + startBase);
        return finalBase < 16 ? "0" + finalBase.toString(16) : finalBase.toString(16);
    };

    getHexColor(color: string){
        return color.substring(color.length - 6, color.length);
    };

}

